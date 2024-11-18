<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Work Status Test
 *
 * @author João Rebelo
 */
class WorkStatusTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(WorkStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {

        $statusDate = new Date();
        foreach (['N', 'A', 'F'] as $status) {
            $workStatus = new WorkStatus($status, $statusDate);
            $this->assertSame($status, $workStatus->getWorkStatus());
            $this->assertSame($statusDate, $workStatus->getWorkStatusDate());
        }
    }


    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWongStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("WorkStatus must be one of 'N', 'A', 'F'");
        new WorkStatus("S", new Date());
    }


}
