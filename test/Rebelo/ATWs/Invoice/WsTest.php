<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class WsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Ws::class);
        $this->assertTrue(true);
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testSubmission(): void
    {

        $invoice = new Invoice(
            static::$taxRegistrationNumber,
            "FT A/" . (new Date())->getTimestamp(), //guaranty a unique number for serial
            new Date(),
            "FT",
            "N",
            static::$taxRegistrationNumber,
            [
                new Line(9.99, null, "IVA", "PT", 23.0, null),
            ],
            new DocumentTotals(1.00, 2.99, 3.99)
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($invoice);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertNotEmpty($response->getMessage());
    }

}
