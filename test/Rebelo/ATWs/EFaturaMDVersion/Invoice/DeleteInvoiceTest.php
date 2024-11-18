<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class DeleteInvoiceTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeleteInvoice::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstanceDocumentList(): void
    {
        $channel = new RecordChannel("System", "9.9.9");

        foreach ([null, $channel] as $recordChannel) {

            $taxRegistrationNumber = "555555550";
            $invoiceNo             = "FT 9/999";
            $atcud                 = "0";
            $invoiceDate           = new Date();
            $customerTaxID         = "999999990";
            $customerTaxIdCountry  = "KR";

            $documentList = [
                new InvoiceHeader(
                    $invoiceNo,
                    $atcud,
                    $invoiceDate,
                    "FT",
                    false,
                    $customerTaxID,
                    $customerTaxIdCountry
                ),
            ];

            $reason = "The delete reason";

            $delete = new DeleteInvoice(
                $taxRegistrationNumber,
                $documentList,
                null,
                $reason,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $delete->getTaxRegistrationNumber());
            $this->assertSame($documentList, $delete->getDocumentList());
            $this->assertSame(null, $delete->getDateRange());
            $this->assertSame($recordChannel, $delete->getRecordChannel());

        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstanceDateRange(): void
    {
        $channel = new RecordChannel("System", "9.9.9");

        foreach ([null, $channel] as $recordChannel) {

            $taxRegistrationNumber = "555555550";

            $dateRange = new DateRange(
                new Date(),
                (new Date())->addDays(1)
            );

            $reason = "The delete reason";

            $delete = new DeleteInvoice(
                $taxRegistrationNumber,
                null,
                $dateRange,
                $reason,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $delete->getTaxRegistrationNumber());
            $this->assertSame(null, $delete->getDocumentList());
            $this->assertSame($dateRange, $delete->getDateRange());
            $this->assertSame($recordChannel, $delete->getRecordChannel());

        }
    }

    /**
     * @test
     * @return void
     */
    public function testEmptyAndNullDocListWithNullDateRange(): void
    {

        try {

            foreach ([[], null] as $documentList) {

                $taxRegistrationNumber = "555555550";

                $reason = "The delete reason";

                new DeleteInvoice(
                    $taxRegistrationNumber,
                    $documentList,
                    null,
                    $reason,
                    null
                );

                $this->fail("Should throw ATWsException");
            }
        } catch (\Throwable $e) {
            $this->assertInstanceOf(ATWsException::class, $e);
            $this->assertSame(
                "One of DocumentList and DateRange cannot be null or empty",
                $e->getMessage()
            );
        }
    }


    /**
     * @test
     * @return void
     */
    public function testNotEmptyDocListWithNotNullDateRange(): void
    {

        try {

            $taxRegistrationNumber = "555555550";
            $invoiceNo             = "FT 9/999";
            $atcud                 = "0";
            $invoiceDate           = new Date();
            $customerTaxID         = "999999990";
            $customerTaxIdCountry  = "KR";

            $documentList = [
                new InvoiceHeader(
                    $invoiceNo,
                    $atcud,
                    $invoiceDate,
                    "FT",
                    false,
                    $customerTaxID,
                    $customerTaxIdCountry
                ),
            ];

            $dateRange = new DateRange(
                new Date(),
                (new Date())->addDays(1)
            );

            $reason = "The delete reason";

            new DeleteInvoice(
                $taxRegistrationNumber,
                $documentList,
                $dateRange,
                $reason,
                null
            );

            $this->fail("Should throw ATWsException");

        } catch (\Throwable $e) {
            $this->assertInstanceOf(ATWsException::class, $e);
            $this->assertSame(
                "One of DocumentList and DateRange must be null or empty",
                $e->getMessage()
            );
        }
    }

}
