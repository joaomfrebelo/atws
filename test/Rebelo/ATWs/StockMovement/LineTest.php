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
use Rebelo\Base;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class LineTest extends TestCase
{

    /**
     * @return void
     */
    public function testReflection(): void
    {
        (new Base(Line::class))->testReflection(Line::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstance(): void
    {
        $productDescription = "The product";
        $quantity = 9.99;
        $unitOfMeasure = "UN";
        $unitPrice = 499.99;
        $orderReferences = [
            "GR A/1", "GD A/2"
        ];

        $line = new Line(
            $productDescription,
            $quantity,
            $unitOfMeasure,
            $unitPrice,
            $orderReferences
        );

        $this->assertSame($productDescription, $line->getProductDescription());
        $this->assertSame($quantity, $line->getQuantity());
        $this->assertSame($unitOfMeasure, $line->getUnitOfMeasure());
        $this->assertSame($unitPrice, $line->getUnitPrice());
        $this->assertSame($orderReferences, $line->getOriginatingON());
    }

    /**
     * @return void
     */
    #[Test]
    public function testInstanceEmptyOrderReferences(): void
    {
        $productDescription = "The product";
        $quantity = 9.99;
        $unitOfMeasure = "UN";
        $unitPrice = 499.99;

        $line = new Line(
            $productDescription,
            $quantity,
            $unitOfMeasure,
            $unitPrice
        );

        $this->assertSame($productDescription, $line->getProductDescription());
        $this->assertSame($quantity, $line->getQuantity());
        $this->assertSame($unitOfMeasure, $line->getUnitOfMeasure());
        $this->assertSame($unitPrice, $line->getUnitPrice());
        $this->assertEmpty($line->getOriginatingON());
    }

}
