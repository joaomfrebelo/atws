<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Change Payment Status Ws
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class ChangePaymentStatusWs extends AWs implements IChangePaymentStatusWs
{

    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\Payment\ChangePaymentStatus
     * @since  2.0.0
     */
    protected ChangePaymentStatus $changePaymentStatus;

    /**
     *
     * @param string $username            AT (e-fatura) username
     * @param string $password            AT (e-fatura) password
     * @param string $certificatePath     The certificate path
     * @param string $certificatePassword The certificate password
     * @param bool   $isTest              Define if teh SOAP request is to the test soap server
     * @since 2.0.0
     */
    public function __construct(string $username, string $password, string $certificatePath, string $certificatePassword, bool $isTest)
    {
        parent::__construct(
            $username, $password, $certificatePath, $certificatePassword, $isTest
        );
    }

    /**
     * Build the xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since  2.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AATWs::NS_ENVELOPE,
            "Body", null
        );

        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "ChangePaymentStatusRequest",
            "http://factemi.at.min_financas.pt/documents"
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "eFaturaMDVersion",
            null,
            AWs::E_FATURA_MD_VERSION
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxRegistrationNumber",
            null,
            $this->changePaymentStatus->getTaxRegistrationNumber()
        );

        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "PaymentHeader",
            null
        );

        $this->changePaymentStatus->getPaymentHeader()->buildXml($xml);

        $xml->endElement(); //PaymentHeader

        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "PaymentStatus",
            null
        );

        $this->changePaymentStatus->getNewPaymentStatus()->buildXml($xml);

        $xml->endElement(); //NewPaymentStatusType

        $this->changePaymentStatus->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //ChangePaymentRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit the change document status to teh AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\ChangePaymentStatus $changePaymentStatus
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(ChangePaymentStatus $changePaymentStatus): Response
    {
        $this->changePaymentStatus = $changePaymentStatus;
        return Response::factory(
            parent::doRequest()
        );
    }

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.0
     */
    public function getWsAction(): string
    {
        return "ChangePaymentStatus";
    }

}
