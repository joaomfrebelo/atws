<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 *
 * @author João Rebelo
 */
class PaymentStatusTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(PaymentStatus::class))->testReflection(PaymentStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testInstance(): void
    {
        foreach (['N', 'A'] as $status) {
            $statusDate    = new Date();
            $paymentStatus = new PaymentStatus(
                $status, $statusDate
            );

            $this->assertSame($status, $paymentStatus->getPaymentStatus());
            $this->assertSame($statusDate, $paymentStatus->getPaymentStatusDate());
        }
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWrongStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("PaymentStatus must be one of 'N', 'A'");
        new PaymentStatus("F", new Date());
    }

}
