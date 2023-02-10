<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;
use SimpleXMLElement;

/**
 * response of consult self billing agreement
 * @since 2.0.2
 */
class ConsultSelfBillingAgreementResponse
{
    /**
     * @var \Rebelo\ATWs\Series\OperationResultInformation
     * @since 2.0.2
     */
    private OperationResultInformation $operationResultInformation;

    /**
     * @var SelfBillingAgreementInfo[]
     * @since 2.0.2
     */
    private array $selfBillingAgreementInfo;

    /**
     * Stores if the response is OK
     * @var bool
     * @since 2.0.2
     */
    private bool $isResponseOk = false;

    /**
     *
     * @since 2.0.2
     */
    private function __construct()
    {
    }

    /**
     * Parse the XML response
     * @param string $xml
     * @return \Rebelo\ATWs\Series\ConsultSelfBillingAgreementResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public static function factory(string $xml): ConsultSelfBillingAgreementResponse
    {
        if (false === $simpleXml = \simplexml_load_string(\trim($xml), SimpleXMLElement::class)) {
            throw new ATWsException("Wrong response from soap request");
        }

        $response = new ConsultSelfBillingAgreementResponse();

        try {

            $simpleXml->xpath("//ReturnCode");

            $fault = $simpleXml->xpath("//faultcode");
            if (\count($fault) > 0) {
                $response->operationResultInformation = new OperationResultInformation(
                    (int)$fault[0],
                    (string)$simpleXml->xpath("//faultstring")[0]
                );
                return $response;
            }

            $result = $simpleXml->xpath("//codResultOper");
            if (\count($result) > 0) {
                $response->isResponseOk               = \in_array((int)$result[0], [2001, 2002, 2004, 2003]);
                $response->operationResultInformation = new OperationResultInformation(
                    (int)$result[0],
                    (string)$simpleXml->xpath("//msgResultOper")[0]
                );

                $infoStack = $simpleXml->xpath("//infoAcordoAutofaturacao") ?? [];

                for ($index = 0; $index < \count($infoStack); $index++) {

                    $info = $infoStack[$index];

                    $response->selfBillingAgreementInfo[] = new SelfBillingAgreementInfo(
                        new SelfBillingEntityCode((string)$info->{"acordoRegistadoCom"}),
                        (string)$info->{"nifAdquirente"},
                        (string)$info->{"nomeAdquirente"},
                        (string)$info->{"nifAssociadoAoAcordo"},
                        (string)$info->{"nomeNifAssociadoAoAcordo"},
                        \count($info->{"paisEstrangeiro"}) === 0 ?
                            null : (string)$info->{"paisEstrangeiro"},
                        new SelfBillingSettlementStatus((string)$info->{"estado"}),
                        Date::parse(Date::SQL_DATE, (string)$info->{"periodoAutorizacaoDe"}),
                        \count($info->{"periodoAutorizacaoAte"}) === 0 ?
                            null : Date::parse(Date::SQL_DATE, (string)$info->{"periodoAutorizacaoDe"})
                    );
                }
            }

        } catch (\Throwable $e) {
            throw new ATWsException("Error parsing XML soap response: " . $e->getMessage());
        }

        return $response;
    }

    /**
     * Get the operation result
     * @return \Rebelo\ATWs\Series\OperationResultInformation
     * @since 2.0.2
     */
    public function getOperationResultInformation(): OperationResultInformation
    {
        return $this->operationResultInformation;
    }

    /**
     * Get the self billing agreement info
     * @return \Rebelo\ATWs\Series\SelfBillingAgreementInfo[]
     * @since 2.0.2
     */
    public function getSelfBillingAgreementInfo(): array
    {
        return $this->selfBillingAgreementInfo;
    }

    /**
     * Get is the response is OK or error
     * @return bool
     * @since 2.0.2
     */
    public function isResponseOk(): bool
    {
        return $this->isResponseOk;
    }

}
