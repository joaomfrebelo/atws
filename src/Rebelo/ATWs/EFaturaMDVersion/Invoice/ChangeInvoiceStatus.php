<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;

/**
 * Change Invoice Status
 * @author João Rebelo
 * @since  2.0.0
 */
class ChangeInvoiceStatus
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * Change the invoice status
     * @param string                                              $taxRegistrationNumber Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceHeader $invoiceHeader
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceStatus $newInvoiceStatus
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null    $recordChannel
     * @since 2.0.0
     */
    public function __construct(
        protected string         $taxRegistrationNumber,
        protected InvoiceHeader  $invoiceHeader,
        protected InvoiceStatus  $newInvoiceStatus,
        protected ?RecordChannel $recordChannel
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
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
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceHeader
     * @since 2.0.0
     */
    public function getInvoiceHeader(): InvoiceHeader
    {
        return $this->invoiceHeader;
    }

    /**
     * The new invoice status
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceStatus
     * @since 2.0.0
     */
    public function getNewInvoiceStatus(): InvoiceStatus
    {
        return $this->newInvoiceStatus;
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
