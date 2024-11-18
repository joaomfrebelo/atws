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
 * This functionality aims to make available the consultation of reported Series
 * @since 1.0.0
 */
class ConsultSeries extends ASeries
{
    /**
     * This functionality aims to make available the consultation of reported Series
     *
     * @param string|null                                    $series                Indicate the identifier of the Series you wish to consult.
     * @param \Rebelo\ATWs\Series\SeriesTypeCode|null        $seriesTypeCode        Indicate the type of Series you want to consult.
     * @param \Rebelo\ATWs\Series\DocumentClassCode|null     $documentClassCode     Indicate the classification given to the Document to which the Series you want to consult belongs
     * @param \Rebelo\ATWs\Series\DocumentTypeCode|null      $documentTypeCode      Indicate the Type of document to which the Series you want to consult belongs.
     * @param string|null                                    $seriesValidationCode  Indicate the validation code of the Series you wish to consult.
     * @param \Rebelo\Date\Date|null                         $fromRegistrationDate  Enter the start date of the search interval.
     * @param \Rebelo\Date\Date|null                         $toRegistrationDate    Enter the end date of the search interval.
     * @param \Rebelo\ATWs\Series\SeriesStatusCode|null      $seriesStatusCode      Indicate the status of the Series you want to consult.
     * @param \Rebelo\ATWs\Series\ProcessingMediumCodes|null $processingMediumCodes Code of means of processing the documents to be issued.
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function __construct(
        private readonly ?string            $series = null,
        private readonly ?SeriesTypeCode    $seriesTypeCode = null,
        private readonly ?DocumentClassCode $documentClassCode = null,
        private readonly ?DocumentTypeCode  $documentTypeCode = null,
        private readonly ?string            $seriesValidationCode = null,
        private readonly ?Date              $fromRegistrationDate = null,
        private readonly ?Date              $toRegistrationDate = null,
        private readonly ?SeriesStatusCode  $seriesStatusCode = null,
        private readonly ?ProcessingMediumCodes $processingMediumCodes = null
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("SeriesTypeCode set to: " . ($this->seriesTypeCode?->value ?? "null"));
        $this->log->debug("DocumentClassCode set to: " . ($this->documentClassCode?->value ?? "null"));
        $this->log->debug("DocumentTypeCode set to: " . ($this->documentTypeCode?->value ?? "null"));
        $this->log->debug("SeriesValidationCode set to: " . $this->seriesValidationCode);
        $this->log->debug(
            "ToRegistrationDate set to: "
            . ($this->toRegistrationDate?->format(Pattern::SQL_DATE) ?? "null")
        );
        $this->log->debug(
            "FromRegistrationDate set to: "
            . ($this->fromRegistrationDate?->format(Pattern::SQL_DATE) ?? "null")
        );
        $this->log->debug("ProcessingMediumCodes set to: " . ($this->processingMediumCodes?->value ?? "null"));

        if($this->fromRegistrationDate !== null && $this->toRegistrationDate !== null){
            if($this->toRegistrationDate->isEarlier($this->fromRegistrationDate)){
                throw new ATWsException("ToRegistrationDate can not be early than FromRegistrationDate");
            }
        }

    }

    /**
     * Indicate the identifier of the Series you wish to consult.
     * @return string|null
     * @since 1.0.0
     */
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * Indicate the type of Series you want to consult.
     * @return \Rebelo\ATWs\Series\SeriesTypeCode|null
     * @since 1.0.0
     */
    public function getSeriesTypeCode(): ?SeriesTypeCode
    {
        return $this->seriesTypeCode;
    }

    /**
     * Indicate the classification given to the Document to which the Series you want to consult belongs
     * @return \Rebelo\ATWs\Series\DocumentClassCode|null
     * @since 1.0.0
     */
    public function getDocumentClassCode(): ?DocumentClassCode
    {
        return $this->documentClassCode;
    }

    /**
     * Indicate the Type of document to which the Series you want to consult belongs.
     * @return \Rebelo\ATWs\Series\DocumentTypeCode|null
     * @since 1.0.0
     */
    public function getDocumentTypeCode(): ?DocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the validation code of the Series you wish to consult.
     * @return string|null
     * @since 1.0.0
     */
    public function getSeriesValidationCode(): ?string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Enter the start date of the search interval.
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getFromRegistrationDate(): ?Date
    {
        return $this->fromRegistrationDate;
    }

    /**
     * Enter the end date of the search interval.
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getToRegistrationDate(): ?Date
    {
        return $this->toRegistrationDate;
    }

    /**
     * Indicate the status of the Series you want to consult.
     * @return \Rebelo\ATWs\Series\SeriesStatusCode|null
     * @since 1.0.0
     */
    public function getSeriesStatusCode(): ?SeriesStatusCode
    {
        return $this->seriesStatusCode;
    }

    /**
     * Code of means of processing the documents to be issued.
     * @return \Rebelo\ATWs\Series\ProcessingMediumCodes|null
     * @since 1.0.0
     */
    public function getProcessingMediumCodes(): ?ProcessingMediumCodes
    {
        return $this->processingMediumCodes;
    }

}
