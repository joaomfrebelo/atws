<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class ChangeInvoiceStatusTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ChangeInvoiceStatus::class);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(): void
    {
        $channel = new RecordChannel("System", "1.9.9");
        foreach ([null, $channel] as $recordChannel) {
            $taxRegistrationNumber = "555555550";
            $invoiceNo             = "FT 9/999";
            $atcud                 = "0";
            $invoiceDate           = new Date();
            $invoiceStatus         = "N";
            $invoiceStatusDate     = new Date();
            $customerTaxID         = "999999990";
            $customerTaxIdCountry  = "KR";
            $invoiceType           = "FT";

            $header = new InvoiceHeader(
                $invoiceNo,
                $atcud,
                $invoiceDate,
                $invoiceType,
                false,
                $customerTaxID,
                $customerTaxIdCountry
            );

            $status = new InvoiceStatus($invoiceStatus, $invoiceStatusDate);

            $changeInvoiceStatus = new ChangeInvoiceStatus(
                $taxRegistrationNumber,
                $header,
                $status,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $changeInvoiceStatus->getTaxRegistrationNumber());
            $this->assertSame($header, $changeInvoiceStatus->getInvoiceHeader());
            $this->assertSame($status, $changeInvoiceStatus->getNewInvoiceStatus());
            $this->assertSame($recordChannel, $changeInvoiceStatus->getRecordChannel());
        }
    }

}
