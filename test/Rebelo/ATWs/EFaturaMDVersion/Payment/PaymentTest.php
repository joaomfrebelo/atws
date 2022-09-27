<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * @author João Rebelo
 */
class PaymentTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Payment::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function paymentDataProvider(): array
    {
        $data = [];

        $paymentData = new PaymentData(
            new PaymentHeader(
                "RC A/999", "0", new Date(), "RC", "999999990", "KR"
            ),
            new PaymentStatus("N", new Date()),
            new Date(),
            [],
            new DocumentTotals(9.99, 9.99, 9.99),
            null
        );

        $data[] = [
            "555555555",
            "Global",
            9999,
            $paymentData,
            null,
        ];

        $data[] = [
            "555555555",
            "Global",
            9999,
            $paymentData,
            new RecordChannel("System TUX", "9.9.9"),
        ];

        return $data;
    }

    /**
     * @test
     * @dataProvider paymentDataProvider
     * @param string                                            $taxRegistrationNumber
     * @param string                                            $taxEntity
     * @param int                                               $softwareCertificateNumber
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentData $paymentData
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null  $recordChannel
     * @return void
     */
    public function testInstance(
        string         $taxRegistrationNumber,
        string         $taxEntity,
        int            $softwareCertificateNumber,
        PaymentData    $paymentData,
        ?RecordChannel $recordChannel
    ): void
    {
        $payment = new Payment(
            $taxRegistrationNumber,
            $taxEntity,
            $softwareCertificateNumber,
            $paymentData,
            $recordChannel
        );

        $this->assertSame($taxRegistrationNumber, $payment->getTaxRegistrationNumber());
        $this->assertSame($taxEntity, $payment->getTaxEntity());
        $this->assertSame($softwareCertificateNumber, $payment->getSoftwareCertificateNumber());
        $this->assertSame($paymentData, $payment->getPaymentData());
        $this->assertSame($recordChannel, $payment->getRecordChannel());
    }

}
