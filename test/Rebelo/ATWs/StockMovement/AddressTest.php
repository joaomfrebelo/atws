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
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class AddressTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        (new Base())->testReflection(Address::class);

        $addressDetail = "Rua da Escolas Gerais";
        $city = "Lisboa";
        $postalCode = "1999-999";

        $address = new Address(
            $addressDetail,
            $city,
            $postalCode
        );

        $this->assertInstanceOf(Address::class, $address);

        $this->assertSame($addressDetail, $address->getAddressDetail());
        $this->assertSame($city, $address->getCity());
        $this->assertSame($postalCode, $address->getPostalCode());
    }

}
