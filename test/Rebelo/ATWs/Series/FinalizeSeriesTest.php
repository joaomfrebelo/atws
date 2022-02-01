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
class FinalizeSeriesTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(FinalizeSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $series = "AAA";
        $documentTypeCode = DocumentTypeCode::NC();
        $seriesValidationCode = "99999999";
        $lastSequenceDocNumber = 99;
        $reason = "No reason";

        $finalizeSeries = new FinalizeSeries(
            $series,
            $documentTypeCode,
            $seriesValidationCode,
            $lastSequenceDocNumber,
            $reason
        );

        $this->assertSame($series, $finalizeSeries->getSeries());
        $this->assertSame($documentTypeCode->get(), $finalizeSeries->getDocumentTypeCode()->get());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }

    /**
     * @test
     * @return void
     */
    public function testInstanceNull(): void
    {
        $series = "AAA";
        $documentTypeCode = DocumentTypeCode::NC();
        $seriesValidationCode = "999999999";
        $lastSequenceDocNumber = 99;

        $finalizeSeries = new FinalizeSeries(
            $series,
            $documentTypeCode,
            $seriesValidationCode,
            $lastSequenceDocNumber,
            null
        );

        $this->assertSame($series, $finalizeSeries->getSeries());
        $this->assertSame($documentTypeCode->get(), $finalizeSeries->getDocumentTypeCode()->get());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertNull($finalizeSeries->getReason());

    }


}
