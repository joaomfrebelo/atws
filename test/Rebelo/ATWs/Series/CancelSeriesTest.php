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
class CancelSeriesTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(CancelSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        $series = "B999";
        $documentTypeCode = DocumentTypeCode::PF;
        $seriesValidationCode = "99999999";

        foreach ([true, false] as $bool)
            $cancelSeries = new CancelSeries(
                $series,
                $documentTypeCode,
                $seriesValidationCode,
                $bool
            );

        $this->assertSame($series, $cancelSeries->getSeries());
        $this->assertSame($documentTypeCode, $cancelSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $cancelSeries->getSeriesValidationCode());
        $this->assertSame($bool, $cancelSeries->isNoIssueDeclaration());
    }

}
