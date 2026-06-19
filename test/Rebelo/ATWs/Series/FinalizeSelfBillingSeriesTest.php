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
use Rebelo\Base;

/**
 * FinalizeSeries Test
 */
class FinalizeSelfBillingSeriesTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(FinalizeSelfBillingSeries::class))->testReflection(FinalizeSelfBillingSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstance(): void
    {
        $series                = "AAA";
        $documentTypeCode      = SelfBillingDocumentTypeCode::NC;
        $seriesValidationCode  = "99999999";
        $lastSequenceDocNumber = 99;
        $entityCode            = SelfBillingEntityCode::CE;
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
        $this->assertSame($documentTypeCode, $finalizeSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($entityCode, $finalizeSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $finalizeSeries->getSupplierTin());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstanceNull(): void
    {
        $series                = "AAA";
        $documentTypeCode      = SelfBillingDocumentTypeCode::NC;
        $seriesValidationCode  = "99999999";
        $lastSequenceDocNumber = 99;
        $entityCode            = SelfBillingEntityCode::CE;
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
        $this->assertSame($documentTypeCode, $finalizeSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($entityCode, $finalizeSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $finalizeSeries->getSupplierTin());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }

}
