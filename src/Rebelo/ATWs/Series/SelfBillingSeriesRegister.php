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
 * This functionality aims to allow the communication of the Self billing Series to the AT,
 * through their registration, so that a unique validation code for the Series is assigned.
 * @since 2.0.2
 */
class SelfBillingSeriesRegister extends ASeries
{

    /**
     * This functionality aims to allow the communication of the Series to the AT,
     * through their registration, so that a unique validation code for the Series is assigned.
     *
     * @param string                                          $series                      Indicate the identifier of the Series you want to communicate to AT.
     * @param \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode $documentTypeCode            Indicate the type of document to which the Series belongs to communicate to AT.
     * @param int                                             $seriesInitialSequenceNumber Indicate the beginning of the document's sequence numbering in the Series.
     * @param \Rebelo\Date\Date                               $expectedInitialDateUse      Indicate the date from which the Series is expected to be used
     * @param int                                             $softwareCertificate         Indicate the invoicing program certificate number assigned by AT. If not applicable, it must be filled in with “0” (zero).
     * @param \Rebelo\ATWs\Series\SelfBillingEntityCode       $selfBillingEntityCode       Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @param string                                          $supplierTin                 Indicate the TIN of the entity with whom established the prior Self-invoicing Agreement.
     * @param string|null                                     $supplierCountry             If not portuguese Indicate the country of the entity with whom established the prior Self-invoicing Agreement.
     * @param string|null                                     $foreignSupplierName         If not portuguese Indicate the name of the entity with whom established the prior Self-invoicing Agreement.
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @since 2.0.2
     */
    public function __construct(
        private string                      $series,
        private SelfBillingDocumentTypeCode $documentTypeCode,
        private int                         $seriesInitialSequenceNumber,
        private Date                        $expectedInitialDateUse,
        private int                         $softwareCertificate,
        private SelfBillingEntityCode       $selfBillingEntityCode,
        private string                      $supplierTin,
        private ?string                     $supplierCountry,
        private ?string                     $foreignSupplierName
    )
    {
        parent::__construct();
        $this->log->debug(__METHOD__);
        $this->log->debug("Series set to: " . $this->series);
        $this->log->debug("DocumentTypeCode set to: " . $this->documentTypeCode->get());
        $this->log->debug("SeriesInitialSequenceNumber set to: " . $this->seriesInitialSequenceNumber);
        $this->log->debug("ExpectedInitialDateUse set to: " . $this->expectedInitialDateUse->format(Date::SQL_DATE));
        $this->log->debug("SoftwareCertificate set to: " . $this->softwareCertificate);
        $this->log->debug("selfBillingEntityCode set to: " . $this->selfBillingEntityCode->get());
        $this->log->debug("Supplier tin set to: " . $this->supplierTin);
        $this->log->debug("Supplier country set to: " . ($this->supplierCountry ?? "null"));
        $this->log->debug("ForeignSupplierName set to: " . ($this->foreignSupplierName ?? "null"));

        $today = Date::parse(Date::SQL_DATE, (new Date())->format(Date::SQL_DATE));
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
     * @since 2.0.2
     */
    public function getSeries(): string
    {
        return $this->series;
    }


    /**
     * Indicate the type of document to which the Series belongs to communicate to AT.
     * @return \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode
     * @since 2.0.2
     */
    public function getDocumentTypeCode(): SelfBillingDocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Indicate the beginning of the document's sequence numbering in the Series.
     * @return int
     * @since 2.0.2
     */
    public function getSeriesInitialSequenceNumber(): int
    {
        return $this->seriesInitialSequenceNumber;
    }

    /**
     * Indicate the date from which the Series is expected to be used
     * @return \Rebelo\Date\Date
     * @since 2.0.2
     */
    public function getExpectedInitialDateUse(): Date
    {
        return $this->expectedInitialDateUse;
    }

    /**
     * Indicate the invoicing program certificate number assigned by AT. If not applicable, it must be filled in with “0” (zero).
     * @return int
     * @since 2.0.2
     */
    public function getSoftwareCertificate(): int
    {
        return $this->softwareCertificate;
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
     * Indicate the type of entity with whom established the prior Self-invoicing Agreement.
     * @return \Rebelo\ATWs\Series\SelfBillingEntityCode
     * @since 2.0.2
     */
    public function getSelfBillingEntityCode(): SelfBillingEntityCode
    {
        return $this->selfBillingEntityCode;
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

    /**
     * If not portuguese Indicate the country of the entity with whom established the prior Self-invoicing Agreement.
     * @return string|null
     * @since 2.0.2
     */
    public function getSupplierCountry(): ?string
    {
        return $this->supplierCountry;
    }

    /**
     * If not portuguese Indicate the name of the entity with whom established the prior Self-invoicing Agreement.
     * @return string|null
     * @since 2.0.2
     */
    public function getForeignSupplierName(): ?string
    {
        return $this->foreignSupplierName;
    }

}
