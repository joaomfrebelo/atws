<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * Invoice to submit
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Invoice
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     *
     * @param string                            $taxRegistrationNumber Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param string                            $invoiceNo             Unique identification of the sales document. It must be identical to that contained in the SAF-T (PT) file
     * @param Date                              $invoiceDate           Document issue date
     * @param string                            $invoiceType           Document Type. FT, FR, FS, NC, ND
     * @param string                            $invoiceStatus         Document status. N - Normal; A - Canceled
     * @param string|InternationalCustomerTaxID $customerTaxID         National purchaser's TIN or Foreign Buyer TIN
     * @param Line[]                            $lines                 Document Lines by Rate (Line)
     * @param DocumentTotals                    $documentTotals        The Document Totals
     * @throws ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function __construct(
        protected string                            $taxRegistrationNumber,
        protected string                            $invoiceNo,
        protected Date                              $invoiceDate,
        protected string                            $invoiceType,
        protected string                            $invoiceStatus,
        protected string|InternationalCustomerTaxID $customerTaxID,
        protected array                             $lines,
        protected DocumentTotals                    $documentTotals
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        if (\in_array($invoiceType, ['FT', 'FR', 'FS', 'NC', 'ND']) === false) {
            $msg = "Invoice type only can be 'FT', 'FR', 'FS', 'NC', 'ND'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (\in_array($invoiceStatus, ['A', 'N']) === false) {
            $msg = "Invoice status only can be 'A', 'N'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->debug("TaxRegistrationNumber set to: " . $taxRegistrationNumber);
        $this->log->debug("invoiceNo set to: " . $invoiceNo);
        $this->log->debug(
            "InvoiceDate set to: " . $invoiceDate->format(
                Date::SQL_DATE
            )
        );
        $this->log->debug("InvoiceType set to: " . $invoiceType);
        $this->log->debug("InvoiceStatus set to: " . $invoiceStatus);
        if (\is_string($customerTaxID)) {
            $this->log->debug("CustomerID set to: " . $customerTaxID);
        }
    }

    /**
     * Issuer TIN<br>
     * Portuguese Tax Identification Number (without any country prefix).
     * @return string
     * @since 1.0.0
     */
    public function getTaxRegistrationNumber(): string
    {
        return $this->taxRegistrationNumber;
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
     * @since 1.0.0
     */
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * Document issue date
     * @return Date
     * @since 1.0.0
     */
    public function getInvoiceDate(): Date
    {
        return $this->invoiceDate;
    }

    /**
     * Document Type. You can assume the following values:<br>
     * FT, FR, FS, NC, ND
     * @return string
     * @since 1.0.0
     */
    public function getInvoiceType(): string
    {
        return $this->invoiceType;
    }

    /**
     * Document status. You can assume the following values:<br>
     * N - Normal;
     * A - Canceled;
     * @return string
     * @since 1.0.0
     */
    public function getInvoiceStatus(): string
    {
        return $this->invoiceStatus;
    }

    /**
     * National purchaser's TIN<br>
     * Portuguese Tax Identification Number (without any country prefix);<br>
     * It must be completed whenever it is a national purchaser;<br>
     * When it has not been collected in the issuer's billing system,
     * it must be filled in with 999999990<br>
     * This field is mutually exclusive with the
     * field “1.6 - Foreign Buyer TIN (InternationalCustomerTaxID)”.
     * One and only one of the fields must be completed.
     * @return string|InternationalCustomerTaxID
     * @since 1.0.0
     */
    public function getCustomerID(): string|InternationalCustomerTaxID
    {
        return $this->customerTaxID;
    }

    /**
     * Document Lines by Rate (Line)
     * @return Line[]
     * @since 1.0.0
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * The Document Totals
     * @return \Rebelo\ATWs\Invoice\DocumentTotals
     * @since 1.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        return $this->documentTotals;
    }

}
