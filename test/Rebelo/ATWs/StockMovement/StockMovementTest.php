<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class StockMovementTest
 *
 * @author João Rebelo
 */
class StockMovementTest extends TestCase
{

    /**
     * @return void
     */
    public function testReflection(): void
    {
        (new Base(StockMovement::class))->testReflection(
            StockMovement::class
        );
        $this->assertTrue(true);
    }

    /**
     *
     * @return array
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public static function provider(): array
    {
        $companyAddress = new Address(
            "Rua da Escola Gerais",
            "Lisboa",
            "1999-999"
        );

        $addressTo = new Address(
            "Beco da Hera - Rua do Salvador",
            "Lisboa",
            "1999-999"
        );

        $addressFrom = new Address(
            "Rua de São Miguel",
            "Lisboa",
            "1999-999"
        );

        $customerAddr = new Address(
            "Quinta de Santo António",
            "Costa da Caparica",
            "1999-999"
        );

        $base = [
            "taxRegistrationNumber" => "594239427",
            "atcud"                 => "ABCDEF-999",
            "companyName"           => "The Company name",
            "companyAddress"        => $companyAddress,
            "documentNumber"        => "GTA 9999/29",
            "ATDocCodeID"           => "999999",
            "movementStatus"        => "N",
            "movementDate"          => new Date(),
            "movementType"          => "GR",
            "customerTaxID"         => "540359700",
            "supplierTaxID"         => null,
            "customerName"          => "Customer fresquinho",
            "customerAddress"       => $customerAddr,
            "addressTo"             => $addressTo,
            "addressFrom"           => $addressFrom,
            "movementEndTime"       => (new Date())->addHours(2),
            "movementStartTime"     => (new Date())->addHours(1),
            "vehicleID"             => "99 AA 99",
            "lines"                 => [
                new Line("Product description", 999.99, "UN", 9.99, ["GR A/1"])
            ]
        ];

        return [
            $base,
            \array_merge(
                $base,
                [
                    "ATDocCodeID"     => null,
                    "customerTaxID"   => null,
                    "movementStatus"  => "T",
                    "movementType"    => "GT",
                    "supplierTaxID"   => "541507150",
                    "customerName"    => null,
                    "customerAddress" => null,
                    "addressTo"       => null,
                    "movementEndTime" => null,
                    "vehicleID"       => null,
                ]
            ),
            \array_merge($base, ["movementStatus" => "A"]),
            \array_merge($base, ["movementType"   => "GA"]),
            \array_merge($base, ["movementType"   => "GC"]),
            \array_merge($base, ["movementType"   => "GD"]),
        ];
    }

    /**
     *
     * @param string       $taxRegistrationNumber
     * @param string       $atcud
     * @param string       $companyName
     * @param Address      $companyAddress
     * @param string       $documentNumber
     * @param string|null  $ATDocCodeID
     * @param string       $movementStatus
     * @param Date         $movementDate
     * @param string       $movementType
     * @param string|null  $customerTaxID
     * @param string|null  $supplierTaxID
     * @param string|null  $customerName
     * @param Address|null $customerAddress
     * @param Address|null $addressTo
     * @param Address      $addressFrom
     * @param Date|null    $movementEndTime
     * @param Date         $movementStartTime
     * @param string|null  $vehicleID
     * @param array        $lines
     *
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    #[DataProvider("provider")]
    public function testInstance(
        string   $taxRegistrationNumber,
        string   $atcud,
        string   $companyName,
        Address  $companyAddress,
        string   $documentNumber,
        ?string  $ATDocCodeID,
        string   $movementStatus,
        Date     $movementDate,
        string   $movementType,
        ?string  $customerTaxID,
        ?string  $supplierTaxID,
        ?string  $customerName,
        ?Address $customerAddress,
        ?Address $addressTo,
        Address  $addressFrom,
        ?Date    $movementEndTime,
        Date     $movementStartTime,
        ?string  $vehicleID,
        array    $lines = []
    ): void
    {
        $stockMovement = new StockMovement(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            $ATDocCodeID,
            $movementStatus,
            $movementDate,
            $movementType,
            $customerTaxID,
            $supplierTaxID,
            $customerName,
            $customerAddress,
            $addressTo,
            $addressFrom,
            $movementEndTime,
            $movementStartTime,
            $vehicleID,
            $lines
        );

        $this->assertSame(
            $taxRegistrationNumber, $stockMovement->getTaxRegistrationNumber()
        );
        $this->assertSame(
            $atcud, $stockMovement->getAtcud()
        );
        $this->assertSame($companyName, $stockMovement->getCompanyName());
        $this->assertSame($companyAddress, $stockMovement->getCompanyAddress());
        $this->assertSame($documentNumber, $stockMovement->getDocumentNumber());
        $this->assertSame($ATDocCodeID, $stockMovement->getATDocCodeID());
        $this->assertSame($movementStatus, $stockMovement->getMovementStatus());
        $this->assertSame($movementDate, $stockMovement->getMovementDate());
        $this->assertSame($movementStartTime, $stockMovement->getMovementStartTime());
        $this->assertSame($customerTaxID, $stockMovement->getCustomerTaxID());
        $this->assertSame($supplierTaxID, $stockMovement->getSupplierTaxID());
        $this->assertSame($customerName, $stockMovement->getCustomerName());
        $this->assertSame($customerAddress, $stockMovement->getCustomerAddress());
        $this->assertSame($addressTo, $stockMovement->getAddressTo());
        $this->assertSame($addressFrom, $stockMovement->getAddressFrom());
        $this->assertSame($movementEndTime, $stockMovement->getMovementEndTime());
        $this->assertSame($movementStartTime, $stockMovement->getMovementStartTime());
        $this->assertSame($vehicleID, $stockMovement->getVehicleID());
        $this->assertSame($lines, $stockMovement->getLines());
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testWrongMovementType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementType only can be 'GR', 'GT', 'GA', 'GC', 'GD'");
        new StockMovement(
            "594239427",
            "ABCDEF-" . \rand(999, 99999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "999999",
            "N",
            new Date(),
            "FT",
            "540359700",
            null,
            "Customer fresquinho",
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            (new Date())->addHours(2),
            (new Date())->addHours(1),
            "99 AA 99",
            [
                new Line("Product description", 999.99, "UN", 9.99, ["GR A/1"])
            ]
        );
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testWrongMovementStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementStatus only can be 'N', 'T', 'A'");
        new StockMovement(
            "594239427",
            "ABCDEF-" . \rand(999, 99999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "999999",
            "M",
            new Date(),
            "GT",
            "540359700",
            null,
            "Customer fresquinho",
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            (new Date())->addHours(2),
            (new Date())->addHours(1),
            "99 AA 99",
            [
                new Line("Product description", 999.99, "UN", 9.99, ["GR A/1"])
            ]
        );
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testSupplierAndCustomerSetToNull(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SupplierTaxID and CustomerTaxID can not be mull at same time");
        new StockMovement(
            "594239427",
            "ABCDEF-" . \rand(999, 99999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "999999",
            "N",
            new Date(),
            "GT",
            null,
            null,
            "Customer fresquinho",
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            (new Date())->addHours(2),
            (new Date())->addHours(1),
            "99 AA 99",
            [
                new Line("Product description", 999.99, "UN", 9.99, ["GR A/1"])
            ]
        );
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testSupplierAndCustomerSetToNotNull(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("SupplierTaxID and CustomerTaxID can not be set at same time");
        new StockMovement(
            "594239427",
            "ABCDEF-" . \rand(999, 99999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "999999",
            "N",
            new Date(),
            "GT",
            "516644440",
            "520749847",
            "Customer fresquinho",
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            new Address("Rua A", "Lisboa", "9999-999"),
            (new Date())->addHours(2),
            (new Date())->addHours(1),
            "99 AA 99",
            [
                new Line("Product description", 999.99, "UN", 9.99, ["GR A/1"])
            ]
        );
    }
}
