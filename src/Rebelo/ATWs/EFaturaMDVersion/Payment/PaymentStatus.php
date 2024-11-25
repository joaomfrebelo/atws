<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * @author João Rebelo
 * @since  2.0.0
 */
class PaymentStatus
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string            $paymentStatus     Document status. It can take on the following values: N – Normal; A – Canceled;
     * @param \Rebelo\Date\Date $paymentStatusDate Date when the document state was last saved.
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $paymentStatus,
        protected Date   $paymentStatusDate
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $allowStatus = ['N', 'A'];
        if (\in_array($this->paymentStatus, $allowStatus) === false) {
            $msg = \sprintf(
                "PaymentStatus must be one of '%s'", \join("', '", $allowStatus)
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Payment status set to " . $this->paymentStatus);
        $this->log->info("Payment status date set to " . $this->paymentStatusDate->format(Pattern::DATE_T_TIME));
    }

    /**
     * Payment status
     * @return string
     * @since 2.0.0
     */
    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    /**
     * Payment this status date
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getPaymentStatusDate(): Date
    {
        return $this->paymentStatusDate;
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
        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "PaymentStatus",
            null,
            $this->getPaymentStatus()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "PaymentStatusDate",
            null,
            $this->getPaymentStatusDate()->format(Pattern::DATE_T_TIME)
        );
    }
}
