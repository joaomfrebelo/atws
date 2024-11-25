<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Response;
use Rebelo\Date\Pattern;

/**
 * Invoice Webservice
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class InvoiceWs extends AWs implements IInvoiceWs
{

    /**
     *
     * @var \Rebelo\ATWs\EFaturaMDVersion\Invoice\Invoice
     * @since 1.0.0
     */
    protected Invoice $invoice;

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
     * Build the invoice request xml body
     *
     * @param \XMLWriter $xml
     *
     * @return void
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "RegisterInvoiceRequest",
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
            $this->invoice->getTaxRegistrationNumber()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxEntity",
            null,
            $this->invoice->getTaxEntity()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SoftwareCertificateNumber",
            null,
            (string)$this->invoice->getSoftwareCertificateNumber()
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "InvoiceData",
            null
        );

        $data = $this->invoice->getInvoiceData();
        $data->getInvoiceHeader()->buildXml($xml);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "DocumentStatus",
            null
        );

        $data->getDocumentStatus()->buildXml($xml);

        $xml->endElement();

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "HashCharacters",
            null,
            $data->getHashCharacters()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "CashVATSchemeIndicator",
            null,
            $data->getCashVATSchemeIndicator() ? "1" : "0"
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "PaperLessIndicator",
            null,
            $data->getPaperLessIndicator() ? "1" : "0"
        );

        if ($data->getEacCode() !== null) {

            $xml->writeElementNs(
                static::NS_AT_WS_BODY,
                "EACCode",
                null,
                $data->getEacCode()
            );

        }
        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SystemEntryDate",
            null,
            $data->getSystemEntryDate()->format(Pattern::DATE_T_TIME)
        );

        foreach ($data->getLines() as $line) {
            $line->buildXml($xml);
        }

        $data->getDocumentTotals()->buildXml($xml);

        foreach ($data->getWithholdingTax() ?? [] as $withholding) {
            $withholding->buildXml($xml);
        }

        $xml->endElement(); //Invoice Data

        $this->invoice->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //RegisterInvoice
        $xml->endElement(); //Body
    }

    /**
     * Submit the invoice to the AT webservice
     *
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\Invoice $invoice
     *
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function submit(Invoice $invoice): Response
    {
        $this->invoice = $invoice;
        return Response::factory(
            parent::doRequest()
        );
    }


    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string
    {
        return "RegisterInvoice";
    }

}
