<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Delete payment Test
 * @author João Rebelo
 */
class DeletePaymentTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeletePayment::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function deletePaymentData(): array
    {
        $data = [];

        $data[] = [
            "555555555",
            [
                new PaymentHeader("RC A/999", "0", new Date(), "RC", "999999990", "KR"),
            ],
            null,
            "Test framework",
            null,
        ];

        $data[] = [
            "555555555",
            [],
            new DateRange(new Date(), new Date()),
            "Test framework",
            new RecordChannel("System TUX", "9.9.9"),
        ];

        $data[] = [
            "555555555",
            null,
            new DateRange(new Date(), new Date()),
            "Test framework",
            new RecordChannel("System TUX", "9.9.9"),
        ];

        return $data;
    }

    /**
     * @test
     * @dataProvider deletePaymentData
     * @param string                                           $taxRegistrationNumber
     * @param array|null                                       $documentList
     * @param \Rebelo\ATWs\EFaturaMDVersion\DateRange|null     $dateRange
     * @param string                                           $reason
     * @param \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null $recordChannel
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(
        string         $taxRegistrationNumber,
        ?array         $documentList,
        ?DateRange     $dateRange,
        string         $reason,
        ?RecordChannel $recordChannel
    ): void
    {

        $deletePayment = new DeletePayment(
            $taxRegistrationNumber,
            $documentList,
            $dateRange,
            $reason,
            $recordChannel
        );

        $this->assertSame($taxRegistrationNumber, $deletePayment->getTaxRegistrationNumber());
        $this->assertSame($documentList, $deletePayment->getDocumentList());
        $this->assertSame($dateRange, $deletePayment->getDateRange());
        $this->assertSame($reason, $deletePayment->getReason());
        $this->assertSame($recordChannel, $deletePayment->getRecordChannel());

    }


}
