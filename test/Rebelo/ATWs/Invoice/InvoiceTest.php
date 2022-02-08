<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class InvoiceTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Invoice::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceNationalCustomer(): void
    {
        foreach (["FT", "FR", "FS", "NC", "ND", "DC"] as $k => $invoiceType) {
            $taxRegistrationNumber = "555555550";
            $invoiceNo             = $invoiceType . " A/9";
            $invoiceDate           = new Date();
            $invoiceStatus         = "N";
            $eaeCode               = $k % 2 === 0 ? null : "99999";
            $customerTaxID         = "999999990";
            $lines                 = [
                new Line([], 9.99, null, "IVA", "PT", 23.0, null),
            ];
            $documentTotals        = new DocumentTotals(1.00, 2.99, 3.99);

            $invoice = new Invoice(
                $taxRegistrationNumber,
                $invoiceNo,
                $invoiceDate,
                $invoiceType,
                $invoiceStatus,
                $eaeCode,
                $customerTaxID,
                $lines,
                $documentTotals
            );

            $this->assertSame($taxRegistrationNumber, $invoice->getTaxRegistrationNumber());
            $this->assertSame($invoiceNo, $invoice->getInvoiceNo());
            $this->assertSame($invoiceDate, $invoice->getInvoiceDate());
            $this->assertSame($invoiceType, $invoice->getInvoiceType());
            $this->assertSame($invoiceStatus, $invoice->getInvoiceStatus());
            $this->assertSame($eaeCode, $invoice->getEacCode());
            $this->assertSame($customerTaxID, $invoice->getCustomerID());
            $this->assertSame($lines, $invoice->getLines());
            $this->assertSame($documentTotals, $invoice->getDocumentTotals());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceInternationalCustomer(): void
    {
        $taxRegistrationNumber = "555555550";
        $invoiceNo             = "FT A/9";
        $invoiceDate           = new Date();
        $invoiceType           = "FT";
        $invoiceStatus         = "N";
        $eacCode               = "99999";
        $customerTaxID         = new InternationalCustomerTaxID("99999999", "ES");
        $lines                 = [
            new Line(null, 9.99, null, "IVA", "PT", 23.0, null),
        ];
        $documentTotals        = new DocumentTotals(1.00, 2.99, 3.99);

        $invoice = new Invoice(
            $taxRegistrationNumber,
            $invoiceNo,
            $invoiceDate,
            $invoiceType,
            $invoiceStatus,
            $eacCode,
            $customerTaxID,
            $lines,
            $documentTotals
        );

        $this->assertSame($taxRegistrationNumber, $invoice->getTaxRegistrationNumber());
        $this->assertSame($invoiceNo, $invoice->getInvoiceNo());
        $this->assertSame($invoiceDate, $invoice->getInvoiceDate());
        $this->assertSame($invoiceType, $invoice->getInvoiceType());
        $this->assertSame($invoiceStatus, $invoice->getInvoiceStatus());
        $this->assertSame($eacCode, $invoice->getEacCode());
        $this->assertSame($customerTaxID, $invoice->getCustomerID());
        $this->assertSame($lines, $invoice->getLines());
        $this->assertSame($documentTotals, $invoice->getDocumentTotals());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceWrongInvoiceType(): void
    {
        $taxRegistrationNumber = "555555550";
        $invoiceNo             = "FT A/9";
        $invoiceDate           = new Date();
        $invoiceType           = "FA";
        $invoiceStatus         = "N";
        $customerTaxID         = new InternationalCustomerTaxID("99999999", "ES");
        $lines                 = [
            new Line(null, 9.99, null, "IVA", "PT", 23.0, null),
        ];
        $documentTotals        = new DocumentTotals(1.00, 2.99, 3.99);

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Invoice type only can be 'FT', 'FR', 'FS', 'NC', 'ND'");

        new Invoice(
            $taxRegistrationNumber,
            $invoiceNo,
            $invoiceDate,
            $invoiceType,
            $invoiceStatus,
            null,
            $customerTaxID,
            $lines,
            $documentTotals
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceWrongInvoiceStatus(): void
    {
        $taxRegistrationNumber = "555555550";
        $invoiceNo             = "FT A/9";
        $invoiceDate           = new Date();
        $invoiceType           = "FR";
        $invoiceStatus         = "F";
        $customerTaxID         = new InternationalCustomerTaxID("99999999", "ES");
        $lines                 = [
            new Line([], 9.99, null, "IVA", "PT", 23.0, null),
        ];
        $documentTotals        = new DocumentTotals(1.00, 2.99, 3.99);

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Invoice status only can be 'A', 'N'");

        new Invoice(
            $taxRegistrationNumber,
            $invoiceNo,
            $invoiceDate,
            $invoiceType,
            $invoiceStatus,
            null,
            $customerTaxID,
            $lines,
            $documentTotals
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceWrongEACCode(): void
    {
        foreach (["9999", "999999"] as $eacCode) {

            $taxRegistrationNumber = "555555550";
            $invoiceNo             = "FT A/9";
            $invoiceDate           = new Date();
            $invoiceType           = "FR";
            $invoiceStatus         = "N";
            $customerTaxID         = new InternationalCustomerTaxID("99999999", "ES");
            $lines                 = [
                new Line([], 9.99, null, "IVA", "PT", 23.0, null),
            ];
            $documentTotals        = new DocumentTotals(1.00, 2.99, 3.99);

            try {

                new Invoice(
                    $taxRegistrationNumber,
                    $invoiceNo,
                    $invoiceDate,
                    $invoiceType,
                    $invoiceStatus,
                    $eacCode,
                    $customerTaxID,
                    $lines,
                    $documentTotals
                );
            } catch (ATWsException $e) {
                $this->assertInstanceOf(ATWsException::class, $e);
                $this->assertSame("Invoice EACCode must respect the regexp ^[0-9]{5}$", $e->getMessage());
                continue;
            }
            $this->fail("Wrong EACCode should throw ATWsException");
        }
    }

}
