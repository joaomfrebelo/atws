<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 *
 * Test for OrderReference class
 *
 * @author João Rebelo
 */
class OrderReferenceTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(OrderReference::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(): void
    {
        $originatingON = "FT A/999";
        $date          = new Date();

        $orderReference = new OrderReference($originatingON, $date);

        $this->assertSame($originatingON, $orderReference->getOriginatingON());
        $this->assertSame($date, $orderReference->getOrderDate());
    }
}
