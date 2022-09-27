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
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\OrderReference;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\EFaturaMDVersion\WithholdingTax;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Invoice Data Test
 *
 * @author João Rebelo
 */
class InvoiceDataTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(InvoiceData::class);
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

        $invoiceType          = "FT";
        $invoiceNo            = "FT 9/999";
        $atcud                = "0";
        $invoiceDate          = new Date();
        $invoiceStatus        = "N";
        $invoiceStatusDate    = new Date();
        $customerTaxID        = "999999990";
        $customerTaxIdCountry = "KR";
        $hash                 = "ABCD";
        $eacCode              = "99999";
        $systemEntryDate      = new Date();
        $documentTotals       = new DocumentTotals(1.00, 2.99, 3.99);
        $withholdingTax       = [
            new WithholdingTax("IRS", 999.99),
        ];
        $tax                  = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );

        $status = new InvoiceStatus($invoiceStatus, $invoiceStatusDate);

        $lines = [
            new Line(
                [
                    new OrderReference("FT 9/99999", new Date()),
                ],
                new Date(),
                null,
                "C",
                null,
                1000.00,
                $tax,
                null
            ),
        ];

        $header = new InvoiceHeader(
            $invoiceNo,
            $atcud,
            $invoiceDate,
            $invoiceType,
            false,
            $customerTaxID,
            $customerTaxIdCountry
        );

        $invoiceData = new InvoiceData(
            $header,
            $status,
            $hash,
            false,
            false,
            $eacCode,
            $systemEntryDate,
            $lines,
            $documentTotals,
            $withholdingTax
        );

        $this->assertSame($invoiceStatus, $invoiceData->getDocumentStatus()->getInvoiceStatus());
        $this->assertSame($invoiceStatusDate, $invoiceData->getDocumentStatus()->getInvoiceStatusDate());
        $this->assertSame($hash, $invoiceData->getHashCharacters());
        $this->assertSame(false, $invoiceData->getCashVATSchemeIndicator());
        $this->assertSame($eacCode, $invoiceData->getEacCode());
        $this->assertSame($systemEntryDate, $invoiceData->getSystemEntryDate());
        $this->assertSame($lines, $invoiceData->getLines());
        $this->assertSame($documentTotals, $invoiceData->getDocumentTotals());
        $this->assertSame($withholdingTax, $invoiceData->getWithholdingTax());
        $this->assertSame(false, $invoiceData->getPaperLessIndicator());

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
        $this->expectExceptionMessage("Invoice EACCode must respect the regexp ^[0-9]{5}$");

        $invoiceType          = "FT";
        $invoiceNo            = "FT 9/999";
        $atcud                = "0";
        $invoiceDate          = new Date();
        $invoiceStatus        = "N";
        $invoiceStatusDate    = new Date();
        $customerTaxID        = "999999990";
        $customerTaxIdCountry = "KR";
        $hash                 = "ABCD";
        $eacCode              = "9999";
        $systemEntryDate      = new Date();
        $documentTotals       = new DocumentTotals(1.00, 2.99, 3.99);
        $withholdingTax       = [
            new WithholdingTax("IRS", 999.99),
        ];
        $tax                  = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );

        $status = new InvoiceStatus($invoiceStatus, $invoiceStatusDate);

        $lines = [
            new Line(
                [
                    new OrderReference("FT 9/99999", new Date()),
                ],
                new Date(),
                null,
                "C",
                null,
                1000.00,
                $tax,
                null
            ),
        ];

        $header = new InvoiceHeader(
            $invoiceNo,
            $atcud,
            $invoiceDate,
            $invoiceType,
            false,
            $customerTaxID,
            $customerTaxIdCountry
        );

        new InvoiceData(
            $header,
            $status,
            $hash,
            false,
            false,
            $eacCode,
            $systemEntryDate,
            $lines,
            $documentTotals,
            $withholdingTax
        );

    }

}
