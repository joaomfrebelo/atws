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
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class AddressTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testInstance(): void
    {
        (new Base(Address::class))->testReflection(Address::class);

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
