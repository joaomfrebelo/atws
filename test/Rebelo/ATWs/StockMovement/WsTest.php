<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class WsTest extends TestCase
{
    /**
     * The AT portal credentials
     * @var array
     */
    #[ArrayShape(["username" => "string", "password" => "string"])]
    public static array $credentials = [];

    /**
     * The tax registration number extracted from the credentials username
     * @var string
     */
    public static string $taxRegistrationNumber;

    /**
     * @beforeClass
     * @throws \Exception
     */
    public static function before(): void
    {
        if (false === $credentials = \parse_ini_file(ATWS_TEST_CREDENTIALS)) {
            throw new \Exception("Fail opening credentials file . " . ATWS_TEST_CREDENTIALS);
        }
        static::$credentials = $credentials;
        [static::$taxRegistrationNumber,] = \explode("/", static::$credentials["username"]);
    }

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
    public function testStockMovementSubmission(): void
    {

        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ABCDEF-" . \rand(999, 99999),
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GT 999GT/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            "555555550",
            null,
            "Nome",
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            null,
            (new Date())->addMinutes(9),
            null,
            [new Line("Des", 9.999, "UN", 9.99)]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testStockMovementDelaySubmission(): void
    {

        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ABCDEF-" . \rand(999, 99999),
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GT 999GT/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            "555555550",
            null,
            "Nome",
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            null,
            (new Date())->addMinutes(-99),
            null,
            [new Line("Des", 9.999, "UN", 9.99)]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->getCode() < 0);
        $this->assertNotEmpty($response->getMessage());
        $this->assertNull($response->getATDocCodeID());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testStockMovementSubmissionWithNoNull(): void
    {
        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ABCDEF-" . \rand(999, 99999),
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GT GTA999/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            "555555550",
            null,
            "Nome",
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            (new Date())->addHours(9),
            (new Date())->addMinutes(9),
            "AABB99",
            [new Line("Des", 9.999, "UN", 9.99)]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testStockMovementSubmissionMultipleLines(): void
    {
        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ABCDEF-" . \rand(999, 99999),
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GT GT999/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            "555555550",
            null,
            "Nome",
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            (new Date())->addHours(9),
            (new Date())->addMinutes(9),
            "AABB99",
            [
                new Line("Des", 9.999, "UN", 9.99),
                new Line("OtehrDes", 999.9, "UN", 0.999)
            ]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testStockMovementSubmissionMultipleGD(): void
    {
        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GD GD999/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            null,
            "555555550",
            "Nome",
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            new Address("AAA", "Lisboa", "9999-999"),
            (new Date())->addHours(9),
            (new Date())->addMinutes(9),
            "AABB99",
            [
                new Line("Des", 9.999, "UN", 9.99),
                new Line("OtehrDes", 999.9, "UN", 0.999)
            ]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testStockMovementSubmissionAllNull(): void
    {
        $stockMovement = new StockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "teste",
            new Address("AAA", "Lisboa", "9999-999"),
            "GT 9GT999/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            "555555550",
            null,
            null,
            null,
            null,
            new Address("AAA", "Lisboa", "9999-999"),
            null,
            (new Date())->addMinutes(9),
            null,
            [
                new Line("Des", 9.999, "UN", 9.99),
                new Line("OtehrDes", 999.9, "UN", 0.999)
            ]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($stockMovement);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testPriorAgricultureStockMovementSubmission(): void
    {
        $agriculture = new PriorAgriculturalStockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA A999BC/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            (new Date())->addMinutes(9),
            false,
            "218434979"
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($agriculture);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testPriorAgricultureStockMovementFarmerStackTaxIDSubmission(): void
    {
        $agriculture = new PriorAgriculturalStockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA B999/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            (new Date())->addMinutes(9),
            false,
            ["218434979", "555332780"]
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($agriculture);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testPriorAgricultureStockMovementSubmissionAzores(): void
    {
        $agriculture = new PriorAgriculturalStockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA A999CD/" . (new Date())->getTimestamp(),
            null,
            "N",
            new Date(),
            "GT",
            (new Date())->addMinutes(9),
            true,
            "218434979"
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($agriculture);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(0, $response->getCode());
        $this->assertTrue(
            \str_starts_with($response->getMessage(), "OK") ||
            \str_starts_with($response->getMessage(), "Alerta:")

        );
        $this->assertNotNull($response->getATDocCodeID());
        $this->assertNotEmpty($response->getATDocCodeID());
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testSubsequentAgriculturalStockMovementSubmission(): void
    {
        $agriculture = new SubsequentAgriculturalStockMovement(
            static::$taxRegistrationNumber,
            "ATCODE-999",
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTP 9999/29",
            "N",
            new Date(),
            "GT",
            "516644440",
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            (new Date())->addDays(1),
            (new Date())->addHours(1),
            "99 AA 99",
            new OrderReferences("GRA A/999", "GR"),
            [
                new AgriculturalLine("product description", 999.99, "UN", 9.99)
            ],
        );

        $ws = new Ws(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $ws->submit($agriculture);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertIsInt($response->getCode());
        $this->assertDoesNotMatchRegularExpression("/error/i", $response->getMessage(), "Response message has error");
        $this->assertNotEmpty($response->getMessage());
    }

}
