<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;

/**
 * Operation Result Information Test
 */
class OperationResultInformationTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(OperationResultInformation::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $resultCode = 999;
        $resultMessage = "result message";

        $operationResultInformation = new OperationResultInformation(
            $resultCode, $resultMessage
        );

        $this->assertSame($resultCode, $operationResultInformation->getOperationResultCode());
        $this->assertSame($resultMessage, $operationResultInformation->getOperationResultMessage());

    }

}
