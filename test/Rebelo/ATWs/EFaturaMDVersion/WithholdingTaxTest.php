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
 * Class WithholdingTax Test
 *
 * @author João Rebelo
 */
class WithholdingTaxTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(WithholdingTax::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        foreach (["IRS", "IRC", "IS"] as $k => $taxType) {

            $withholdingTaxAmount = $k === 0 ? 0.0 : 09.99;

            $withholding = new WithholdingTax($taxType, $withholdingTaxAmount);
            $this->assertSame($taxType, $withholding->getWithholdingTaxType());
            $this->assertSame($withholdingTaxAmount, $withholding->getWithholdingTaxAmount());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongTaxType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Withholding Tax Type only allow 'IRS', 'IRC', 'IS'");
        new WithholdingTax("IVA", 9.99);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testTaxAmountLessThanZero(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Withholding Tax Amount cannot be less than zero");
        new WithholdingTax("IRS", -0.01);
    }

}
