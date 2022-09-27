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
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 *
 * @author João Rebelo
 */
class DeleteWorkDocumentTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeleteWorkDocument::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceDocumentList(): void
    {

        $taxRegistrationNumber = "555555555";
        $documentList          = [
            new WorkHeader(
                "A A/999",
                "0",
                new Date(),
                "FO",
                "999999990",
                "KR"
            ),
        ];
        $dateRange             = null;
        $reason                = "Test framework";

        foreach ([new RecordChannel("System TUX", "9.9.9"), null] as $recordChannel) {

            $delete = new DeleteWorkDocument(
                $taxRegistrationNumber,
                $documentList,
                $dateRange,
                $reason,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $delete->getTaxRegistrationNumber());
            $this->assertSame($documentList, $delete->getDocumentList());
            $this->assertSame($dateRange, $delete->getDateRange());
            $this->assertSame($reason, $delete->getReason());
            $this->assertSame($recordChannel, $delete->getRecordChannel());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstanceDateRange(): void
    {

        $taxRegistrationNumber = "555555555";
        $documentList          = null;
        $dateRange             = new DateRange(new Date(), new Date());
        $reason                = "Test framework";

        foreach ([new RecordChannel("System TUX", "9.9.9"), null] as $recordChannel) {

            $delete = new DeleteWorkDocument(
                $taxRegistrationNumber,
                $documentList,
                $dateRange,
                $reason,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $delete->getTaxRegistrationNumber());
            $this->assertSame($documentList, $delete->getDocumentList());
            $this->assertSame($dateRange, $delete->getDateRange());
            $this->assertSame($reason, $delete->getReason());
            $this->assertSame($recordChannel, $delete->getRecordChannel());
        }
    }

    /**
     * @test
     * @return void
     */
    public function testDocumentListEmptyOrNullAndDateRangeNull(): void
    {

        foreach ([[], null] as $documentList) {

            try {
                new DeleteWorkDocument(
                    "555555555",
                    $documentList,
                    null,
                    "Test framework",
                    null
                );
                $this->fail("Should throw ATWsException");
            } catch (\Throwable $e) {
                $this->assertInstanceOf(ATWsException::class, $e);
                $this->assertSame(
                    "One of DocumentList and DateRange cannot be null or empty",
                    $e->getMessage()
                );
            }
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testDocumentListNotEmptyOrNullAndDateRangeNotNull(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("One of DocumentList and DateRange must be null or empty");

        $documentList = [
            new WorkHeader(
                "A A/999",
                "0",
                new Date(),
                "FO",
                "999999990",
                "KR"
            ),
        ];

        new DeleteWorkDocument(
            "555555555",
            $documentList,
            new DateRange(new Date(), new Date()),
            "Test framework",
            null
        );

    }

}
