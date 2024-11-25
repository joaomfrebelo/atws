<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\EFaturaMDVersion\WithholdingTax;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 *
 */
class PaymentDataTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(PaymentData::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function paymentDataDataProvider(): array
    {
        $data = [];

        $header = new PaymentHeader("RC A/999", "0", new Date(), "RC", "999999999", "KR");
        $status = new PaymentStatus("N", new Date());
        $totals = new DocumentTotals(0.0, 0.0, 0.0);
        $lines  = [
            new Line(
                [new SourceDocumentID("FT A/999", new Date())],
                null,
                "C",
                9.99,
                null,
                null
            ),
            new Line(
                [new SourceDocumentID("FT A/999", new Date())],
                null,
                "C",
                9.99,
                new Tax("IVA", "PT", "ISE", 0.0, null),
                "M99"
            ),
        ];

        $data[] = [
            $header, $status, new Date(), $lines, $totals, null,
        ];

        $data[] = [
            $header,
            $status, new Date(),
            $lines,
            $totals,
            [new WithholdingTax("IRS", 9.99)],
        ];

        $data[] = [
            $header,
            $status, new Date(),
            $lines,
            $totals,
            [
                new WithholdingTax("IRS", 9.99),
                new WithholdingTax("IRC", 999.99),
            ],
        ];

        return $data;
    }

    /**
     * @test
     * @dataProvider paymentDataDataProvider
     *
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader $paymentHeader
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus $paymentStatus
     * @param \Rebelo\Date\Date                                   $systemEntryDate
     * @param array                                               $lines
     * @param \Rebelo\ATWs\EFaturaMDVersion\DocumentTotals        $documentTotals
     * @param \Rebelo\ATWs\EFaturaMDVersion\WithholdingTax[]|null $withholdingTax
     *
     * @return void
     */
    public function testInstance(
        PaymentHeader  $paymentHeader,
        PaymentStatus  $paymentStatus,
        Date           $systemEntryDate,
        array          $lines,
        DocumentTotals $documentTotals,
        ?array         $withholdingTax
    ): void
    {

        $paymentData = new PaymentData(
            $paymentHeader,
            $paymentStatus,
            $systemEntryDate,
            $lines,
            $documentTotals,
            $withholdingTax
        );

        $this->assertSame($paymentHeader, $paymentData->getPaymentHeader());
        $this->assertSame($paymentStatus, $paymentData->getPaymentStatus());
        $this->assertSame($systemEntryDate, $paymentData->getSystemEntryDate());
        $this->assertSame($lines, $paymentData->getLines());
        $this->assertSame($documentTotals, $paymentData->getDocumentTotals());
        $this->assertSame($withholdingTax, $paymentData->getWithholdingTax());
    }


}
