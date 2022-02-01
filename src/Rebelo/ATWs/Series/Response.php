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
 * The Response from the SOAP server
 * @since 1.0.0
 */
class Response
{

    /**
     * @var \Rebelo\ATWs\Series\OperationResultInformation
     * @since 1.0.0
     */
    private OperationResultInformation $operationResultInformation;

    /**
     * @var \Rebelo\ATWs\Series\SeriesInformation[]
     * @since 1.0.0
     */
    private array $seriesInformation = [];

    /**
     * Stores if the response is OK
     * @var bool
     * @since 1.0.0
     */
    private bool $isResponseOk = false;

    /**
     *
     * @since 1.0.0
     */
    private function __construct()
    {
    }

    /**
     * Parse the XML response
     * @param string $xml
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public static function factory(string $xml): Response
    {
        if (false === $simpleXml = \simplexml_load_string(\trim($xml), SimpleXMLElement::class)) {
            throw new ATWsException("Wrong response from soap request");
        }

        $response = new Response();
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

                $infoSerieStack = $simpleXml->xpath("//infoSerie") ?? [];

                for ($index = 0; $index < \count($infoSerieStack); $index++) {

                    $infoSerie = $infoSerieStack[$index];

                    $response->seriesInformation[] = new SeriesInformation(
                        (string)$infoSerie->{"serie"},
                        new SeriesTypeCode((string)$infoSerie->{"tipoSerie"}),
                        new DocumentClassCode((string)$infoSerie->{"classeDoc"}),
                        new DocumentTypeCode((string)$infoSerie->{"tipoDoc"}),
                        (int)$infoSerie->{"numInicialSeq"},
                        Date::parse(Date::SQL_DATE, (string)$infoSerie->{"dataInicioPrevUtiliz"}),
                        \count($infoSerie->{"seqUltimoDocEmitido"}) === 0 ?
                            null : (int)$infoSerie->{"seqUltimoDocEmitido"},
                        new ProcessingMediumCodes((string)$infoSerie->{"meioProcessamento"}),
                        (int)$infoSerie->{"numCertSWFatur"},
                        (string)$infoSerie->{"codValidacaoSerie"},
                        Date::parse(Date::SQL_DATE, (string)$infoSerie->{"dataRegisto"}),
                        new SeriesStatusCode((string)$infoSerie->{"estado"}),
                        \count($infoSerie->{"motivoEstado"}) === 0 ?
                            null : (string)$infoSerie->{"motivoEstado"},
                        \count($infoSerie->{"justificacao"}) === 0 ?
                            null : (string)$infoSerie->{"justificacao"},
                        \count($infoSerie->{"dataEstado"}) === 0 ?
                            null : Date::parse(Date::DATE_T_TIME, \substr((string)$infoSerie->{"dataEstado"}, 0, 19)),
                        (string)$infoSerie->{"nifComunicou"}
                    );
                }

                return $response;
            }

        } catch (\Throwable $e) {
            throw new ATWsException("Error parsing XML soap response: " . $e->getMessage());
        }

        throw new ATWsException("XML parser not available for XML response");
    }

    /**
     * @return \Rebelo\ATWs\Series\OperationResultInformation
     * @since 1.0.0
     */
    public function getOperationResultInformation(): OperationResultInformation
    {
        return $this->operationResultInformation;
    }

    /**
     * @return \Rebelo\ATWs\Series\SeriesInformation[]
     * @since 1.0.0
     */
    public function getSeriesInformation(): array
    {
        return $this->seriesInformation;
    }

    /**
     * get if the response is OK (Not error)
     * @return bool
     * @since 1.0.0
     */
    public function isResponseOk(): bool
    {
        return $this->isResponseOk;
    }

}
