<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Payment Data
 * @since 2.0.0
 */
class PaymentData
{

    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader $paymentHeader   The payment header
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus $paymentStatus   The payment status
     * @param \Rebelo\Date\Date                                   $systemEntryDate Record recording date to the second.
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\Line[]        $lines           Payment lines
     * @param \Rebelo\ATWs\EFaturaMDVersion\DocumentTotals        $documentTotals  Payment totals
     * @param \Rebelo\ATWs\EFaturaMDVersion\WithholdingTax[]|null $withholdingTax  Withholding tax
     * @since 2.0.0
     */
    public function __construct(
        protected PaymentHeader  $paymentHeader,
        protected PaymentStatus  $paymentStatus,
        protected Date           $systemEntryDate,
        protected array          $lines,
        protected DocumentTotals $documentTotals,
        protected ?array         $withholdingTax
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->info(
            "SystemEntryDate set to " . $this->systemEntryDate->format(Pattern::DATE_T_TIME)
        );
    }

    /**
     * Record recording date to the second.
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getSystemEntryDate(): Date
    {
        return $this->systemEntryDate;
    }

    /**
     * The payment lines
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\Line[]
     * @since 2.0.0
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * The document totals
     * @return \Rebelo\ATWs\EFaturaMDVersion\DocumentTotals
     * @since 2.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        return $this->documentTotals;
    }

    /**
     * Get the actual invoice status
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus
     * @since 2.0.0
     */
    public function getPaymentStatus(): PaymentStatus
    {
        return $this->paymentStatus;
    }

    /**
     * The payment header
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader
     * @since 2.0.0
     */
    public function getPaymentHeader(): PaymentHeader
    {
        return $this->paymentHeader;
    }

    /**
     * Withholding tax
     * @return \Rebelo\ATWs\EFaturaMDVersion\WithholdingTax[]|null
     * @since 2.0.0
     */
    public function getWithholdingTax(): ?array
    {
        return $this->withholdingTax;
    }

}
