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
class FinalizeSeriesTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(FinalizeSeries::class))->testReflection(FinalizeSeries::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstance(): void
    {
        $series = "AAA";
        $documentTypeCode = DocumentTypeCode::NC;
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
        $this->assertSame($documentTypeCode, $finalizeSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertSame($reason, $finalizeSeries->getReason());
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstanceNull(): void
    {
        $series = "AAA";
        $documentTypeCode = DocumentTypeCode::NC;
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
        $this->assertSame($documentTypeCode, $finalizeSeries->getDocumentTypeCode());
        $this->assertSame($seriesValidationCode, $seriesValidationCode);
        $this->assertSame($lastSequenceDocNumber, $finalizeSeries->getLastSequenceDocNumber());
        $this->assertNull($finalizeSeries->getReason());

    }

}
