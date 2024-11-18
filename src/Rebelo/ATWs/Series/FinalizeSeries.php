<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

/**
 * This functionality is intended to indicate that a Series was valid for a set of documents,
 * but that it will no longer be used from the last document communicated
 * @since 1.0.0
 */
class FinalizeSeries extends ASeries
{

    /**
     * This functionality is intended to indicate that a Series was valid for a set of documents,
     * but that it will no longer be used from the last document communicated
     * @param string                               $series                Indicate the identifier of the Series you want to complete.
     * @param \Rebelo\ATWs\Series\DocumentTypeCode $documentTypeCode      Indicate the type of document to which the Series you want to finalize belongs.
     * @param string                               $seriesValidationCode  Indicate the validation code of the Series, assigned by the AT, that you want to finalize.
     * @param int                                  $lastSequenceDocNumber Indicate the number of the last document issued in the Series you want to finalize.
     * @param string|null                          $reason                Indicate observations relevant to the end of the Series.
     * @since 1.0.0
     */
    public function __construct(
        private string           $series,
        private DocumentTypeCode $documentTypeCode,
        private string           $seriesValidationCode,
        private int              $lastSequenceDocNumber,
        private ?string          $reason = null
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("DocumentTypeCode set to: " . $this->documentTypeCode->value);
        $this->log->debug("SeriesValidationCode: " . $this->seriesValidationCode);
        $this->log->debug("lastSequenceDocNumber: " . $this->lastSequenceDocNumber);
        $this->log->debug("Reason: " . ($this->reason ?? "null"));
    }

    /**
     * Indicate the identifier of the Series you want to complete.
     * @return string
     * @since 1.0.0
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Indicate the type of document to which the Series you want to finalize belongs.
     * @return \Rebelo\ATWs\Series\DocumentTypeCode
     * @since 1.0.0
     */
    public function getDocumentTypeCode(): DocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the validation code of the Series, assigned by the AT, that you want to finalize.
     * @return string
     * @since 1.0.0
     */
    public function getSeriesValidationCode(): string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Indicate the number of the last document issued in the Series you want to finalize.
     * @return int
     * @since 1.0.0
     */
    public function getLastSequenceDocNumber(): int
    {
        return $this->lastSequenceDocNumber;
    }

    /**
     * Indicate observations relevant to the end of the Series.
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        return $this->reason;
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
