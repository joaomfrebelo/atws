<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Work Data Test
 *
 * @author João Rebelo
 */
class WorkDataTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(WorkData::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(): void
    {
        $workHeader      = new WorkHeader(
            "A A/999", "0", new Date(), "FO", "999999990", "KR"
        );
        $documentStatus  = new WorkStatus("N", new Date());
        $hashCharacters  = "ABCD";
        $systemEntryDate = new Date();
        $tax             = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $lines           = [new Line(
            null, new Date(), null, "C", 999.99, null, $tax, null
        )];
        $documentTotals  = new DocumentTotals(230.00, 999.99, 1229.99);

        foreach (["99999", null] as $eacCode) {
            $workData = new WorkData(
                $workHeader,
                $documentStatus,
                $hashCharacters,
                $eacCode,
                $systemEntryDate,
                $lines,
                $documentTotals
            );

            $this->assertSame($workHeader, $workData->getWorkHeader());
            $this->assertSame($documentStatus, $workData->getDocumentStatus());
            $this->assertSame($hashCharacters, $workData->getHashCharacters());
            $this->assertSame($eacCode, $workData->getEacCode());
            $this->assertSame($systemEntryDate, $workData->getSystemEntryDate());
            $this->assertSame($lines, $workData->getLines());
            $this->assertSame($documentTotals, $workData->getDocumentTotals());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongEacCode(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("EACCode must respect the regexp ^[0-9]{5}$");

        $workHeader      = new WorkHeader(
            "A A/999", "0", new Date(), "FO", "999999990", "KR"
        );
        $documentStatus  = new WorkStatus("N", new Date());
        $hashCharacters  = "ABCD";
        $systemEntryDate = new Date();
        $tax             = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $lines           = [new Line(
            null, new Date(), null, "C", 999.99, null, $tax, null
        )];
        $documentTotals  = new DocumentTotals(230.00, 999.99, 1229.99);

        new WorkData(
            $workHeader,
            $documentStatus,
            $hashCharacters,
            "9999",
            $systemEntryDate,
            $lines,
            $documentTotals
        );
    }

}
