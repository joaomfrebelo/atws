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

/**
 * CancelSeries tests
 */
class CancelSelfBillingSeriesTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(CancelSelfBillingSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        $series               = "B999";
        $documentTypeCode     = DocumentTypeCode::FT();
        $seriesValidationCode = "99999999";
        $entityCode           = SelfBillingEntityCode::CE();
        $supplierTin          = "555555550";

        foreach ([true, false] as $bool)
            $cancelSeries = new CancelSelfBillingSeries(
                $series,
                $documentTypeCode,
                $seriesValidationCode,
                $bool,
                $entityCode,
                $supplierTin
            );

        $this->assertSame($series, $cancelSeries->getSeries());
        $this->assertSame($documentTypeCode->get(), $cancelSeries->getDocumentTypeCode()->get());
        $this->assertSame($seriesValidationCode, $cancelSeries->getSeriesValidationCode());
        $this->assertSame($bool, $cancelSeries->isNoIssueDeclaration());
        $this->assertSame($entityCode, $cancelSeries->getSelfBillingEntityCode());
        $this->assertSame($supplierTin, $cancelSeries->getSupplierTin());
    }

}
