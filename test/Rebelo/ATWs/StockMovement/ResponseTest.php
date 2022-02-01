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
class ResponseTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Response::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Exception
     */
    public function testResponse(): void
    {
        $file = \file_get_contents(ATWS_STOCK_MOVEMENT_RESPONSE_DIR . DIRECTORY_SEPARATOR . "GTResponse.xml");
        if(false === $xml = $file){
            throw new \Exception("Fail to load file: " . $file);
        }
        $response = Response::factory($xml);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertSame("OK", $response->getMessage());
        $this->assertSame("1099214626", $response->getATDocCodeID());
    }

}
