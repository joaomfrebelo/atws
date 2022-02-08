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
    public function testInstanceDebit(): void
    {
        $debit              = 1.99;
        $credit             = null;
        $taxType            = "IVA";
        $taxRegion          = "PT";
        $taxPercentage      = 23.0;
        $taxExemptionReason = null;

        foreach ([null, []] as $oderReference) {

            $line = new Line(
                $oderReference,
                $debit,
                $credit,
                $taxType,
                $taxRegion,
                $taxPercentage,
                $taxExemptionReason
            );

            $this->assertSame($oderReference, $line->getOrderReference());
            $this->assertSame($debit, $line->getDebitAmount());
            $this->assertSame($credit, $line->getCreditAmount());
            $this->assertSame($taxType, $line->getTaxType());
            $this->assertSame($taxRegion, $line->getTaxCountryRegion());
            $this->assertSame($taxPercentage, $line->getTaxPercentage());
            $this->assertNull($line->getTaxExemptionReason());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstanceCredit(): void
    {
        $debit = null;
        $credit = 1.99;
        $taxType = "IVA";
        $taxRegion = "PT";
        $taxPercentage = 23.0;
        $taxExemptionReason = null;

        $line = new Line(
            null,
            $debit,
            $credit,
            $taxType,
            $taxRegion,
            $taxPercentage,
            $taxExemptionReason
        );

        $this->assertSame($debit, $line->getDebitAmount());
        $this->assertSame($credit, $line->getCreditAmount());
        $this->assertSame($taxType, $line->getTaxType());
        $this->assertSame($taxRegion, $line->getTaxCountryRegion());
        $this->assertSame($taxPercentage, $line->getTaxPercentage());
        $this->assertNull($line->getTaxExemptionReason());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstanceExemptionReason(): void
    {
        $debit = null;
        $credit = 1.99;
        $taxType = "IVA";
        $taxRegion = "PT";
        $taxPercentage = 0.0;
        $taxExemptionReason = "M09";

        $line = new Line(
            null,
            $debit,
            $credit,
            $taxType,
            $taxRegion,
            $taxPercentage,
            $taxExemptionReason
        );

        $this->assertSame($debit, $line->getDebitAmount());
        $this->assertSame($credit, $line->getCreditAmount());
        $this->assertSame($taxType, $line->getTaxType());
        $this->assertSame($taxRegion, $line->getTaxCountryRegion());
        $this->assertSame($taxPercentage, $line->getTaxPercentage());
        $this->assertSame($taxExemptionReason, $line->getTaxExemptionReason());
    }

    /**
     * @test
     * @return void
     */
    public function testInstanceExemptionReasonNonTaxZero(): void
    {
        $debit = null;
        $credit = 1.99;
        $taxType = "IVA";
        $taxRegion = "PT";
        $taxPercentage = 23.0;
        $taxExemptionReason = "M09";

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage(
            "Tax exemption reason only can be set if tax percentage equal to 0.0"
        );

        new Line(
            null,
            $debit,
            $credit,
            $taxType,
            $taxRegion,
            $taxPercentage,
            $taxExemptionReason
        );
    }

    /**
     * @test
     * @return void
     */
    public function testInstanceNoExemptionReasonToTaxZero(): void
    {
        $debit = null;
        $credit = 1.99;
        $taxType = "IVA";
        $taxRegion = "PT";
        $taxPercentage = 0.0;
        $taxExemptionReason = null;

        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage(
            "Tax exemption reason must be set if tax percentage is equal to 0.0"
        );

        new Line(
            null,
            $debit,
            $credit,
            $taxType,
            $taxRegion,
            $taxPercentage,
            $taxExemptionReason
        );
    }

}
