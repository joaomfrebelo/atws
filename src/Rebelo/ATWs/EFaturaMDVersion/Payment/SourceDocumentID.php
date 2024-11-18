<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * SourceDocumentID
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class SourceDocumentID
{
    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * Reference to the source document (SourceDocumentID)
     * If there is a need to make more than one reference, this structure can be generated as many times as necessary.
     *
     * @param string            $originatingON The type, series and number of the document supporting its issuance must be indicated.
     * @param \Rebelo\Date\Date $invoiceDate   The date of the invoice or amending document to which the payment refers must be indicated.
     * @since 2.0.0
     */
    public function __construct(
        protected string $originatingON,
        protected Date   $invoiceDate
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("OriginatingON set to " . $this->originatingON);
        $this->log->info("InvoiceDate set to " . $this->invoiceDate->format(Pattern::SQL_DATE));
    }

    /**
     * The type, series and number of the document supporting its issuance must be indicated.
     * @return string
     * @since 2.0.0
     */
    public function getOriginatingON(): string
    {
        return $this->originatingON;
    }

    /**
     * The date of the invoice or amending document to which the payment refers must be indicated.
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getInvoiceDate(): Date
    {
        return $this->invoiceDate;
    }


    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "SourceDocumentID",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "OriginatingON",
            null,
            $this->getOriginatingON()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "InvoiceDate",
            null,
            $this->getInvoiceDate()->format(Pattern::SQL_DATE)
        );

        $xml->endElement();
    }

}
