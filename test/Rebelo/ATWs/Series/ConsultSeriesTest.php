<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * ConsultSeries Test
 */
class ConsultSeriesTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(ConsultSeries::class))->testReflection(ConsultSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testInstance(): void
    {
        $series = "A999";
        $seriesTypeCode = SeriesTypeCode::N;
        $documentClassCode = DocumentClassCode::SI;
        $documentTypeCode = DocumentTypeCode::FT;
        $seriesValidationCode = "AAA999";
        $fromRegistrationDate = (new Date())->addDays(-99);
        $toRegistrationDate = new Date();
        $seriesStatusCode = SeriesStatusCode::A;
        $processingMediumCodes = ProcessingMediumCodes::PF;

        $consultSeries = new ConsultSeries(
            $series,
            $seriesTypeCode,
            $documentClassCode,
            $documentTypeCode,
            $seriesValidationCode,
            $fromRegistrationDate,
            $toRegistrationDate,
            $seriesStatusCode,
            $processingMediumCodes
        );

        $this->assertSame($series, $consultSeries->getSeries());
        $this->assertSame($seriesTypeCode, $consultSeries->getSeriesTypeCode());
        $this->assertSame($documentClassCode, $consultSeries->getDocumentClassCode());
        $this->assertSame($documentTypeCode, $consultSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $consultSeries->getSeriesValidationCode());
        $this->assertSame($fromRegistrationDate, $consultSeries->getFromRegistrationDate());
        $this->assertSame($toRegistrationDate, $consultSeries->getToRegistrationDate());
        $this->assertSame($seriesStatusCode, $consultSeries->getSeriesStatusCode());
        $this->assertSame($processingMediumCodes, $consultSeries->getProcessingMediumCodes());
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstanceNull(): void
    {
        $consultSeries = new ConsultSeries();
        $this->assertNull($consultSeries->getSeries());
        $this->assertNull($consultSeries->getDocumentTypeCode());
        $this->assertNull($consultSeries->getSeriesValidationCode());
        $this->assertNull($consultSeries->getDocumentClassCode());
        $this->assertNull($consultSeries->getSeriesTypeCode());
        $this->assertNull($consultSeries->getSeriesStatusCode());
        $this->assertNull($consultSeries->getFromRegistrationDate());
        $this->assertNull($consultSeries->getToRegistrationDate());
        $this->assertNull($consultSeries->getProcessingMediumCodes());
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testWrongDates(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("ToRegistrationDate can not be early than FromRegistrationDate");
        new ConsultSeries(
            fromRegistrationDate: new Date(),
            toRegistrationDate: (new Date())->addSeconds(-1)
        );
    }
}
