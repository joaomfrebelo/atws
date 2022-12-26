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
 * @since 2.0.2
 */
class CancelSelfBillingSeries extends ASeries
{

    /**
     * This functionality aims to provide the action of canceling the communication
     * of a previously communicated Series, by error.
     *
     * @param string                                    $series                The identifier of the Series whose communication you want to cancel.
     * @param \Rebelo\ATWs\Series\DocumentTypeCode      $documentTypeCode      Indicate the type of document to which the Series whose communication you want to cancel belongs.
     * @param string                                    $seriesValidationCode  Indicate the Series validation code, assigned by the AT, whose communication you want to cancel.
     * @param bool                                      $noIssueDeclaration    Informative indication that the taxable person was aware that he should not cancel the communication of a Series if he has already used documents issued with the information thereof.     The communication will not be accepted if the taxable person does not indicate (filling in this parameter with the true value) that he was aware of the condition presented.
     * @param \Rebelo\ATWs\Series\SelfBillingEntityCode $selfBillingEntityCode Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @param string                                    $supplierTin           Indicate the TIN of the entity with whom established the prior Self-invoicing Agreement.
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function __construct(
        private string                $series,
        private DocumentTypeCode      $documentTypeCode,
        private string                $seriesValidationCode,
        private bool                  $noIssueDeclaration,
        private SelfBillingEntityCode $selfBillingEntityCode,
        private string                $supplierTin,
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("DocumentTypeCode set to: " . $this->documentTypeCode->get());
        $this->log->debug("SeriesValidationCode: " . $this->seriesValidationCode);
        $this->log->debug("NoIssueDeclaration: " . ($this->noIssueDeclaration ? "true" : "false"));
        $this->log->debug("selfBillingEntityCode set to: " . $this->selfBillingEntityCode->get());
        $this->log->debug("Supplier tin set to: " . $this->supplierTin);

        if (!static::isValidSeries($this->series)) {
            $msg = "Series identifier not valid";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }
    }

    /**
     * The identifier of the Series whose communication you want to cancel.
     * @return string
     * @since 2.0.2
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Indicate the type of document to which the Series whose communication you want to cancel belongs.
     * @return \Rebelo\ATWs\Series\DocumentTypeCode
     * @since 2.0.2
     */
    public function getDocumentTypeCode(): DocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the Series validation code, assigned by the AT, whose communication you want to cancel.
     * @return string
     * @since 2.0.2
     */
    public function getSeriesValidationCode(): string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Informative indication that the taxable person was aware that he should not cancel
     * the communication of a Series if he has already used documents issued with the information thereof.     The communication will not be accepted if the taxable person does not indicate (filling in this parameter with the true value) that he was aware of the condition presented.
     * @return bool
     * @since 2.0.2
     */
    public function isNoIssueDeclaration(): bool
    {
        return $this->noIssueDeclaration;
    }

    /**
     * Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @return \Rebelo\ATWs\Series\SelfBillingEntityCode
     * @since 2.0.2
     */
    public function getSelfBillingEntityCode(): SelfBillingEntityCode
    {
        return $this->selfBillingEntityCode;
    }

    /**
     * Indicate the classification given to the document to which the Series belongs to be communicated to the AT.
     * Composed of a set of values
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function getClassDocumentCode(): DocumentClassCode
    {
        return $this->documentTypeCode->getDocumentClassCode();
    }

    /**
     * Indicate the TIN of the entity with whom established the prior Self-invoicing Agreement.
     * @return string
     * @since 2.0.2
     */
    public function getSupplierTin(): string
    {
        return $this->supplierTin;
    }

}
