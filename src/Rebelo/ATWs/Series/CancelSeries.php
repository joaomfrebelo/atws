<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\ATWsException;

/**
 * This functionality aims to provide the action of canceling
 * the communication of a previously communicated Series, by error.
 * @since 1.0.0
 */
class CancelSeries extends ASeries
{

    /**
     * This functionality aims to provide the action of canceling the communication
     * of a previously communicated Series, by error.
     * @param string                               $series               The identifier of the Series whose communication you want to cancel.
     * @param \Rebelo\ATWs\Series\DocumentTypeCode $documentTypeCode     Indicate the type of document to which the Series whose communication you want to cancel belongs.
     * @param string                               $seriesValidationCode Indicate the Series validation code, assigned by the AT, whose communication you want to cancel.
     * @param bool                                 $noIssueDeclaration   Informative indication that the taxable person was aware that he should not cancel the communication of a Series if he has already used documents issued with the information thereof.     The communication will not be accepted if the taxable person does not indicate (filling in this parameter with the true value) that he was aware of the condition presented.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function __construct(
        private string           $series,
        private DocumentTypeCode $documentTypeCode,
        private string           $seriesValidationCode,
        private bool             $noIssueDeclaration
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("DocumentTypeCode set to: " . $this->documentTypeCode->get());
        $this->log->debug("SeriesValidationCode: " . $this->seriesValidationCode);
        $this->log->debug("NoIssueDeclaration: " . ($this->noIssueDeclaration ? "true" : "false"));

        if (!static::isValidSeries($this->series)) {
            $msg = "Series identifier not valid";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }
    }

    /**
     * The identifier of the Series whose communication you want to cancel.
     * @return string
     * @since 1.0.0
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Indicate the type of document to which the Series whose communication you want to cancel belongs.
     * @return \Rebelo\ATWs\Series\DocumentTypeCode
     * @since 1.0.0
     */
    public function getDocumentTypeCode(): DocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the Series validation code, assigned by the AT, whose communication you want to cancel.
     * @return string
     * @since 1.0.0
     */
    public function getSeriesValidationCode(): string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Informative indication that the taxable person was aware that he should not cancel
     * the communication of a Series if he has already used documents issued with the information thereof.     The communication will not be accepted if the taxable person does not indicate (filling in this parameter with the true value) that he was aware of the condition presented.
     * @return bool
     * @since 1.0.0
     */
    public function isNoIssueDeclaration(): bool
    {
        return $this->noIssueDeclaration;
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
