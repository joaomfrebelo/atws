<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class LineTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $taxExemptionCode = null;

        foreach (['D', 'C'] as $k => $debitCreditIndicator) {

            if ($k === 0) {
                $totalTaxBase = 9.99;
                $amount       = null;
            } else {
                $totalTaxBase = null;
                $amount       = 9.99;
            }

            $line = new Line(
                $orderReference,
                $taxPointDate,
                $reference,
                $debitCreditIndicator,
                $totalTaxBase,
                $amount,
                $tax,
                $taxExemptionCode
            );

            $this->assertSame($orderReference, $line->getOrderReference());
            $this->assertSame($taxPointDate, $line->getTaxPointDate());
            $this->assertSame($reference, $line->getReference());
            $this->assertSame($debitCreditIndicator, $line->getDebitCreditIndicator());
            $this->assertSame($totalTaxBase, $line->getTotalTaxBase());
            $this->assertSame($amount, $line->getAmount());
            $this->assertSame($tax, $line->getTax());
            $this->assertSame($taxExemptionCode, $line->getTaxExemptionCode());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongDebitCredit(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Debit Credit indicator must be on of 'D', 'C'");

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $taxExemptionCode = null;
        $totalTaxBase     = 9.99;
        $amount           = null;

        new Line(
            $orderReference,
            $taxPointDate,
            $reference,
            "A",
            $totalTaxBase,
            $amount,
            $tax,
            $taxExemptionCode
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxTotalBaseAndAmountBothSet(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("TotalTaxBase and Amount cannot be both set at same time");

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $taxExemptionCode = null;
        $totalTaxBase     = 9.99;
        $amount           = 99.99;

        new Line(
            $orderReference,
            $taxPointDate,
            $reference,
            "C",
            $totalTaxBase,
            $amount,
            $tax,
            $taxExemptionCode
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxTotalBaseAndAmountBothNull(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("One of TotalTaxBase or Amount must be set");

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IVA", "PT", "NOR", 23.00, null
        );
        $taxExemptionCode = null;
        $totalTaxBase     = null;
        $amount           = null;

        new Line(
            $orderReference,
            $taxPointDate,
            $reference,
            "C",
            $totalTaxBase,
            $amount,
            $tax,
            $taxExemptionCode
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxExemptionCodeNullWithTaxExemptionVatIse(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Tax exemption TaxExemptionCode must be set");

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IVA", "PT", "ISE", 0.00, null
        );
        $taxExemptionCode = null;
        $totalTaxBase     = 9.99;
        $amount           = null;

        new Line(
            $orderReference,
            $taxPointDate,
            $reference,
            "C",
            $totalTaxBase,
            $amount,
            $tax,
            $taxExemptionCode
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxExemptionCodeNullWithTaxExemptionTotalAmountZero(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Tax exemption TaxExemptionCode must be set");

        $orderReference   = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate     = new Date();
        $reference        = ["FT 999/999"];
        $tax              = new Tax(
            "IS", "PT", "OUT", null, 0.0
        );
        $taxExemptionCode = null;
        $totalTaxBase     = 9.99;
        $amount           = null;

        new Line(
            $orderReference,
            $taxPointDate,
            $reference,
            "C",
            $totalTaxBase,
            $amount,
            $tax,
            $taxExemptionCode
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxExemptionCodeWrongFormat(): void
    {

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Wrong tax exemption code format");

        $orderReference = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate   = new Date();
        $reference      = ["FT 999/999"];
        $tax            = new Tax(
            "IS", "PT", "OUT", null, 0.0
        );

        $totalTaxBase = 9.99;
        $amount       = null;

        foreach (["A99", "M999"] as $taxExemptionCode) {

            new Line(
                $orderReference,
                $taxPointDate,
                $reference,
                "C",
                $totalTaxBase,
                $amount,
                $tax,
                $taxExemptionCode
            );
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxExemptionCodeNotSet(): void
    {
        $orderReference = [new OrderReference("FT 9/99999", new Date())];
        $taxPointDate   = new Date();
        $reference      = ["FT 999/999"];

        $taxExemptionCode = "M99";
        $totalTaxBase     = 9.99;
        $amount           = null;

        foreach (range(0, 1) as $n) {

            $tax = new Tax(
                "IVA", "PT", "NOR",
                $n === 0 ? 23.0 : null,
                $n === 0 ? null : 9.99
            );

            try {
                new Line(
                    $orderReference,
                    $taxPointDate,
                    $reference,
                    "C",
                    $totalTaxBase,
                    $amount,
                    $tax,
                    $taxExemptionCode
                );

                $this->fail("Should throw ATWsException");
            } catch (\Throwable $e) {
                $this->assertInstanceOf(ATWsException::class, $e);
                $this->assertSame(
                    "Tax exemption TaxExemptionCode cannot be set",
                    $e->getMessage()
                );
            }
        }
    }

}
