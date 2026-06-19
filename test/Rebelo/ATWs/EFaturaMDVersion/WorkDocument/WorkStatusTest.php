<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\Attributes\Test;
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
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(WorkStatus::class))->testReflection(WorkStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
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
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWongStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("WorkStatus must be one of 'N', 'A', 'F'");
        new WorkStatus("S", new Date());
    }

}
