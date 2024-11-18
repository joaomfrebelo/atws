<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * @author João Rebelo
 * @since  2.0.0
 */
class InvoiceHeader
{
    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string $invoiceNo            Unique identification of the sales document. It must be identical to that contained in the SAF-T (PT) file
     * @param string $atcud                This field must contain the Document's Unique Code. The field must be filled in with «0» (zero) until its regulation.
     * @param Date   $invoiceDate          Document issue date
     * @param string $invoiceType          Document Type. FT, FS, NC, ND, FR, RP, RE, CS, LD, RA
     * @param bool   $selfBillingIndicator It must be filled in with “1” if it respects self-billing and with “0” (zero) otherwise
     * @param string $customerTaxID        Purchaser's TIN
     * @param string $customerTaxIDCountry Purchaser's TIN country: Two-character code (alpha2) according to ISO 3166-1.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $invoiceNo,
        protected string $atcud,
        protected Date   $invoiceDate,
        protected string $invoiceType,
        protected bool   $selfBillingIndicator,
        protected string $customerTaxID,
        protected string $customerTaxIDCountry,
    )
    {

        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("invoiceNo set to: " . $invoiceNo);
        $this->log->info(
            "InvoiceDate set to: " . $invoiceDate->format(Pattern::SQL_DATE)
        );

        $allowTypes = ['FT', 'FR', 'FS', 'NC', 'ND', 'RP', 'RE', 'CS', 'LD', 'RA'];

        if (\in_array($this->invoiceType, $allowTypes) === false) {
            $msg = \sprintf(
                "Invoice type only can be '%s'",
                \join("', '", $allowTypes),
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("InvoiceType set to: " . $invoiceType);
        $this->log->info(
            \sprintf(
                "Self billing Indicator set to %s",
                $this->selfBillingIndicator ? "true" : "false"
            )
        );

        $this->log->info("CustomerTaxID set to: " . $customerTaxID);

        if (\preg_match("/^([A-Z]{2}|Desconhecido)$/", $this->customerTaxIDCountry) !== 1) {
            $msg = "Wrong format for CustomerTaxIdCountry";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("CustomerTaxIDCountry set to: " . $this->customerTaxIDCountry);

    }

    /**
     * Unique identification of the sales document<br>
     * It must be identical to that contained in the SAF-T (PT) file,
     * when generated from the billing system that issued this document;<br>
     * You must respect the format defined in the legislation regarding
     * the SAF-T (PT) file, in force when communicating the elements of
     * the invoices:<br>
     * It consists of the document's internal code, followed by a space,
     * followed by the document's series identifier, followed by a slash (/),
     * and a document's sequential number within the series;
     * There can be no records with the same identification;
     * @return string
     * @since 2.0.0
     */
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * Document issue date
     * @return Date
     * @since 2.0.0
     */
    public function getInvoiceDate(): Date
    {
        return $this->invoiceDate;
    }

    /**
     * Document Type. You can assume the following values:<br>
     * FT, FR, FS, NC, ND
     * @return string
     * @since 2.0.0
     */
    public function getInvoiceType(): string
    {
        return $this->invoiceType;
    }

    /**
     * It must be filled in with “1” if it respects self-billing
     * and with “0” (zero) otherwise
     * @return bool
     * @since 2.0.0
     */
    public function getSelfBillingIndicator(): bool
    {
        return $this->selfBillingIndicator;
    }

    /**
     * NIF of the national/international acquirer.
     * National TIN:
     * Portuguese Tax Identification Number (without any country prefix).
     * International TIN:
     * NIF or equivalent in the respective country that has been collected in the issuer's invoicing system.
     * When it has not been collected in the issuer's invoicing system, it must be filled in with 999999990.
     * @return string
     * @since 2.0.0
     */
    public function getCustomerTaxID(): string
    {
        return $this->customerTaxID;
    }

    /**
     * Country of the acquirer's TIN:
     * Two-character code (alpha2) according to ISO 3166-1.
     * @return string
     * @since 2.0.0
     */
    public function getCustomerTaxIDCountry(): string
    {
        return $this->customerTaxIDCountry;
    }

    /**
     * This field must contain the Document's Unique Code.
     * The field must be filled in with «0» (zero) until its regulation.
     * @return string
     * @since 2.0.0
     */
    public function getAtcud(): string
    {
        return $this->atcud;
    }

    /**
     * Build the xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceNo",
            null,
            $this->getInvoiceNo()
        );

        $xml->writeElementNs(
            Aws::NS_AT_WS_BODY,
            "ATCUD",
            null,
            $this->getAtcud()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceDate",
            null,
            $this->getInvoiceDate()->format(Pattern::SQL_DATE)
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceType",
            null,
            $this->getInvoiceType()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "SelfBillingIndicator",
            null,
            $this->getSelfBillingIndicator() ? "1" : "0"
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "CustomerTaxID",
            null,
            $this->getCustomerTaxID()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "CustomerTaxIDCountry",
            null,
            $this->getCustomerTaxIDCountry()
        );
    }

}
