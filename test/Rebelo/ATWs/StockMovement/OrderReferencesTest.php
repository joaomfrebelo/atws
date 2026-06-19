<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\OrderReference;
use Rebelo\Base;

/**
 * Class OrderReferencesTest
 *
 * @author João Rebelo
 */
class OrderReferencesTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(OrderReference::class))->testReflection(OrderReferences::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    #[Test]
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
