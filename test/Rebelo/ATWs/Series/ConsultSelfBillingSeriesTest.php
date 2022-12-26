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
 * ConsultSeries Test
 */
class ConsultSelfBillingSeriesTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ConsultSelfBillingSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        $series               = "A999";
        $documentTypeCode     = SelfBillingDocumentTypeCode::FT();
        $seriesValidationCode = "AAA999";
        $fromRegistrationDate = (new Date())->addDays(-99);
        $toRegistrationDate   = new Date();
        $entityCode           = SelfBillingEntityCode::CE();
        $supplierTin          = "999999990";

        $consultSeries = new ConsultSelfBillingSeries(
            $series,
            $documentTypeCode,
            $seriesValidationCode,
            $fromRegistrationDate,
            $toRegistrationDate,
            $entityCode,
            $supplierTin
        );

        $this->assertSame($series, $consultSeries->getSeries());
        $this->assertSame($documentTypeCode, $consultSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $consultSeries->getSeriesValidationCode());
        $this->assertSame($fromRegistrationDate, $consultSeries->getFromRegistrationDate());
        $this->assertSame($toRegistrationDate, $consultSeries->getToRegistrationDate());
        $this->assertSame($entityCode, $consultSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $consultSeries->getSupplierTin());
    }

    /**
     * @test
     * @return void
     */
    public function testInstanceNull(): void
    {
        $consultSeries = new ConsultSelfBillingSeries();
        $this->assertNull($consultSeries->getSeries());
        $this->assertNull($consultSeries->getDocumentTypeCode());
        $this->assertNull($consultSeries->getSeriesValidationCode());
        $this->assertNull($consultSeries->getFromRegistrationDate());
        $this->assertNull($consultSeries->getToRegistrationDate());
        $this->assertNull($consultSeries->getSelfBillingEntityCode());
        $this->assertNull($consultSeries->getSupplierTin());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongDates(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("ToRegistrationDate can not be early than FromRegistrationDate");
        new ConsultSelfBillingSeries(
            fromRegistrationDate: new Date(),
            toRegistrationDate:   (new Date())->addSeconds(-1)
        );
    }
}
