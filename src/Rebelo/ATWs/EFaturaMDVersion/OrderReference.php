<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Order Reference
 * @author João Rebelo
 * @since  1.0.0
 */
class OrderReference
{

    /**
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * Reference to the source document (OrderReferences)
     * @param string            $originatingON The type, series and number of the document shall be indicated. In case the document is included in SAF-T (PT), the numbering structure of the field of origin shall be used.
     * @param \Rebelo\Date\Date $orderDate     The document order reference date
     * @since 1.0.0
     */
    public function __construct(
        protected string $originatingON,
        protected Date   $orderDate
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->info("originatingON set to: " . $this->originatingON);
        $this->log->info("orderDate set to: " . $this->orderDate->format(Pattern::SQL_DATE));
    }

    /**
     * The type, series and number of the document shall be indicated.
     * In case the document is included in SAF-T (PT),
     * the numbering structure of the field of origin shall be used.
     * @return string
     * @since 1.0.0
     */
    public function getOriginatingON(): string
    {
        return $this->originatingON;
    }

    /**
     * The document order reference date
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getOrderDate(): Date
    {
        return $this->orderDate;
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildlXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "OrderReferences",
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
            "OrderDate",
            null,
            $this->getOrderDate()->format(Pattern::SQL_DATE)
        );

        $xml->endElement();
    }


}
