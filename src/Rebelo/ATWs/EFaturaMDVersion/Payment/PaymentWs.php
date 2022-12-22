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
use Rebelo\Date\Date;

/**
 * Payments Webservice
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class PaymentWs extends AWs implements IPaymentWs
{
    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\Payment\Payment
     * @since 2.0.0
     */
    protected Payment $payment;

    /**
     *
     * @param string $username            AT (e-fatura) username
     * @param string $password            AT (e-fatura) password
     * @param string $certificatePath     The certificate path
     * @param string $certificatePassword The certificate password
     * @param bool   $isTest              Define if teh SOAP request is to the test soap server
     * @since 2.0.0
     */
    public function __construct(
        string $username,
        string $password,
        string $certificatePath,
        string $certificatePassword,
        bool   $isTest
    )
    {
        parent::__construct(
            $username, $password, $certificatePath, $certificatePassword, $isTest
        );
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "RegisterPaymentRequest",
            "http://factemi.at.min_financas.pt/documents"
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "eFaturaMDVersion",
            null,
            static::E_FATURA_MD_VERSION
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "AuditFileVersion",
            null,
            $this->getAuditFileVersion()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxRegistrationNumber",
            null,
            $this->payment->getTaxRegistrationNumber()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxEntity",
            null,
            $this->payment->getTaxEntity()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SoftwareCertificateNumber",
            null,
            (string)$this->payment->getSoftwareCertificateNumber()
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "PaymentData",
            null
        );

        $data = $this->payment->getPaymentData();
        $data->getPaymentHeader()->buildXml($xml);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "DocumentStatus",
            null
        );

        $data->getPaymentStatus()->buildXml($xml);

        $xml->endElement();

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SystemEntryDate",
            null,
            $data->getSystemEntryDate()->format(Date::DATE_T_TIME)
        );

        foreach ($data->getLines() as $line) {
            $line->buildXml($xml);
        }

        $data->getDocumentTotals()->buildXml($xml);

        foreach ($data->getWithholdingTax() ?: [] as $withholdingTax) {
            $withholdingTax->buildXml($xml);
        }

        $xml->endElement(); //PaymentData

        $this->payment->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //RegisterPayment
        $xml->endElement(); //Body
    }

    /**
     * Submit the payment document to the AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\Payment $payment
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(Payment $payment): Response
    {
        $this->payment = $payment;
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
        return "RegisterPayment";
    }

}
