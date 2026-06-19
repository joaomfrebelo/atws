<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Change Payment Status Test
 * @author João Rebelo
 */
class ChangePaymentStatusTest extends TestCase
{

    use TCredentials;

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(ChangePaymentStatus::class))->testReflection(ChangePaymentStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\ATWs\ATWsException
     */
    public static function changePaymentStatusData(): array
    {
        $data = [];

        $header = new PaymentHeader("RC A/999", "0", new Date(), "RC", "999999990", "KR");
        $status = new PaymentStatus("A", new Date());

        $data[] = ["555555555", $header, $status, null];
        $data[] = ["555555555", $header, $status, new RecordChannel("System TUX", "9.9.9")];

        return $data;
    }

    /**
     * @param string                                              $taxRegistrationNumber
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentHeader $header
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\PaymentStatus $newPaymentStatus
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null    $recordChannel
     * @return void
     */
    #[Test]
    #[DataProvider("changePaymentStatusData")]
    public function testInstance(
        string         $taxRegistrationNumber,
        PaymentHeader  $header,
        PaymentStatus  $newPaymentStatus,
        ?RecordChannel $recordChannel
    ): void
    {
        $changePaymentStatus = new ChangePaymentStatus(
            $taxRegistrationNumber,
            $header,
            $newPaymentStatus,
            $recordChannel
        );

        $this->assertSame($taxRegistrationNumber, $changePaymentStatus->getTaxRegistrationNumber());
        $this->assertSame($header, $changePaymentStatus->getPaymentHeader());
        $this->assertSame($newPaymentStatus, $changePaymentStatus->getNewPaymentStatus());
        $this->assertSame($recordChannel, $changePaymentStatus->getRecordChannel());
    }

}
