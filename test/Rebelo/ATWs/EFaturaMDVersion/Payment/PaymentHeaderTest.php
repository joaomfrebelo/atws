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
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * @author João Rebelo
 */
class PaymentHeaderTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(PaymentHeader::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(): void
    {
        $paymentRefNo    = "RC A/999";
        $atcud           = "0";
        $transactionDate = new Date();
        $paymentType     = "RC";
        $customerTaxID   = "999999990";

        foreach (["KR", "Desconhecido"] as $customerTaxIDCountry) {

            $header = new PaymentHeader(
                $paymentRefNo,
                $atcud,
                $transactionDate,
                $paymentType,
                $customerTaxID,
                $customerTaxIDCountry
            );

            $this->assertSame($paymentRefNo, $header->getPaymentRefNo());
            $this->assertSame($atcud, $header->getAtcud());
            $this->assertSame($transactionDate, $header->getTransactionDate());
            $this->assertSame($paymentType, $header->getPaymentType());
            $this->assertSame($customerTaxID, $header->getCustomerTaxID());
            $this->assertSame($customerTaxIDCountry, $header->getCustomerTaxIDCountry());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongPaymentType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("PaymentType type only can be 'RC' Cash vat payment");
        new PaymentHeader(
            "RC A/999",
            "0",
            new Date(),
            "RG",
            "999999990",
            "KR"
        );
    }

}
