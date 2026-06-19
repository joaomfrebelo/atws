<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;

/**
 * @author João Rebelo
 * @since  2.0.0
 */
class ChangePaymentStatus
{
    /**
     * Change the payment document status
     * @param string                                              $taxRegistrationNumber Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader $paymentHeader
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus $newPaymentStatus
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null    $recordChannel
     * @since 2.0.0
     */
    public function __construct(
        protected string         $taxRegistrationNumber,
        protected PaymentHeader  $paymentHeader,
        protected PaymentStatus  $newPaymentStatus,
        protected ?RecordChannel $recordChannel
    )
    {
        AATWs::$logger?->debug(__METHOD__);
    }

    /**
     * Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     *
     * @return string
     * @since 2.0.0
     */
    public function getTaxRegistrationNumber(): string
    {
        return $this->taxRegistrationNumber;
    }

    /**
     * The invoice header
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader
     * @since 2.0.0
     */
    public function getPaymentHeader(): PaymentHeader
    {
        return $this->paymentHeader;
    }

    /**
     * The new invoice status
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus
     * @since 2.0.0
     */
    public function getNewPaymentStatus(): PaymentStatus
    {
        return $this->newPaymentStatus;
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null
     * @since 2.0.0
     */
    public function getRecordChannel(): ?RecordChannel
    {
        return $this->recordChannel;
    }

}
