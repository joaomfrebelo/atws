<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;

/**
 * Delete work document
 * @since 2.0.0
 */
class DeleteWorkDocument
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * Delete work document
     * @param string                                                       $taxRegistrationNumber Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkHeader[]|null $documentList          List of business work documents This field is mutually exclusive with the field “Date Range (dateRange)”. Only one of the fields must be filled in.
     * @param \Rebelo\ATWs\EFaturaMDVersion\DateRange|null                 $dateRange             This field is mutually exclusive with the field “List of commercial documents (documentsList)”. Only one of the fields must be filled in.
     * @param string                                                       $reason                The reason that led to the deletion of commercial documents must be indicated.
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null             $recordChannel
     * @throws \Rebelo\ATWs\ATWsException
     * @since  2.0.0
     */
    public function __construct(
        protected string         $taxRegistrationNumber,
        protected array|null     $documentList,
        protected ?DateRange     $dateRange,
        protected string         $reason,
        protected ?RecordChannel $recordChannel
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $docListIsEmpty = $this->documentList === null || \count($this->documentList) === 0;

        if ($docListIsEmpty === true && $this->dateRange === null) {
            $msg = "One of DocumentList and DateRange cannot be null or empty";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($docListIsEmpty === false && $this->dateRange !== null) {
            $msg = "One of DocumentList and DateRange must be null or empty";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $reasonLength = \strlen($this->reason);
        if ($reasonLength < 10 || $reasonLength > 500) {
            $msg = "Reason length must be between 10 and 500";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

    }

    /**
     * Issuer TIN Portuguese Tax Identification Number (without any country prefix).
     *
     * @return string
     * @since  2.0.0
     */
    public function getTaxRegistrationNumber(): string
    {
        return $this->taxRegistrationNumber;
    }

    /**
     * List of business documents (documentsList)
     * This field is mutually exclusive with the field “Date Range (dateRange)”.
     * Only one of the fields must be filled in.
     * @return array|null
     * @since  2.0.0
     */
    public function getDocumentList(): ?array
    {
        return $this->documentList;
    }

    /**
     * This field is mutually exclusive with the field “List of commercial documents (documentsList)”.
     * Only one of the fields must be filled in.
     * @return \Rebelo\ATWs\EFaturaMDVersion\DateRange|null
     * @since  2.0.0
     */
    public function getDateRange(): ?DateRange
    {
        return $this->dateRange;
    }

    /**
     * @return string
     * @since  2.0.0
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * The reason that led to the deletion of commercial documents must be indicated.
     * @return \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null
     * @since  2.0.0
     */
    public function getRecordChannel(): ?RecordChannel
    {
        return $this->recordChannel;
    }

}
