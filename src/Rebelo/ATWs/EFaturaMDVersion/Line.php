<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Line
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Line
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * Document Lines by Rate (LineSummary)
     * Summary of invoice lines by tax rate and reason for exemption or non-payment:
     * There must be one, and only one line, for each tax (TaxType, TaxCountryRegion, TaxCode), for each exemption or non-payment reason (TaxExemptionCode), for each date of shipment of goods or provision of service (TaxPointDate) for each rectified document (Reference), for each original document number (OriginatingOn) and for credit or debit line indicator (DebitCreditIndicator).
     * If necessary, this structure can be generated as many times as necessary.
     * @param \Rebelo\ATWs\EFaturaMDVersion\OrderReference[]|null $orderReference       Reference to the source document (OrderReferences) If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     * @param \Rebelo\Date\Date                                   $taxPointDate         Date of dispatch of the goods or the provision of services. It must be filled in with the date of the associated packing slip, if any. If there is more than one delivery note, the date of the oldest must be indicated.
     * @param string[]|null                                       $reference            Reference to the invoice or simplified invoice, through its unique identification, in the systems where it exists. The numbering structure of the source field must be used. If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     * @param string                                              $debitCreditIndicator Date of dispatch of the goods or the provision of services. It must be filled in with the date of the associated packing slip, if any. Debit/Credit Indicator: D – Debit (if the value of the line, without tax, of the documents to be debited to the respective account); C – Credit (if the value of the line, without tax, of the documents to be credited to the respective account).
     * @param float|null                                          $totalTaxBase         Taxable total that does not compete for the Document Total without tax (NetTotal). This value is the basis for calculating the line taxes. Calculation of the Taxable Total: Quantity * TaxBase.    Note: fields according to SAF-T(PT). This field is mutually exclusive with the field “1.6.14.6 – Amount of the line without tax, of documents (Amount)”. Only one of the fields must be filled in.
     * @param float|null                                          $amount               Value of the line, excluding tax and any discounts, of the documents to be debited/credited in the respective account. This field is mutually exclusive with the field “1.6.14.5 – Total taxable (TotalTaxBase)”. Only one of the fields must be filled in.
     * @param Tax                                                 $tax                  Tax Rate (Tax)
     * @param string|null                                         $taxExemptionCode     Reason for VAT exemption: Mandatory field when it is an exempt transmission or service provision or in which, justifiably, there is no VAT settlement (fields 1.6.14.7.4 – Tax Percentage) or 1.6.14.7.5 – Total tax amount (TotalTaxAmount) is equal to zero); It must be filled in with the codes from the table “Codes of reasons for exemption or non-payment of VAT”, which appears in point 0 of this document.
     * @throws ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected ?array     $orderReference,
        protected Date       $taxPointDate,
        protected array|null $reference,
        protected string     $debitCreditIndicator,
        protected ?float     $totalTaxBase,
        protected ?float     $amount,
        protected Tax        $tax,
        protected ?string    $taxExemptionCode
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("Tax point date set to " . $this->taxPointDate->format(Pattern::SQL_DATE));

        $allowCreDebIndicator = ["D", "C"];
        if (\in_array($this->debitCreditIndicator, $allowCreDebIndicator) === false) {
            $msg = \sprintf(
                "Debit Credit indicator must be on of '%s'",
                \join("', '", $allowCreDebIndicator)
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($this->totalTaxBase !== null && $this->amount !== null) {
            $msg = "TotalTaxBase and Amount cannot be both set at same time";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($this->totalTaxBase === null && $this->amount === null) {
            $msg = "One of TotalTaxBase or Amount must be set";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("TotalTaxBase set to :" . ($this->totalTaxBase ?? "null"));
        $this->log->info("Amount set to :" . ($this->amount ?? "null"));

        if ($this->taxExemptionCode === null) {
            if ($this->tax->getTaxPercentage() === 0.0 || $this->tax->getTaxCode() === "ISE" || $this->tax->getTotalTaxAmount() === 0.0) {
                $msg = "Tax exemption TaxExemptionCode must be set";
                $this->log->error($msg);
                throw new ATWsException($msg);
            }
        } else {

            if (\preg_match("/^(M[0-9]{2})$/", $this->taxExemptionCode) !== 1) {
                $msg = "Wrong tax exemption code format";
                $this->log->error($msg);
                throw new ATWsException($msg);
            }

            if (($this->tax->getTaxPercentage() ?? 0.0) > 0.0 || ($this->tax->getTotalTaxAmount() ?? 0.0) > 0.0) {
                $msg = "Tax exemption TaxExemptionCode cannot be set";
                $this->log->error($msg);
                throw new ATWsException($msg);
            }
        }

        $this->log->info("TaxExemptionCode set to :" . ($taxExemptionCode ?? "null"));
    }

    /**
     * Get the OrderReferences stack
     * @return \Rebelo\ATWs\EFaturaMDVersion\OrderReference[]|null
     * @since 1.0.0
     */
    public function getOrderReference(): ?array
    {
        return $this->orderReference;
    }

    /**
     * Date of dispatch of the goods or the provision of services.
     * It must be filled in with the date of the associated packing slip, if any.
     * If there is more than one delivery note, the date of the oldest must be indicated.
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getTaxPointDate(): Date
    {
        return $this->taxPointDate;
    }

    /**
     * Reference to the invoice or simplified invoice, through its unique identification,
     * in the systems where it exists.
     * The numbering structure of the source field must be used.
     * If there is a need to make more than one reference,
     * this structure can be generated as many times as necessary.
     * @return string[]|null
     * @since 2.0.0
     */
    public function getReference(): ?array
    {
        return $this->reference;
    }

    /**
     * Date of dispatch of the goods or the provision of services.
     * It must be filled in with the date of the associated packing slip, if any.
     * Debit/Credit Indicator:
     * D – Debit (if the value of the line, without tax, of the documents to be debited to the respective account);
     * C – Credit (if the value of the line, without tax, of the documents to be credited to the respective account).
     * @return string
     * @since 2.0.0
     */
    public
    function getDebitCreditIndicator(): string
    {
        return $this->debitCreditIndicator;
    }

    /**
     * Taxable total that does not compete for the Document Total without tax (NetTotal).
     * This value is the basis for calculating the line taxes.
     * Calculation of the Taxable Total:
     *  Quantity * TaxBase.
     * Note: fields according to SAF-T(PT)
     * This field is mutually exclusive with the field “1.6.14.6 – Amount of the line without tax, of documents (Amount)”. Only one of the fields must be filled in.
     * @return float|null
     * @since 1.0.0
     */
    public function getTotalTaxBase(): ?float
    {
        return $this->totalTaxBase;
    }

    /**
     * Value of the line, excluding tax and any discounts,
     * of the documents to be debited/credited in the respective account.
     * This field is mutually exclusive with the field “1.6.14.5 – Total taxable (TotalTaxBase)”. Only one of the fields must be filled in.
     * @return float|null
     * @since 2.0.0
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Tax Rate (Tax)
     * @return \Rebelo\ATWs\EFaturaMDVersion\Tax
     * @since 2.0.0
     */
    public
    function getTax(): Tax
    {
        return $this->tax;
    }

    /**
     * Reason for VAT exemption:
     * Mandatory field when it is an exempt transmission or service provision or in which, justifiably,
     * there is no VAT settlement (fields 1.6.14.7.4 – Tax Percentage) or 1.6.14.7.5 – Total tax amount (TotalTaxAmount) is equal to zero);
     * It must be filled in with the codes from the table “Codes of reasons for exemption or non-payment of VAT”, which appears in point 0 of this document.
     * @return string|null
     * @since 1.0.0
     */
    public
    function getTaxExemptionCode(): ?string
    {
        return $this->taxExemptionCode;
    }

    /**
     * Build xml
     *
     * @param \XMLWriter $xml
     *
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "LineSummary",
            null
        );

        $orderReferenceStack = $this->getOrderReference() ?? [];

        if (\count($orderReferenceStack) > 0) {

            foreach ($orderReferenceStack as $orderReference) {
                $orderReference->buildlXml($xml);
            }

        }

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxPointDate",
            null,
            $this->getTaxPointDate()->format(Pattern::SQL_DATE)
        );

        foreach (($this->getReference() ?? []) as $reference) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "Reference",
                null,
                $reference
            );
        }

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "DebitCreditIndicator",
            null,
            $this->getDebitCreditIndicator()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            $this->getAmount() === null ? "TotalTaxBase" : "Amount",
            null,
            \number_format(
                $this->getAmount() ?? $this->getTotalTaxBase(),
                AATWs::DECIMALS, ".", ""
            )
        );

        $this->getTax()->buildXml($xml);

        if ($this->getTaxExemptionCode() !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "TaxExemptionCode",
                null,
                $this->getTaxExemptionCode()
            );
        }

        $xml->endElement(); //Line
    }

}
