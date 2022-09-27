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

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class RecordChannelTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(RecordChannel::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $system  = "System";
        $version = "29.9.9";

        $recordChannel = new RecordChannel($system, $version);

        $this->assertSame($system, $recordChannel->getSystem());
        $this->assertSame($version, $recordChannel->getVersion());

    }

}
