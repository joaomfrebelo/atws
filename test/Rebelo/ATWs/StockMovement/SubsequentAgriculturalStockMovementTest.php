<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class SubsequentAgriculturalStockMovementTest
 *
 * @author João Rebelo
 */
class SubsequentAgriculturalStockMovementTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(
            SubsequentAgriculturalStockMovement::class
        );
        $this->assertTrue(true);
    }

    /**
     *
     * @return array
     */
    public function provider(): array
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

        $base = [
            "taxRegistrationNumber" => "594239427",
            "atcud" => "ABCDEF-999",
            "companyName" => "The Company name",
            "companyAddress" => $companyAddress,
            "documentNumber" => "GTA 9999/29",
            "movementStatus" => "N",
            "movementDate" => new Date(),
            "movementType" => "GR",
            "farmerTaxID" => "516644440",
            "addressTo" => $addressTo,
            "addressFrom" => $addressFrom,
            "movementEndTime" => (new Date())->addDays(1),
            "movementStartTime" => (new Date())->addHours(1),
            "vehicleID" => "99 AA 99",
            "orderReferences" => new OrderReferences("GRA A/999", "GR"),
            "lines" => [
                new AgriculturalLine("product description", 999.99, "UN", 9.99)
            ],
        ];

        return [
            $base,
            \array_merge(
                $base,
                [
                    "movementStatus" => "T",
                    "movementType" => "GT",
                    "customerName" => null,
                    "addressTo" => null,
                    "movementEndTime" => null,
                    "vehicleID" => null,
                ]
            ),
        ];
    }

    /**
     * @dataProvider provider
     * @test
     * @param string          $taxRegistrationNumber
     * @param string          $atcud
     * @param string          $companyName
     * @param Address         $companyAddress
     * @param string          $documentNumber
     * @param string          $movementStatus
     * @param Date            $movementDate
     * @param string          $movementType
     * @param string          $farmerTaxID
     * @param Address|null    $addressTo
     * @param Address         $addressFrom
     * @param Date|null       $movementEndTime
     * @param Date            $movementStartTime
     * @param string|null     $vehicleID
     * @param OrderReferences $orderReferences
     * @param array           $lines
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(
        string          $taxRegistrationNumber,
        string          $atcud,
        string          $companyName,
        Address         $companyAddress,
        string          $documentNumber,
        string          $movementStatus,
        Date            $movementDate,
        string          $movementType,
        string          $farmerTaxID,
        ?Address        $addressTo,
        Address         $addressFrom,
        ?Date           $movementEndTime,
        Date            $movementStartTime,
        ?string         $vehicleID,
        OrderReferences $orderReferences,
        array           $lines
    ): void
    {
        $atockMovement = new SubsequentAgriculturalStockMovement(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            $movementStatus,
            $movementDate,
            $movementType,
            $farmerTaxID,
            $addressTo,
            $addressFrom,
            $movementEndTime,
            $movementStartTime,
            $vehicleID,
            $orderReferences,
            $lines
        );

        $this->assertSame(
            $taxRegistrationNumber, $atockMovement->getTaxRegistrationNumber()
        );
        $this->assertSame($atcud, $atockMovement->getAtcud());
        $this->assertSame($companyName, $atockMovement->getCompanyName());
        $this->assertSame($companyAddress, $atockMovement->getCompanyAddress());
        $this->assertSame($documentNumber, $atockMovement->getDocumentNumber());
        $this->assertSame($movementStatus, $atockMovement->getMovementStatus());
        $this->assertSame($movementDate, $atockMovement->getMovementDate());
        $this->assertSame($movementStartTime, $atockMovement->getMovementStartTime());
        $this->assertSame($addressTo, $atockMovement->getAddressTo());
        $this->assertSame($addressFrom, $atockMovement->getAddressFrom());
        $this->assertSame($movementEndTime, $atockMovement->getMovementEndTime());
        $this->assertSame($movementStartTime, $atockMovement->getMovementStartTime());
        $this->assertSame($vehicleID, $atockMovement->getVehicleID());
        $this->assertSame($lines, $atockMovement->getLines());
        $this->assertSame($movementType, $atockMovement->getMovementType());
        $this->assertSame($farmerTaxID, $atockMovement->getFarmerTaxID());
        $this->assertSame($orderReferences, $atockMovement->getOrderReferences());
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongMovementType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementType only can be 'GR', 'GT'");
        new SubsequentAgriculturalStockMovement(
            "594239427",
            "ABCDEF-".\rand(999, 9999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "N",
            new Date(),
            "GD",
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
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongMovementStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementStatus only can be 'N', 'T'");
        new SubsequentAgriculturalStockMovement(
            "594239427",
            "ABCDEF-".\rand(999, 9999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "A",
            new Date(),
            "GR",
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
    }

}
