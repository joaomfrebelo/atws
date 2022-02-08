<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * Invoice Ws
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Ws extends AATWs
{

    /**
     * The WSDL file path
     * @since 1.0.0
     */
    const WSDL = __DIR__ . DIRECTORY_SEPARATOR . "factemiws.wsdl";

    /**
     * Namespace of
     * RegisterInvoiceElem xmlns:ns2="http://servicos.portaldasfinancas.gov.pt/faturas/
     *
     * @since 1.0.0
     */
    const NS_REGISTERINVOICE = "ns2";

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     *
     * @var \Rebelo\ATWs\Invoice\Invoice
     * @since 1.0.0
     */
    protected Invoice $invoice;

    /**
     * Build the invoice request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTERINVOICE,
            "RegisterInvoiceElem",
            "http://servicos.portaldasfinancas.gov.pt/faturas/"
        );
        $xml->writeElement("TaxRegistrationNumber", $this->invoice->getTaxRegistrationNumber());
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "InvoiceNo",
            null,
            $this->invoice->getInvoiceNo()
        );
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "InvoiceDate",
            null,
            $this->invoice->getInvoiceDate()->format(Date::SQL_DATE)
        );
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "InvoiceType",
            null,
            $this->invoice->getInvoiceType()
        );
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "InvoiceStatus",
            null,
            $this->invoice->getInvoiceStatus()
        );

        if (\is_string($this->invoice->getCustomerID())) {
            $xml->writeElement("CustomerTaxID", $this->invoice->getCustomerID());
        } else {
            $xml->startElementNs(static::NS_REGISTERINVOICE, "InternationalCustomerTaxID", null);
            $xml->writeElement("TaxIDNumber", $this->invoice->getCustomerID()->getTaxIDNumber());
            $xml->writeElement("TaxIDCountry", $this->invoice->getCustomerID()->getTaxIDCountry());
            $xml->endElement(); //InternationalCustomerTaxID
        }

        foreach ($this->invoice->getLines() as $line) {
            $xml->startElement("Line");

            $orderReferenceStack = $line->getOrderReference() ?? [];

            if (\count($orderReferenceStack) > 0) {
                $xml->startElementNs(static::NS_REGISTERINVOICE, "OrderReferences", null);
                foreach ($orderReferenceStack as $orderReference) {
                    $xml->startElementNs(static::NS_REGISTERINVOICE, "OrderReference", null);
                    $xml->writeElementNs(
                        static::NS_REGISTERINVOICE,
                        "OriginatingON",
                        null,
                        $orderReference->getOriginatingON()
                    );
                    $xml->writeElementNs(
                        static::NS_REGISTERINVOICE,
                        "OrderDate",
                        null,
                        $orderReference->getOrderDate()->format(Date::SQL_DATE)
                    );
                    $xml->endElement();
                }
                $xml->endElement();
            }

            $xml->writeElementNs(
                static::NS_REGISTERINVOICE,
                $line->getDebitAmount() ? "DebitAmount" : "CreditAmount",
                null,
                \number_format(
                    $line->getDebitAmount() ?? $line->getCreditAmount(),
                    AATWs::DECIMALS, ".", ""
                )
            );
            $xml->startElementNs(
                static::NS_REGISTERINVOICE,
                "Tax",
                null
            );
            $xml->writeElementNs(
                static::NS_REGISTERINVOICE,
                "TaxType",
                null,
                $line->getTaxType()
            );
            $xml->writeElementNs(
                static::NS_REGISTERINVOICE,
                "TaxCountryRegion",
                null,
                $line->getTaxCountryRegion()
            );
            $xml->writeElementNs(
                static::NS_REGISTERINVOICE,
                "TaxPercentage",
                null,
                \number_format(
                    $line->getTaxPercentage(), 2, ".", ""
                )
            );

            $xml->endElement(); //Tax

            if ($line->getTaxExemptionReason() !== null) {
                $xml->writeElementNs(
                    static::NS_REGISTERINVOICE,
                    "TaxExemptionReason",
                    null,
                    $line->getTaxExemptionReason()
                );
            }

            $xml->endElement(); //Line
        }

        $xml->startElement("DocumentTotals");
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "TaxPayable",
            null,
            \number_format(
                $this->invoice->getDocumentTotals()->getTaxPayable(),
                AATWs::DECIMALS, ".", ""
            )
        );
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "NetTotal",
            null,
            \number_format(
                $this->invoice->getDocumentTotals()->getNetTotal(),
                AATWs::DECIMALS, ".", ""
            )
        );
        $xml->writeElementNs(
            static::NS_REGISTERINVOICE,
            "GrossTotal",
            null,
            \number_format(
                $this->invoice->getDocumentTotals()->getGrossTotal(),
                2, ".", ""
            )
        );

        $xml->endElement(); //DocumentTotals
        $xml->endElement(); //RegisterInvoice
        $xml->endElement(); //Body
    }

    /**
     *
     * @param \Rebelo\ATWs\Invoice\Invoice $invoice
     * @return \Rebelo\ATWs\Invoice\Response
     * @throws \Rebelo\ATWs\ATWsException
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
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsdl(): string
    {
        if (\file_exists(static::WSDL) === false) {
            throw new ATWsException("WSDL file not exist: " . static::WSDL);
        }
        return "file://" . static::WSDL;
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

    /**
     * Get the Webservice location
     * @return string
     * @since 1.0.0
     */
    public function getWsLocation(): string
    {
        return $this->isTest ?
            "https://servicos.portaldasfinancas.gov.pt:700/fews/faturas" :
            "https://servicos.portaldasfinancas.gov.pt:400/fews/faturas";
    }

}
