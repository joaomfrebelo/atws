<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use PHPUnit\Framework\TestCase;

/**
 * Class InternationalCustomerTaxIDTest
 *
 * @author João Rebelo
 */
class InternationalCustomerTaxIDTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        $tin = "123456789";
        $country = "ES";
        $intCustomerTin = new InternationalCustomerTaxID(
            $tin, $country
        );

        $this->assertSame($tin, $intCustomerTin->getTaxIDNumber());
        $this->assertSame($country, $intCustomerTin->getTaxIDCountry());
    }

}
