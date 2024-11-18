<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * @since 2.0.0
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
     * @return array
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function lineDataProvider(): array
    {
        $data = [];

        $data[] = [
            ["FT A/999", "FT B/999"],
            0.0,
            "C",
            9.99,
            new Tax("IVA", "PT", "NOR", 23.0, null),
            null,
        ];

        $data[] = [
            ["FT A/999", "FT B/999"],
            null,
            "C",
            9.99,
            new Tax("IVA", "PT", "NOR", 23.0, null),
            null,
        ];

        $data[] = [
            ["FT A/999", "FT B/999"],
            null,
            "C",
            9.99,
            null,
            null,
        ];

        $data[] = [
            ["FT A/999",],
            9.0,
            "D",
            0.0,
            new Tax("IVA", "PT", "ISE", 0.0, null),
            null,
        ];

        return $data;
    }

    /**
     * @test
     * @dataProvider lineDataProvider
     * @param array                                  $sourceDocumentID
     * @param float|null                             $settlementAmount
     * @param string                                 $debitCreditIndicator
     * @param float                                  $amount
     * @param \Rebelo\ATWs\EFaturaMDVersion\Tax|null $tax
     * @param string|null                            $taxExemptionCode
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(
        array   $sourceDocumentID,
        ?float  $settlementAmount,
        string  $debitCreditIndicator,
        float   $amount,
        ?Tax    $tax,
        ?string $taxExemptionCode
    ): void
    {

        $line = new Line(
            $sourceDocumentID,
            $settlementAmount,
            $debitCreditIndicator,
            $amount,
            $tax,
            $taxExemptionCode
        );

        $this->assertSame($sourceDocumentID, $line->getSourceDocumentID());
        $this->assertSame($settlementAmount, $line->getSettlementAmount());
        $this->assertSame($debitCreditIndicator, $line->getDebitCreditIndicator());
        $this->assertSame($amount, $line->getAmount());
        $this->assertSame($tax, $line->getTax());
        $this->assertSame($taxExemptionCode, $line->getTaxExemptionCode());

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testEmptySourceDocumentsID(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SourceDocumentID cannot be empty");

        new Line(
            [],
            null,
            "C",
            9.99,
            null,
            null
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongDebitCreditIndicator(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Debit Credit indicator must be on of 'D', 'C'");

        new Line(
            [new SourceDocumentID("FT A/999", new Date())],
            null,
            "S",
            9.99,
            null,
            null
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testSettlementAmountLessThanZero(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SettlementAmount cannot be less than zero");

        new Line(
            [new SourceDocumentID("FT A/999", new Date())],
            -.01,
            "C",
            9.99,
            null,
            null
        );
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testAmountLessThanZero(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Amount cannot be less than zero");

        new Line(
            [new SourceDocumentID("FT A/999", new Date())],
            null,
            "C",
            -0.01,
            null,
            null
        );
    }

    /**
     * @test
     * @return void
     */
    public function testWrongTaxExemptionCode(): void
    {
        foreach (["M999", "M9", "999", "99"] as $taxExemptionCode) {
            try {
                new Line(
                    [new SourceDocumentID("FT A/999", new Date())],
                    null,
                    "C",
                    0.01,
                    null,
                    $taxExemptionCode
                );
            } catch (\Throwable $e) {
                $this->assertInstanceOf(ATWsException::class, $e);
                $this->assertSame(
                    "Wrong tax exemption code format",
                    $e->getMessage()
                );
            }
        }
    }

}
