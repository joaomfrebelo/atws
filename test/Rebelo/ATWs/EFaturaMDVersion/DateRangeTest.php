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
use Rebelo\Date\Date;

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class DateRangeTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DateRange::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        $startDate = new Date();
        $endDate   = new Date();

        $dateRange = new DateRange($startDate, $endDate);

        $this->assertSame($startDate, $dateRange->getStartDate());
        $this->assertSame($endDate, $dateRange->getEndDate());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testStartDateAfterEndDate(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Start date cannot be after end date");

        $startDate = (new Date())->addMinutes(1);
        $endDate   = new Date();

        new DateRange($startDate, $endDate);
    }

}
