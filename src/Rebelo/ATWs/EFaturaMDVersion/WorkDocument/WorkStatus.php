<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * @author João Rebelo
 * @since  2.0.0
 */
class WorkStatus
{
    /**
     * @param string            $workStatus     Document status. It can take on the following values: N – Normal; A – Canceled; F – Billed.
     * @param \Rebelo\Date\Date $workStatusDate Date when the document state was last saved.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $workStatus,
        protected Date   $workStatusDate
    )
    {
        AATWs::$logger?->debug(__METHOD__);

        $allowStatus = ['N', 'A', 'F'];
        if (\in_array($this->workStatus, $allowStatus) === false) {
            $msg = \sprintf(
                "WorkStatus must be one of '%s'", \join("', '", $allowStatus)
            );
            AATWs::$logger?->error($msg);
            throw new ATWsException($msg);
        }

        AATWs::$logger?->info("Work status set to " . $this->workStatus);
        AATWs::$logger?->info("Work status date set to " . $this->workStatusDate->format(Pattern::DATE_T_TIME));

    }

    /**
     * Work status
     * @return string
     * @since 2.0.0
     */
    public function getWorkStatus(): string
    {
        return $this->workStatus;
    }

    /**
     * Work this status date
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getWorkStatusDate(): Date
    {
        return $this->workStatusDate;
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
            "WorkStatus",
            null,
            $this->getWorkStatus()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "WorkStatusDate",
            null,
            $this->getWorkStatusDate()->format(Pattern::DATE_T_TIME)
        );
    }

}
