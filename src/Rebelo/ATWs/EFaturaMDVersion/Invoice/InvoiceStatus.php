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
class InvoiceStatus
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string            $invoiceStatus     Document status. It can take on the following values: N – Normal; A – Canceled; F – Billed;S – Self-billed.
     * @param \Rebelo\Date\Date $invoiceStatusDate Date when the document state was last saved.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $invoiceStatus,
        protected Date   $invoiceStatusDate
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $allowStatus = ['N', 'A', 'F', 'S'];
        if (\in_array($this->invoiceStatus, $allowStatus) === false) {
            $msg = \sprintf(
                "InvoiceStatus must be one of '%s'", \join("', '", $allowStatus)
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Invoice status set to " . $this->invoiceStatus);
        $this->log->info(
            "Invoice status date set to " . $this->invoiceStatusDate->format(Pattern::DATE_T_TIME)
        );

    }

    /**
     * Invoice status
     * @return string
     * @since 2.0.0
     */
    public function getInvoiceStatus(): string
    {
        return $this->invoiceStatus;
    }

    /**
     * Invoice this status date
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getInvoiceStatusDate(): Date
    {
        return $this->invoiceStatusDate;
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceStatus",
            null,
            $this->getInvoiceStatus()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceStatusDate",
            null,
            $this->getInvoiceStatusDate()->format(Pattern::DATE_T_TIME)
        );
    }

}
