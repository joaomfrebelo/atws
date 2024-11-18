<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * This functionality aims to allow the communication of the Series to the AT,
 * through their registration, so that a unique validation code for the Series is assigned.
 * @since 1.0.0
 */
class SeriesRegister extends ASeries
{

    /**
     * This functionality aims to allow the communication of the Series to the AT,
     * through their registration, so that a unique validation code for the Series is assigned.
     *
     * @param string                                    $series                      Indicate the identifier of the Series you want to communicate to AT.
     * @param \Rebelo\ATWs\Series\SeriesTypeCode        $seriesTypeCode              Indicate the type of Series you want to communicate to AT
     * @param \Rebelo\ATWs\Series\DocumentTypeCode      $documentTypeCode            Indicate the type of document to which the Series belongs to communicate to AT.
     * @param int                                       $seriesInitialSequenceNumber Indicate the beginning of the document's sequence numbering in the Series.
     * @param \Rebelo\Date\Date                         $expectedInitialDateUse      Indicate the date from which the Series is expected to be used
     * @param int                                       $softwareCertificate         Indicate the invoicing program certificate number assigned by AT. If not applicable, it must be filled in with “0” (zero).
     * @param \Rebelo\ATWs\Series\ProcessingMediumCodes $processingMediumCode        Code of means of processing the documents to be issued.
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function __construct(
        private string                $series,
        private SeriesTypeCode        $seriesTypeCode,
        private DocumentTypeCode      $documentTypeCode,
        private int                   $seriesInitialSequenceNumber,
        private Date                  $expectedInitialDateUse,
        private int                   $softwareCertificate,
        private ProcessingMediumCodes $processingMediumCode
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("SeriesTypeCode set to: " . $this->seriesTypeCode->value);
        $this->log->debug("DocumentTypeCode set to: " . $this->documentTypeCode->value);
        $this->log->debug("SeriesInitialSequenceNumber set to: " . $this->seriesInitialSequenceNumber);
        $this->log->debug("ExpectedInitialDateUse set to: " . $this->expectedInitialDateUse->format(Pattern::SQL_DATE));
        $this->log->debug("SoftwareCertificate set to: " . $this->softwareCertificate);
        $this->log->debug("ProcessingMediumCode set to: " . $this->processingMediumCode->value);

        $today = Date::parse(Pattern::SQL_DATE, (new Date())->format(Pattern::SQL_DATE));
        if ($this->getExpectedInitialDateUse()->isEarlier($today)) {
            $msg = "ExpectedInitialDateUse can not be earlier that NOW";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (!static::isValidSeries($this->series)) {
            $msg = "Series identifier not valid";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (!static::isValidSeriesInitialSequenceNumber($this->seriesInitialSequenceNumber)) {
            $msg = "SeriesInitialSequenceNumber identifier not valid";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }
    }

    /**
     * Indicate the identifier of the Series you want to communicate to AT.
     * @return string
     * @since 1.0.0
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Indicate the type of Series you want to communicate to AT
     * @return \Rebelo\ATWs\Series\SeriesTypeCode
     * @since 1.0.0
     */
    public function getSeriesTypeCode(): SeriesTypeCode
    {
        return $this->seriesTypeCode;
    }

    /**
     * Indicate the type of document to which the Series belongs to communicate to AT.
     * @return \Rebelo\ATWs\Series\DocumentTypeCode
     * @since 1.0.0
     */
    public function getDocumentTypeCode(): DocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the beginning of the document's sequence numbering in the Series.
     * @return int
     * @since 1.0.0
     */
    public function getSeriesInitialSequenceNumber(): int
    {
        return $this->seriesInitialSequenceNumber;
    }

    /**
     * Indicate the date from which the Series is expected to be used
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getExpectedInitialDateUse(): Date
    {
        return $this->expectedInitialDateUse;
    }

    /**
     * Indicate the invoicing program certificate number assigned by AT. If not applicable, it must be filled in with “0” (zero).
     * @return int
     * @since 1.0.0
     */
    public function getSoftwareCertificate(): int
    {
        return $this->softwareCertificate;
    }

    /**
     * Code of means of processing the documents to be issued.
     * @return \Rebelo\ATWs\Series\ProcessingMediumCodes
     * @since 1.0.0
     */
    public function getProcessingMediumCode(): ProcessingMediumCodes
    {
        return $this->processingMediumCode;
    }

    /**
     * Indicate the classification given to the document to which the Series belongs to be communicated to the AT.
     * Composed of a set of values
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getClassDocumentCode(): DocumentClassCode
    {
        return $this->documentTypeCode->getDocumentClassCode();
    }

}
