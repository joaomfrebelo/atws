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
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Invoice Header Test
 *
 * @author João Rebelo
 */
class InvoiceHeaderTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(InvoiceHeader::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testHeader(): void
    {
        foreach (['FT', 'FR', 'FS', 'NC', 'ND', 'RP', 'RE', 'CS', 'LD', 'RA'] as $k => $invoiceType) {
            $invoiceNo            = "FT 9/999";
            $atcud                = "0";
            $invoiceDate          = new Date();
            $selfBillingIndicator = $k % 2 === 0;
            $customerTaxID        = "999999990";
            $customerTaxIdCountry = $k % 2 === 0 ? "Desconhecido" : "KR";

            $header = new InvoiceHeader(
                $invoiceNo,
                $atcud,
                $invoiceDate,
                $invoiceType,
                $selfBillingIndicator,
                $customerTaxID,
                $customerTaxIdCountry
            );

            $this->assertSame($invoiceNo, $header->getInvoiceNo());
            $this->assertSame($atcud, $header->getAtcud());
            $this->assertSame($invoiceDate, $header->getInvoiceDate());
            $this->assertSame($invoiceType, $header->getInvoiceType());
            $this->assertSame($selfBillingIndicator, $header->getSelfBillingIndicator());
            $this->assertSame($customerTaxID, $header->getCustomerTaxID());
            $this->assertSame($customerTaxIdCountry, $header->getCustomerTaxIDCountry());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceWrongInvoiceType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Invoice type only can be 'FT', 'FR', 'FS', 'NC', 'ND', 'RP', 'RE', 'CS', 'LD', 'RA'");

        new InvoiceHeader(
            "FT 9/999",
            "0",
            new Date(),
            "DC",
            false,
            "999999990",
            "KR"
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstanceWrongCustomerTaxIdCountry(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Wrong format for CustomerTaxIdCountry");

        new InvoiceHeader(
            "FT 9/999",
            "0",
            new Date(),
            "FT",
            false,
            "999999990",
            "KOR"
        );
    }

}
