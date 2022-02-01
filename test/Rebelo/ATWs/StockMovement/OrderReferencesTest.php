<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;

/**
 * Class OrderReferencesTest
 *
 * @author João Rebelo
 */
class OrderReferencesTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(OrderReferences::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $originatingOn = "GTR 1999/1";
        $movementType = "GT";

        $orderReferences = new OrderReferences(
            $originatingOn, $movementType
        );

        $this->assertSame($originatingOn, $orderReferences->getOriginatingOn());
        $this->assertSame($movementType, $orderReferences->getMovementType());
    }

}
