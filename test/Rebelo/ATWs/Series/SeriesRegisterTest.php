<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * SeriesRegister Test
 */
class SeriesRegisterTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(SeriesRegister::class);
        $this->assertTrue(true);
    }

    /**
     * Wrong Series data provider
     * @return array
     */
    public function providerSeries(): array
    {
        return [
            ["A-A"], ["A_A"], ["A.A"], ["A"], ["AAA"]
        ];
    }

    /**
     * @test
     * @dataProvider providerSeries
     *
     * @param string $series
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstance(string $series): void
    {
        $seriesTypeCode = SeriesTypeCode::N;
        $documentTypeCode = DocumentTypeCode::OR;
        $seriesInitialSequenceNumber = 999;
        $expectedInitialDateUse = (new Date())->addMinutes(1);
        $softwareCertificate = 9999;
        $processingMediumCode = ProcessingMediumCodes::PF;

        $seriesRegister = new SeriesRegister(
            $series,
            $seriesTypeCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $processingMediumCode
        );

        $this->assertInstanceOf(SeriesRegister::class, $seriesRegister);
        $this->assertSame($series, $seriesRegister->getSeries());
        $this->assertSame($seriesTypeCode, $seriesRegister->getSeriesTypeCode());
        $this->assertSame($documentTypeCode, $seriesRegister->getDocumentTypeCode());
        $this->assertSame($seriesInitialSequenceNumber, $seriesRegister->getSeriesInitialSequenceNumber());
        $this->assertSame($expectedInitialDateUse, $seriesRegister->getExpectedInitialDateUse());
        $this->assertSame($softwareCertificate, $seriesRegister->getSoftwareCertificate());
        $this->assertSame($processingMediumCode, $seriesRegister->getProcessingMediumCode());
        $this->assertSame(
            DocumentClassCode::mapDocTypeToClassDoc($seriesRegister->getDocumentTypeCode()),
            $seriesRegister->getClassDocumentCode()
        );
    }

    /**
     * Wrong Series data provider
     * @return array
     */
    public function providerWrongSeries(): array
    {
        return [
            ["-A"], ["A-"], [".A"], ["A."], ["_A"], ["A_"], [""], ["A A"], [" "], ["AÇ"], ["É"]
        ];
    }

    /**
     * @test
     * @dataProvider providerWrongSeries
     *
     * @param string $series
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongSeries(string $series): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Series identifier not valid");

        $seriesTypeCode = SeriesTypeCode::N;
        $documentTypeCode = DocumentTypeCode::OR;
        $seriesInitialSequenceNumber = 999;
        $expectedInitialDateUse = (new Date())->addMinutes(1);
        $softwareCertificate = 9999;
        $processingMediumCode = ProcessingMediumCodes::PF;

        new SeriesRegister(
            $series,
            $seriesTypeCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $processingMediumCode
        );
    }

    /**
     * Wrong Sequential number data provider
     * @return array
     */
    public function providerWrongNumber(): array
    {
        return [
            [-1], [0],
        ];
    }

    /**
     * @test
     * @dataProvider providerWrongNumber
     *
     * @param int $seriesInitialSequenceNumber
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongNumber(int $seriesInitialSequenceNumber): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SeriesInitialSequenceNumber identifier not valid");

        $series = "A";
        $seriesTypeCode = SeriesTypeCode::N;
        $documentTypeCode = DocumentTypeCode::OR;
        $expectedInitialDateUse = (new Date())->addMinutes(1);
        $softwareCertificate = 9999;
        $processingMediumCode = ProcessingMediumCodes::PF;

        new SeriesRegister(
            $series,
            $seriesTypeCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $processingMediumCode
        );
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongDate(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("ExpectedInitialDateUse can not be earlier that NOW");

        $series = "A";
        $seriesTypeCode = SeriesTypeCode::N;
        $seriesInitialSequenceNumber = 999;
        $documentTypeCode = DocumentTypeCode::OR;
        $expectedInitialDateUse = (new Date())->addDays(-1);
        $softwareCertificate = 9999;
        $processingMediumCode = ProcessingMediumCodes::PF;

        new SeriesRegister(
            $series,
            $seriesTypeCode,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $processingMediumCode
        );
    }

}
