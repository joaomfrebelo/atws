<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\EFaturaMDVersion\DocBase;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;

/**
 * @author João Rebelo
 * @since  2.0.0
 */
class Payment
{
    use DocBase;

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string                                            $taxRegistrationNumber     Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param string                                            $taxEntity                 The communication must specify which establishment it relates to, if applicable. Otherwise, it must be filled in with the “Global” specification. If it comes from an accounting program, or from an integrated accounting and invoicing program, this field must be filled in with the specification “Sede”.
     * @param int                                               $softwareCertificateNumber Certificate number assigned to the program by the AT, in accordance with Ordinance No. 363/2010, of 23 June. If not applicable, it must be filled in with “0” (zero).
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentData $paymentData               The payment data details
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null  $recordChannel
     */
    public function __construct(
        protected string         $taxRegistrationNumber,
        protected string         $taxEntity,
        protected int            $softwareCertificateNumber,
        protected PaymentData    $paymentData,
        protected ?RecordChannel $recordChannel
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->info("TaxRegistrationNumber set to: " . $taxRegistrationNumber);
        $this->log->info("TaxEntity set to: " . $this->taxEntity);
        $this->log->info("SoftwareCertificateNumber set to: " . $this->softwareCertificateNumber);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentData
     * @since 2.0.0
     */
    public function getPaymentData(): PaymentData
    {
        return $this->paymentData;
    }


}
