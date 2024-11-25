<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Tax;

/**
 * Payment line
 * @since 2.0.0
 */
class Line
{
    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * Receipt Lines by Fee (LineSummary)
     * Summary of receipt lines by tax rate and reason for exemption or non-payment.
     * There must be one, and only one line, for each tax (TaxType, TaxCountryRegion, TaxCode)
     * and reason for exemption or non-payment (TaxExemptionCode),
     * for each original document number (OriginatingOn) and for each credit line indicator or debit (DebitCreditIndicator).
     * If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\SourceDocumentID[] $sourceDocumentID     Reference to the source document (SourceDocumentID) If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     * @param float|null                                               $settlementAmount     Discounts granted upon payment of this document.
     * @param string                                                   $debitCreditIndicator Debit/Credit Indicator: D – Debit (if the value of the line, without tax, of the receipts is debit); C – Credit (if the line value, excluding tax, of the receipts is credit).
     * @param float                                                    $amount               Line value, excluding tax and any discounts, from the invoice receipt or amending document.
     * @param \Rebelo\ATWs\EFaturaMDVersion\Tax|null                   $tax                  Tax Rate (Tax)
     * @param string|null                                              $taxExemptionCode     Reason for VAT exemption: Mandatory field in the case of an exempt transmission or service provision or in which, justifiably, VAT is not paid [fields 1.6.9.6.4 – Tax Percentage (TaxPercentage) or 1.6.9.6.5 – Total tax amount (TotalTaxAmount) is equal to zero]; It must be filled in with the codes from the table “Codes of reasons for exemption or non-payment of VAT”, which appears in point 2.2.2.1 of this document.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected array   $sourceDocumentID,
        protected ?float  $settlementAmount,
        protected string  $debitCreditIndicator,
        protected float   $amount,
        protected ?Tax    $tax,
        protected ?string $taxExemptionCode
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        if (\count($this->sourceDocumentID) === 0) {
            $msg = "SourceDocumentID cannot be empty";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $allowCreDebIndicator = ["D", "C"];
        if (\in_array($this->debitCreditIndicator, $allowCreDebIndicator) === false) {
            $msg = \sprintf(
                "Debit Credit indicator must be on of '%s'",
                \join("', '", $allowCreDebIndicator)
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("DebitCreditIndicator set to " . $this->debitCreditIndicator);

        if ($this->settlementAmount !== null && $this->settlementAmount < 0.0) {
            $msg = "SettlementAmount cannot be less than zero";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("SettlementAmount set to " . ((string)($this->settlementAmount ?? "null")));

        if ($this->amount < 0.0) {
            $msg = "Amount cannot be less than zero";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Amount set to " . ((string)$this->amount));

        if ($this->taxExemptionCode !== null && \preg_match("/^(M[0-9]{2})$/", $this->taxExemptionCode) !== 1) {
            $msg = "Wrong tax exemption code format";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("TaxExemptionCode set to " . ($this->taxExemptionCode ?? "null"));
    }

    /**
     * Reference to the source document (SourceDocumentID)
     * If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     * @return array
     * @since 2.0.0
     */
    public function getSourceDocumentID(): array
    {
        return $this->sourceDocumentID;
    }

    /**
     * Discounts granted upon payment of this document.
     * @return float|null
     * @since 2.0.0
     */
    public function getSettlementAmount(): ?float
    {
        return $this->settlementAmount;
    }

    /**
     * Debit/Credit Indicator:
     * D – Debit (if the value of the line, without tax, of the receipts is debit);
     * C – Credit (if the line value, excluding tax, of the receipts is credit).
     * @return string
     * @since 2.0.0
     */
    public function getDebitCreditIndicator(): string
    {
        return $this->debitCreditIndicator;
    }

    /**
     * Line value, excluding tax and any discounts, from the invoice receipt or amending document.
     * @return float
     * @since 2.0.0
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Tax Rate (Tax)
     * @return \Rebelo\ATWs\EFaturaMDVersion\Tax|null
     * @since 2.0.0
     */
    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    /**
     * Reason for VAT exemption:
     * Mandatory field in the case of an exempt transmission or service provision or in which, justifiably,
     * VAT is not paid [fields 1.6.9.6.4 – Tax Percentage (TaxPercentage)
     * or 1.6.9.6.5 – Total tax amount (TotalTaxAmount) is equal to zero];
     * It must be filled in with the codes from the table “Codes of reasons for exemption or non-payment of VAT”,
     * which appears in point 2.2.2.1 of this document.
     * @return string|null
     * @since 2.0.0
     */
    public function getTaxExemptionCode(): ?string
    {
        return $this->taxExemptionCode;
    }

    /**
     * Build the xml
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

        foreach ($this->getSourceDocumentID() as $sourceDocumentID) {
            $sourceDocumentID->buildXml($xml);
        }

        if ($this->settlementAmount !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "SettlementAmount",
                null,
                \number_format(
                    $this->settlementAmount,
                    AATWs::DECIMALS, ".", ""
                )
            );
        }

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "DebitCreditIndicator",
            null,
            $this->debitCreditIndicator
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "Amount",
            null,
            \number_format(
                $this->amount,
                AATWs::DECIMALS, ".", ""
            )
        );

        $this->tax?->buildXml($xml);

        if ($this->taxExemptionCode !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "TaxExemptionCode",
                null,
                $this->taxExemptionCode
            );
        }

        $xml->endElement();
    }
}
