<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * Date range
 * @author João Rebelo
 * @since  2.0.0
 */
class DateRange
{

    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param \Rebelo\Date\Date $startDate
     * @param \Rebelo\Date\Date $endDate
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected Date $startDate,
        protected Date $endDate
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        if ($this->startDate->isLater($this->endDate)) {
            $msg = "Start date cannot be after end date";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

    }

    /**
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getStartDate(): Date
    {
        return $this->startDate;
    }

    /**
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getEndDate(): Date
    {
        return $this->endDate;
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "dateRange",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "StartDate",
            null,
            $this->startDate->format(Date::SQL_DATE)
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "EndDate",
            null,
            $this->endDate->format(Date::SQL_DATE)
        );

        $xml->endElement();
    }

}
