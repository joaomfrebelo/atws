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
 * Class LineTest
 *
 * @author João Rebelo
 */
class AgriculturalLineTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(AgriculturalLine::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $productDescription = "The product";
        $quantity = 9.99;
        $unitOfMeasure = "UN";
        $unitPrice = 499.99;

        $line = new AgriculturalLine(
            $productDescription,
            $quantity,
            $unitOfMeasure,
            $unitPrice
        );

        $this->assertSame($productDescription, $line->getProductDescription());
        $this->assertSame($quantity, $line->getQuantity());
        $this->assertSame($unitOfMeasure, $line->getUnitOfMeasure());
        $this->assertSame($unitPrice, $line->getUnitPrice());
    }

}
