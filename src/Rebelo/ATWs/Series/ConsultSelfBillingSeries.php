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

/**
 * This functionality aims to make available the consultation of reported Series
 * @since 2.0.2
 */
class ConsultSelfBillingSeries extends ASeries
{
    /**
     * This functionality aims to make available the consultation of reported Series
     *
     * @param string|null                                          $series                Indicate the identifier of the Series you wish to consult.
     * @param \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode|null $documentTypeCode      Indicate the Type of document to which the Series you want to consult belongs.
     * @param string|null                                          $seriesValidationCode  Indicate the validation code of the Series you wish to consult.
     * @param \Rebelo\Date\Date|null                               $fromRegistrationDate  Enter the start date of the search interval.
     * @param \Rebelo\Date\Date|null                               $toRegistrationDate    Enter the end date of the search interval.
     * @param \Rebelo\ATWs\Series\SelfBillingEntityCode|null       $selfBillingEntityCode Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @param string|null                                          $supplierTin           Indicate the TIN of the entity with whom established the prior Self-invoicing Agreement.
     *
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function __construct(
        private ?string                      $series = null,
        private ?SelfBillingDocumentTypeCode $documentTypeCode = null,
        private ?string                      $seriesValidationCode = null,
        private ?Date                        $fromRegistrationDate = null,
        private ?Date                        $toRegistrationDate = null,
        private ?SelfBillingEntityCode       $selfBillingEntityCode = null,
        private ?string                      $supplierTin = null,
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("DocumentTypeCode set to: " . ($this->documentTypeCode?->get() ?? "null"));
        $this->log->debug("SeriesValidationCode set to: " . $this->seriesValidationCode);
        $this->log->debug(
            "ToRegistrationDate set to: "
            . ($this->toRegistrationDate?->format(Date::SQL_DATE) ?? "null")
        );
        $this->log->debug(
            "FromRegistrationDate set to: "
            . ($this->fromRegistrationDate?->format(Date::SQL_DATE) ?? "null")
        );

        if ($this->fromRegistrationDate !== null && $this->toRegistrationDate !== null) {
            if ($this->toRegistrationDate->isEarlier($this->fromRegistrationDate)) {
                throw new ATWsException("ToRegistrationDate can not be early than FromRegistrationDate");
            }
        }

        $this->log->debug("SelfBillingEntityCode: " . ($this->selfBillingEntityCode?->get() ?? "null"));
        $this->log->debug("Supplier: " . ($this->supplierTin ?? "null"));

    }

    /**
     * Indicate the identifier of the Series you wish to consult.
     * @return string|null
     * @since 2.0.2
     */
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * Indicate the Type of document to which the Series you want to consult belongs.
     * @return \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode|null
     * @since 2.0.2
     */
    public function getDocumentTypeCode(): ?SelfBillingDocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the validation code of the Series you wish to consult.
     * @return string|null
     * @since 2.0.2
     */
    public function getSeriesValidationCode(): ?string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Enter the start date of the search interval.
     * @return \Rebelo\Date\Date|null
     * @since 2.0.2
     */
    public function getFromRegistrationDate(): ?Date
    {
        return $this->fromRegistrationDate;
    }

    /**
     * Enter the end date of the search interval.
     * @return \Rebelo\Date\Date|null
     * @since 2.0.2
     */
    public function getToRegistrationDate(): ?Date
    {
        return $this->toRegistrationDate;
    }

    /**
     * Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @return \Rebelo\ATWs\Series\SelfBillingEntityCode|null
     * @since 2.0.2
     */
    public function getSelfBillingEntityCode(): ?SelfBillingEntityCode
    {
        return $this->selfBillingEntityCode;
    }

    /**
     * Indicate the TIN of the entity with whom established the prior Self-invoicing Agreement.
     * @return string|null
     * @since 2.0.2
     */
    public function getSupplierTin(): ?string
    {
        return $this->supplierTin;
    }

}
