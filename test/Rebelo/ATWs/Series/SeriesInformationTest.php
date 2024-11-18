<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * SeriesInformation Test
 */
class SeriesInformationTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(SeriesInformation::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @test
     */
    public function testInstance(): void
    {
        $series = "A";
        $seriesTypeCode = SeriesTypeCode::N;
        $documentClassCode = DocumentClassCode::PY;
        $documentTypeCode = DocumentTypeCode::RC;
        $seriesInitialSequenceNumber = 9;
        $expectedInitialDateUse = (new Date())->addDays(-2);
        $seriesLastSequenceNumber = 999;
        $processingMediumCode = ProcessingMediumCodes::PF;
        $softwareCertificate = 9999;
        $seriesValidationCode = "12345789";
        $registerDate = (new Date())->addDays(-1);
        $seriesStatusCode = SeriesStatusCode::N;
        $statusReasonCode = "RE";
        $statusReason = "Status reason";
        $statusDate = new Date();
        $registrationNif = "999999990";

        $seriesInformation = new SeriesInformation(
            $series,
            $seriesTypeCode,
            $documentClassCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $seriesLastSequenceNumber,
            $processingMediumCode,
            $softwareCertificate,
            $seriesValidationCode,
            $registerDate,
            $seriesStatusCode,
            $statusReasonCode,
            $statusReason,
            $statusDate,
            $registrationNif,
        );

        $this->assertSame($series, $seriesInformation->getSeries());
        $this->assertSame($seriesTypeCode, $seriesInformation->getSeriesTypeCode());
        $this->assertSame($documentClassCode, $seriesInformation->getDocumentClassCode());
        $this->assertSame($documentTypeCode, $seriesInformation->getDocumentTypeCode());
        $this->assertSame($seriesInitialSequenceNumber, $seriesInformation->getSeriesInitialSequenceNumber());
        $this->assertSame($expectedInitialDateUse, $seriesInformation->getExpectedInitialDateUse());
        $this->assertSame($seriesLastSequenceNumber, $seriesInformation->getSeriesLastSequenceNumber());
        $this->assertSame($processingMediumCode, $seriesInformation->getProcessingMediumCode());
        $this->assertSame($softwareCertificate, $seriesInformation->getSoftwareCertificate());
        $this->assertSame($seriesValidationCode, $seriesInformation->getSeriesValidationCode());
        $this->assertSame($registerDate, $seriesInformation->getRegisterDate());
        $this->assertSame($seriesStatusCode, $seriesInformation->getSeriesStatusCode());
        $this->assertSame($statusReasonCode, $seriesInformation->getStatusReasonCode());
        $this->assertSame($statusReason, $seriesInformation->getStatusReason());
        $this->assertSame($statusReasonCode, $seriesInformation->getStatusReasonCode());
        $this->assertSame($statusDate, $seriesInformation->getStatusDate());
        $this->assertSame($registrationNif, $seriesInformation->getRegistrationNif());
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     * @test
     */
    public function testInstanceNull(): void
    {
        $series = "AB";
        $seriesTypeCode = SeriesTypeCode::N;
        $documentClassCode = DocumentClassCode::PY;
        $documentTypeCode = DocumentTypeCode::RC;
        $seriesInitialSequenceNumber = 9;
        $expectedInitialDateUse = (new Date())->addDays(-2);
        $seriesLastSequenceNumber = null;
        $processingMediumCode = ProcessingMediumCodes::PF;
        $softwareCertificate = 9999;
        $seriesValidationCode = "12345789";
        $registerDate = (new Date())->addDays(-1);
        $seriesStatusCode = SeriesStatusCode::N;
        $statusReasonCode = null;
        $statusReason = null;
        $statusDate = null;
        $registrationNif = "999999990";

        $seriesInformation = new SeriesInformation(
            $series,
            $seriesTypeCode,
            $documentClassCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $seriesLastSequenceNumber,
            $processingMediumCode,
            $softwareCertificate,
            $seriesValidationCode,
            $registerDate,
            $seriesStatusCode,
            $statusReasonCode,
            $statusReason,
            $statusDate,
            $registrationNif,
        );

        $this->assertSame($series, $seriesInformation->getSeries());
        $this->assertSame($seriesTypeCode, $seriesInformation->getSeriesTypeCode());
        $this->assertSame($documentClassCode, $seriesInformation->getDocumentClassCode());
        $this->assertSame($documentTypeCode, $seriesInformation->getDocumentTypeCode());
        $this->assertSame($seriesInitialSequenceNumber, $seriesInformation->getSeriesInitialSequenceNumber());
        $this->assertSame($expectedInitialDateUse, $seriesInformation->getExpectedInitialDateUse());
        $this->assertSame($seriesLastSequenceNumber, $seriesInformation->getSeriesLastSequenceNumber());
        $this->assertSame($processingMediumCode, $seriesInformation->getProcessingMediumCode());
        $this->assertSame($softwareCertificate, $seriesInformation->getSoftwareCertificate());
        $this->assertSame($seriesValidationCode, $seriesInformation->getSeriesValidationCode());
        $this->assertSame($registerDate, $seriesInformation->getRegisterDate());
        $this->assertSame($seriesStatusCode, $seriesInformation->getSeriesStatusCode());
        $this->assertSame($statusReasonCode, $seriesInformation->getStatusReasonCode());
        $this->assertSame($statusReason, $seriesInformation->getStatusReason());
        $this->assertSame($statusReasonCode, $seriesInformation->getStatusReasonCode());
        $this->assertSame($statusDate, $seriesInformation->getStatusDate());
        $this->assertSame($registrationNif, $seriesInformation->getRegistrationNif());
    }

}
