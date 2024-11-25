<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * class Work Document
 * @author João Rebelo
 */
class WorkDocumentTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(WorkDocument::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {

        $taxRegistrationNumber     = "555555555";
        $taxEntity                 = "Global";
        $softwareCertificateNumber = 9999;

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

        $workData = new WorkData(
            $workHeader,
            $documentStatus,
            $hashCharacters,
            null,
            $systemEntryDate,
            $lines,
            $documentTotals
        );

        foreach ([new RecordChannel("System TUX", "9.9.9"), null] as $recordChannel) {
            $workDocument = new WorkDocument(
                $taxRegistrationNumber,
                $taxEntity,
                $softwareCertificateNumber,
                $workData,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $workDocument->getTaxRegistrationNumber());
            $this->assertSame($taxEntity, $workDocument->getTaxEntity());
            $this->assertSame($softwareCertificateNumber, $workDocument->getSoftwareCertificateNumber());
            $this->assertSame($workData, $workDocument->getWorkData());
            $this->assertSame($recordChannel, $workDocument->getRecordChannel());
        }
    }

}
