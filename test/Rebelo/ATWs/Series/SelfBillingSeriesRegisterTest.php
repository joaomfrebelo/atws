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
class SelfBillingSeriesRegisterTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(SelfBillingSeriesRegister::class);
        $this->assertTrue(true);
    }

    /**
     * Wrong Series data provider
     * @return array
     */
    public function providerSeries(): array
    {
        return [
            ["A-A"], ["A_A"], ["A.A"], ["A"], ["AAA"],
        ];
    }

    /**
     * @test
     * @dataProvider providerSeries
     *
     * @param string $series
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstance(string $series): void
    {
        $documentTypeCode            = SelfBillingDocumentTypeCode::FT();
        $seriesInitialSequenceNumber = 999;
        $expectedInitialDateUse      = (new Date())->addMinutes(1);
        $softwareCertificate         = 9999;
        $supplierTin                 = (string)\rand(111111111, 999999999);
        $entityCode                  = SelfBillingEntityCode::CE();

        $seriesRegister = new SelfBillingSeriesRegister(
            $series,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $entityCode,
            $supplierTin,
            null,
            null
        );

        $this->assertInstanceOf(SelfBillingSeriesRegister::class, $seriesRegister);
        $this->assertSame($series, $seriesRegister->getSeries());
        $this->assertSame($documentTypeCode, $seriesRegister->getDocumentTypeCode());
        $this->assertSame($seriesInitialSequenceNumber, $seriesRegister->getSeriesInitialSequenceNumber());
        $this->assertSame($expectedInitialDateUse, $seriesRegister->getExpectedInitialDateUse());
        $this->assertSame($softwareCertificate, $seriesRegister->getSoftwareCertificate());
         $this->assertSame(
            DocumentClassCode::mapDocTypeToClassDoc($seriesRegister->getDocumentTypeCode()->get())->get(),
            $seriesRegister->getClassDocumentCode()->get()
        );
        $this->assertSame($entityCode, $seriesRegister->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $seriesRegister->getSupplierTin());
        $this->assertNull($seriesRegister->getSupplierCountry());
        $this->assertNull($seriesRegister->getForeignSupplierName());

        $supplierCounty     = "KR";
        $foreignSupplierName = "Korean company";

        $seriesRegister = new SelfBillingSeriesRegister(
            $series,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $entityCode,
            $supplierTin,
            $supplierCounty,
            $foreignSupplierName
        );

        $this->assertSame($supplierCounty, $seriesRegister->getSupplierCountry());
        $this->assertSame($foreignSupplierName, $seriesRegister->getForeignSupplierName());
    }

    /**
     * Wrong Series data provider
     * @return array
     */
    public function providerWrongSeries(): array
    {
        return [
            ["-A"], ["A-"], [".A"], ["A."], ["_A"], ["A_"], [""], ["A A"], [" "], ["AÇ"], ["É"],
        ];
    }

    /**
     * @test
     * @dataProvider providerWrongSeries
     *
     * @param string $series
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongSeries(string $series): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Series identifier not valid");

        $documentTypeCode            = SelfBillingDocumentTypeCode::FT();
        $seriesInitialSequenceNumber = 999;
        $expectedInitialDateUse      = (new Date())->addMinutes(1);
        $softwareCertificate         = 9999;
        $supplierTin                 = (string)\rand(111111111, 999999999);
        $entityCode                  = SelfBillingEntityCode::CE();

        new SelfBillingSeriesRegister(
            $series,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $entityCode,
            $supplierTin,
            null,
            null
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
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongNumber(int $seriesInitialSequenceNumber): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SeriesInitialSequenceNumber identifier not valid");

        $series                      = "A";
        $documentTypeCode            = SelfBillingDocumentTypeCode::FT();
        $expectedInitialDateUse      = (new Date())->addMinutes(1);
        $softwareCertificate         = 9999;
        $supplierTin                 = (string)\rand(111111111, 999999999);
        $entityCode                  = SelfBillingEntityCode::CE();

        new SelfBillingSeriesRegister(
            $series,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $entityCode,
            $supplierTin,
            null,
            null
        );
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceWrongDate(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("ExpectedInitialDateUse can not be earlier that NOW");

        $series                      = "A";
        $documentTypeCode            = SelfBillingDocumentTypeCode::FT();
        $seriesInitialSequenceNumber = 999;
        $expectedInitialDateUse      = (new Date())->addDays(-1);
        $softwareCertificate         = 9999;
        $supplierTin                 = (string)\rand(111111111, 999999999);
        $entityCode                  = SelfBillingEntityCode::CE();

        new SelfBillingSeriesRegister(
            $series,
            $documentTypeCode,
            $seriesInitialSequenceNumber,
            $expectedInitialDateUse,
            $softwareCertificate,
            $entityCode,
            $supplierTin,
            null,
            null
        );
    }

}
