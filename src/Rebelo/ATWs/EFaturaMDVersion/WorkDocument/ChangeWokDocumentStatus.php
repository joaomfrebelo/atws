<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;

/**
 * Change work document status
 * @since 2.0.0
 */
class ChangeWokDocumentStatus
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * Change the work document status
     * @param string                                                $taxRegistrationNumber Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkHeader $workHeader
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkStatus $newWorkStatus
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null      $recordChannel
     * @since 2.0.0
     */
    public function __construct(
        protected string         $taxRegistrationNumber,
        protected WorkHeader     $workHeader,
        protected WorkStatus     $newWorkStatus,
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
     * @return \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkHeader
     * @since 2.0.0
     */
    public function getWorkHeader(): WorkHeader
    {
        return $this->workHeader;
    }

    /**
     * The new invoice status
     * @return \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkStatus
     * @since 2.0.0
     */
    public function getNewWorkStatus(): WorkStatus
    {
        return $this->newWorkStatus;
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
