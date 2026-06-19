<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class DateRangeTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(DateRange::class))->testReflection(DateRange::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testInstance(): void
    {
        $startDate = new Date();
        $endDate   = new Date();

        $dateRange = new DateRange($startDate, $endDate);

        $this->assertSame($startDate, $dateRange->getStartDate());
        $this->assertSame($endDate, $dateRange->getEndDate());
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testStartDateAfterEndDate(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Start date cannot be after end date");

        $startDate = (new Date())->addMinutes(1);
        $endDate   = new Date();

        new DateRange($startDate, $endDate);
    }

}
