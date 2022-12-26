<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPStan\Testing\TestCase;
use Rebelo\Base;

/**
 * FinalizeSeries Test
 */
class FinalizeSelfBillingSeriesTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(FinalizeSelfBillingSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $series                = "AAA";
        $documentTypeCode      = SelfBillingDocumentTypeCode::NC();
        $seriesValidationCode  = "99999999";
        $lastSequenceDocNumber = 99;
        $entityCode            = SelfBillingEntityCode::CE();
        $supplierTin           = "555555555";
        $reason                = "No reason";

        $finalizeSeries = new FinalizeSelfBillingSeries(
            $series,
            $documentTypeCode,
            $seriesValidationCode,
            $lastSequenceDocNumber,
            $entityCode,
            $supplierTin,
            $reason,
        );

        $this->assertSame($series, $finalizeSeries->getSeries());
        $this->assertSame($documentTypeCode->get(), $finalizeSeries->getDocumentTypeCode()->get());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($entityCode, $finalizeSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $finalizeSeries->getSupplierTin());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }

    /**
     * @test
     * @return void
     */
    public function testInstanceNull(): void
    {
        $series                = "AAA";
        $documentTypeCode      = SelfBillingDocumentTypeCode::NC();
        $seriesValidationCode  = "99999999";
        $lastSequenceDocNumber = 99;
        $entityCode            = SelfBillingEntityCode::CE();
        $supplierTin           = "555555555";
        $reason                = null;

        $finalizeSeries = new FinalizeSelfBillingSeries(
            $series,
            $documentTypeCode,
            $seriesValidationCode,
            $lastSequenceDocNumber,
            $entityCode,
            $supplierTin,
            $reason,
        );

        $this->assertSame($series, $finalizeSeries->getSeries());
        $this->assertSame($documentTypeCode->get(), $finalizeSeries->getDocumentTypeCode()->get());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($entityCode, $finalizeSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $finalizeSeries->getSupplierTin());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }


}
