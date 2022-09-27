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

/**
 * Class TaxTest
 *
 * @author João Rebelo
 */
class TaxTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Tax::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {

        $taxType          = "IVA";
        $taxCountryRegion = "PT";
        $taxCode          = "NOR";
        $taxPercentage    = 23.0;
        $totalTaxAmount   = null;

        $tax = new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

        $this->assertSame($taxType, $tax->getTaxType());
        $this->assertSame($taxCountryRegion, $tax->getTaxCountryRegion());
        $this->assertSame($taxCode, $tax->getTaxCode());
        $this->assertSame($taxPercentage, $tax->getTaxPercentage());
        $this->assertSame($totalTaxAmount, $tax->getTotalTaxAmount());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Tax Type only can be on of 'IVA', 'IS', 'NS'");

        $taxType          = "VAT";
        $taxCountryRegion = "PT";
        $taxCode          = "NOR";
        $taxPercentage    = 23.0;
        $totalTaxAmount   = null;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxCountryRegion(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Wrong Tax Country Region");

        $taxType          = "IVA";
        $taxCountryRegion = "KOR";
        $taxCode          = "NOR";
        $taxPercentage    = 23.0;
        $totalTaxAmount   = null;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxPercentageAndTaxAmountSetToNull(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Fields TaxPercentage and TotalTaxAmount are mutual exclusive, only one must be set");

        $taxType          = "IVA";
        $taxCountryRegion = "KR";
        $taxCode          = "NOR";
        $taxPercentage    = null;
        $totalTaxAmount   = null;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxPercentageAndTaxAmountSetToNotNull(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Fields TaxPercentage and TotalTaxAmount are mutual exclusive, only one can be set");

        $taxType          = "IVA";
        $taxCountryRegion = "KR";
        $taxCode          = "NOR";
        $taxPercentage    = 9.99;
        $totalTaxAmount   = 999.99;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxPercentageGreaterThan100(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("TaxPercentage cannot be lower than zero and greater than 100");

        $taxType          = "IVA";
        $taxCountryRegion = "KR";
        $taxCode          = "NOR";
        $taxPercentage    = 100.01;
        $totalTaxAmount   = null;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxPercentageLessThanZero(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("TaxPercentage cannot be lower than zero and greater than 100");

        $taxType          = "IVA";
        $taxCountryRegion = "KR";
        $taxCode          = "NOR";
        $taxPercentage    = -0.01;
        $totalTaxAmount   = null;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxAmountLessThanZero(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("TaxAmount cannot be lower than zero");

        $taxType          = "IVA";
        $taxCountryRegion = "KR";
        $taxCode          = "NOR";
        $taxPercentage    = null;
        $totalTaxAmount   = -0.01;

        new Tax(
            $taxType,
            $taxCountryRegion,
            $taxCode,
            $taxPercentage,
            $totalTaxAmount
        );

    }

}
