<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use Rebelo\ATWs\ATWsException;

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
     * Document Lines by Rate (Line)<br>
     * Summary of invoice lines by tax rate,
     * and reason for exemption or non-settlement.
     * There must be one and only one line for each
     * fee (TaxType, TaxCountryRegion, TaxCode) and reason
     * for exemption or non-settlement (TaxExemptionReason)
     * @param \Rebelo\ATWs\Invoice\OrderReference[]|null $orderReference
     * @param float|null                                 $debitAmount
     * @param float|null                                 $creditAmount
     * @param string                                     $taxType
     * @param string                                     $taxCountryRegion
     * @param float                                      $taxPercentage
     * @param string|null                                $taxExemptionReason
     * @throws ATWsException
     * @since 1.0.0
     */
    public function __construct(
        protected ?array  $orderReference,
        protected ?float  $debitAmount,
        protected ?float  $creditAmount,
        protected string  $taxType,
        protected string  $taxCountryRegion,
        protected float   $taxPercentage,
        protected ?string $taxExemptionReason
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        if ($debitAmount === null && $creditAmount === null) {
            $msg = "Debit an Credit amount can not be null at same time";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($taxExemptionReason !== null && $taxPercentage !== 0.0) {
            $msg = "Tax exemption reason only can be set if tax percentage equal to 0.0";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($taxExemptionReason === null && $taxPercentage === 0.0) {
            $msg = "Tax exemption reason must be set if tax percentage is equal to 0.0";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->debug("Debit amount set to :" . ($debitAmount ?? "null"));
        $this->log->debug("Credit amount set to :" . ($creditAmount ?? "null"));
        $this->log->debug("TaxType set to:" . $taxType);
        $this->log->debug("TaxCountryRegion set to:" . $taxCountryRegion);
        $this->log->debug("TaxPercentage amount set to :" . $taxPercentage);
        $this->log->debug("TaxExemptionReason set to :" . ($taxExemptionReason ?? "null"));
    }

    /**
     * Get the OrderReferences stack
     * @return \Rebelo\ATWs\Invoice\OrderReference[]|null
     * @since 1.0.0
     */
    public function getOrderReference(): ?array
    {
        return $this->orderReference;
    }

    /**
     * Get the debit amount<br>
     * Sum of the value of the lines, excluding tax,
     * less line and header discounts, where the rate and/or reason
     * for exemption described in “1.7.3 - Rate (Tax)” was applied.<br>
     * Mandatory for Credit Notes. In the remaining types of document,
     * only the field “1.7.2 - Amount on Credit (CreditAmount)”
     * must be filled out.
     * @return float|null
     * @since 1.0.0
     */
    public
    function getDebitAmount(): ?float
    {
        return $this->debitAmount;
    }

    /**
     * Get the Credit amount<br>
     * Sum of the value of the lines, excluding tax,
     * less line and header discounts, where the rate and/or reason
     * for exemption described in “1.7.3 - Rate (Tax)” was applied.<br>
     * Mandatory for Invoices, Simplified Invoices and Debit Notes.
     * In the Credit Notes, only the field “1.7.1 - Debit Amount (DebitAmount)”
     * must be filled out.
     * @return float|null
     * @since 1.0.0
     */
    public
    function getCreditAmount(): ?float
    {
        return $this->creditAmount;
    }

    /**
     * Get the tax type<br>
     * Rate type. It must assume value:<br>
     * VAT - value added tax.
     * @return string
     * @since 1.0.0
     */
    public
    function getTaxType(): string
    {
        return $this->taxType;
    }

    /**
     * Get the tax country region<br>
     * It must be filled in with:<br>
     * PT - fiscal space in mainland Portugal;<br>
     * PT-AC - fiscal space of the Autonomous Region of the Azores;<br>
     * PT-MA - fiscal space of the Autonomous Region of Madeira.
     * @return string
     * @since 1.0.0
     */
    public
    function getTaxCountryRegion(): string
    {
        return $this->taxCountryRegion;
    }

    /**
     * Get the tax percentage<br>
     * VAT rate applied. It must be filled in with the percentage
     * rate corresponding to the tax applicable to the
     * field “1.7.1 - Debit Amount (DebitAmount)”
     * or to the field “1.7.2 - Amount to Credit (CreditAmount)”.
     * It must be filled in with 0 (zero) in the case of an exempt
     * transmission or provision of services or in which, justifiably,
     * there is no VAT settlement.
     * @return float
     * @since 1.0.0
     */
    public
    function getTaxPercentage(): float
    {
        return $this->taxPercentage;
    }

    /**
     * Get the Tax exception reason when the tax percentage e zero<br>
     * VAT exemption reason
     * Mandatory field when it comes to an exempt transmission or
     * service provision or in which, justifiably, there is no VAT settlement.
     * It must be filled in with the codes from the table of
     * Reasons for Exemption or non-payment of VAT, which appears
     * in point 4.2.1 of this document.
     * @return string|null
     * @since 1.0.0
     */
    public
    function getTaxExemptionReason(): ?string
    {
        return $this->taxExemptionReason;
    }

}
