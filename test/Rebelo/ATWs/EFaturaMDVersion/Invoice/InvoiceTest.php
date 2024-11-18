<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\OrderReference;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\EFaturaMDVersion\WithholdingTax;
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
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        foreach (['FT', 'FR', 'FS', 'NC', 'ND', 'RP', 'RE', 'CS', 'LD', 'RA'] as $k => $invoiceType) {
            $taxRegistrationNumber     = "555555550";
            $taxEntity                 = "Global";
            $softwareCertificateNumber = 9999;
            $invoiceNo                 = "FT 9/999";
            $atcud                     = "0";
            $invoiceDate               = new Date();
            $selfBillingIndicator      = $k % 2 === 0;
            $invoiceStatus             = $k % 2 === 0 ? "S" : "N";
            $invoiceStatusDate         = new Date();
            $customerTaxID             = "999999990";
            $customerTaxIdCountry      = $k % 2 === 0 ? "PT" : "KR";
            $hash                      = "ABCD";
            $cashVatIndicator          = $k % 2 === 0;
            $paperLessIndicator        = $k % 2 !== 0;
            $eacCode                   = $k % 2 === 0 ? null : "99999";
            $systemEntryDate           = new Date();
            $documentTotals            = new DocumentTotals(1.00, 2.99, 3.99);
            $withholdingTax            = [
                new WithholdingTax("IRS", 999.99),
            ];
            $tax                       = new Tax(
                "IVA", "PT", "NOR", 23.00, null
            );

            $header = new InvoiceHeader(
                $invoiceNo,
                $atcud,
                $invoiceDate,
                $invoiceType,
                $selfBillingIndicator,
                $customerTaxID,
                $customerTaxIdCountry
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

            $invoiceData = new InvoiceData(
                $header,
                $status,
                $hash,
                $cashVatIndicator,
                $paperLessIndicator,
                $eacCode,
                $systemEntryDate,
                $lines,
                $documentTotals,
                $withholdingTax
            );

            $invoice = new Invoice(
                $taxRegistrationNumber,
                $taxEntity,
                $softwareCertificateNumber,
                $invoiceData,
                null
            );

            $this->assertSame($taxRegistrationNumber, $invoice->getTaxRegistrationNumber());
            $this->assertSame($taxEntity, $invoice->getTaxEntity());
            $this->assertSame($softwareCertificateNumber, $invoice->getSoftwareCertificateNumber());

            $header = $invoice->getInvoiceData()->getInvoiceHeader();
            $this->assertSame($invoiceNo, $header->getInvoiceNo());
            $this->assertSame($atcud, $header->getAtcud());
            $this->assertSame($invoiceDate, $header->getInvoiceDate());
            $this->assertSame($invoiceType, $header->getInvoiceType());
            $this->assertSame($selfBillingIndicator, $header->getSelfBillingIndicator());
            $this->assertSame($customerTaxID, $header->getCustomerTaxID());
            $this->assertSame($customerTaxIdCountry, $header->getCustomerTaxIDCountry());

            $data = $invoice->getInvoiceData();
            $this->assertSame($invoiceStatus, $data->getDocumentStatus()->getInvoiceStatus());
            $this->assertSame($invoiceStatusDate, $data->getDocumentStatus()->getInvoiceStatusDate());
            $this->assertSame($hash, $data->getHashCharacters());
            $this->assertSame($cashVatIndicator, $data->getCashVATSchemeIndicator());
            $this->assertSame($eacCode, $data->getEacCode());
            $this->assertSame($systemEntryDate, $data->getSystemEntryDate());
            $this->assertSame($lines, $data->getLines());
            $this->assertSame($documentTotals, $data->getDocumentTotals());
            $this->assertSame($withholdingTax, $data->getWithholdingTax());

        }
    }


}
