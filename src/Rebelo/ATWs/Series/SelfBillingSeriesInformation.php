<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\Date\Date;

/**
 * The Serial information returned by the operations of webservice
 * @since 1.0.0
 */
class SelfBillingSeriesInformation
{

    /**
     * @param string                                          $series                      Series Identifier.
     * @param \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode $documentTypeCode            Series document type code.
     * @param int                                             $seriesInitialSequenceNumber Start of document sequence numbering in the Series.
     * @param \Rebelo\Date\Date                               $expectedInitialDateUse      Date from which the use of the Series is expected.
     * @param int|null                                        $seriesLastSequenceNumber    Sequence of the last document issued in the Series.
     * @param \Rebelo\ATWs\Series\ProcessingMediumCodes       $processingMediumCode        Code of means of processing the documents to be issued.
     * @param int                                             $softwareCertificate         Billing program certificate number assigned by AT. If not applicable, the result is filled with “0” (zero).
     * @param string                                          $seriesValidationCode        Series validation code, assigned by AT.
     * @param \Rebelo\Date\Date                               $registerDate                Date of registration of the Series.
     * @param \Rebelo\ATWs\Series\SeriesStatusCode            $seriesStatusCode            Code of the status that the Series has during the communication process.
     * @param string|null                                     $statusReasonCode            Reason code for change of state
     * @param string|null                                     $statusReason                Pertinent remarks communicated about the end of the Series.
     * @param \Rebelo\Date\Date|null                          $statusDate                  Date of last change of status of the Series.
     * @param string                                          $registrationNif             Tax number of the taxpayer responsible for communicating the Series.
     *
     * @since 1.0.0
     */
    public function __construct(
        private string                      $series,
        private SelfBillingDocumentTypeCode $documentTypeCode,
        private int                         $seriesInitialSequenceNumber,
        private Date                        $expectedInitialDateUse,
        private ?int                        $seriesLastSequenceNumber,
        private ProcessingMediumCodes       $processingMediumCode,
        private int                         $softwareCertificate,
        private string                      $seriesValidationCode,
        private Date                        $registerDate,
        private SeriesStatusCode            $seriesStatusCode,
        private ?string                     $statusReasonCode,
        private ?string                     $statusReason,
        private ?Date                       $statusDate,
        private string                      $registrationNif
    )
    {
    }

    /**
     * Series Identifier.
     * @return string
     * @since 1.0.0
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Series document type code.
     * @return \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode
     * @since 1.0.0
     */
    public function getDocumentTypeCode(): SelfBillingDocumentTypeCode
    {
        return $this->documentTypeCode;
    }

    /**
     * Start of document sequence numbering in the Series.
     * @return int
     * @since 1.0.0
     */
    public function getSeriesInitialSequenceNumber(): int
    {
        return $this->seriesInitialSequenceNumber;
    }

    /**
     * Date from which the use of the Series is expected.
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getExpectedInitialDateUse(): Date
    {
        return $this->expectedInitialDateUse;
    }

    /**
     * Sequence of the last document issued in the Series.
     * @return int|null
     * @since 1.0.0
     */
    public function getSeriesLastSequenceNumber(): ?int
    {
        return $this->seriesLastSequenceNumber;
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
     * Billing program certificate number assigned by AT. If not applicable, the result is filled with “0” (zero).
     * @return int
     * @since 1.0.0
     */
    public function getSoftwareCertificate(): int
    {
        return $this->softwareCertificate;
    }

    /**
     * Series validation code, assigned by AT.
     * @return string
     * @since 1.0.0
     */
    public function getSeriesValidationCode(): string
    {
        return $this->seriesValidationCode;
    }

    /**
     * Date of registration of the Series.
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getRegisterDate(): Date
    {
        return $this->registerDate;
    }

    /**
     * Code of the status that the Series has during the communication process.
     * @return \Rebelo\ATWs\Series\SeriesStatusCode
     * @since 1.0.0
     */
    public function getSeriesStatusCode(): SeriesStatusCode
    {
        return $this->seriesStatusCode;
    }

    /**
     * Reason code for change of state
     * @return string|null
     * @since 1.0.0
     */
    public function getStatusReasonCode(): ?string
    {
        return $this->statusReasonCode;
    }

    /**
     * Pertinent remarks communicated about the end of the Series.
     * @return string|null
     * @since 1.0.0
     */
    public function getStatusReason(): ?string
    {
        return $this->statusReason;
    }

    /**
     * Date of last change of status of the Series.
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getStatusDate(): ?Date
    {
        return $this->statusDate;
    }

    /**
     * Tax number of the taxpayer responsible for communicating the Series.
     * @return string
     * @since 1.0.0
     */
    public function getRegistrationNif(): string
    {
        return $this->registrationNif;
    }

}
