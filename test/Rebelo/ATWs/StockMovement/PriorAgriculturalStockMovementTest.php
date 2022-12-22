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
 * Class PriorAgriculturalStockMovementTest
 *
 * @author João Rebelo
 */
class PriorAgriculturalStockMovementTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(
            PriorAgriculturalStockMovement::class
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

        $base = [
            "taxRegistrationNumber" => "594239427",
            "atcud"                 => "ABCDEF-999",
            "companyName"           => "The Company name",
            "companyAddress"        => $companyAddress,
            "documentNumber"        => "GTA 9999/29",
            "ATDocCodeID"           => "999999",
            "movementStatus"        => "N",
            "movementDate"          => new Date(),
            "movementType"          => "GT",
            "movementStartTime"     => new Date(),
            "inAcores"              => false,
            "farmerTaxID"           => "561248923",
        ];

        return [
            $base,
            \array_merge($base, ["ATDocCodeID" => null, "movementStatus" => "T", "movementType" => "GR"]),
            \array_merge($base, ["movementStatus" => "A", "farmerTaxID" => ["799732958", "790976439"]]),
            \array_merge($base, ["movementStatus" => "M"]),
        ];
    }

    /**
     * @dataProvider provider
     * @test
     * @param string       $taxRegistrationNumber
     * @param string       $atcud
     * @param string       $companyName
     * @param Address      $companyAddress
     * @param string       $documentNumber
     * @param string|null  $ATDocCodeID
     * @param string       $movementStatus
     * @param Date         $movementDate
     * @param string       $movementType
     * @param Date         $movementStartTime
     * @param bool         $inAcores
     * @param string|array $farmerTaxID
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(
        string       $taxRegistrationNumber,
        string       $atcud,
        string       $companyName,
        Address      $companyAddress,
        string       $documentNumber,
        ?string      $ATDocCodeID,
        string       $movementStatus,
        Date         $movementDate,
        string       $movementType,
        Date         $movementStartTime,
        bool         $inAcores,
        string|array $farmerTaxID
    ): void
    {
        $priorAgrStkMov = new PriorAgriculturalStockMovement(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            $ATDocCodeID,
            $movementStatus,
            $movementDate,
            $movementType,
            $movementStartTime,
            $inAcores,
            $farmerTaxID
        );

        $this->assertSame(
            $taxRegistrationNumber, $priorAgrStkMov->getTaxRegistrationNumber()
        );
        $this->assertSame($atcud, $priorAgrStkMov->getAtcud());
        $this->assertSame($companyAddress, $priorAgrStkMov->getCompanyAddress());
        $this->assertSame($documentNumber, $priorAgrStkMov->getDocumentNumber());
        $this->assertSame($ATDocCodeID, $priorAgrStkMov->getATDocCodeID());
        $this->assertSame($movementStatus, $priorAgrStkMov->getMovementStatus());
        $this->assertSame($movementDate, $priorAgrStkMov->getMovementDate());
        $this->assertSame($movementStartTime, $priorAgrStkMov->getMovementStartTime());
        $this->assertSame($inAcores, $priorAgrStkMov->getInAzores());
        $this->assertSame($farmerTaxID, $priorAgrStkMov->getFarmerTaxID());
    }

    /**
     *
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongMovementType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementType only can be 'GR', 'GT'");
        new PriorAgriculturalStockMovement(
            "594239427",
            "ABCDEF-".\rand(999, 9999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/29",
            "999999",
            "N",
            new Date(),
            "GC",
            new Date(),
            false,
            "561248923"
        );
    }

    /**
     *
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testWrongMovementStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("MovementStatus only can be 'N', 'T', 'A', 'M'");
        new PriorAgriculturalStockMovement(
            "594239427",
            "ABCDEF-".\rand(999, 9999),
            "The Company name",
            new Address("Rua A", "Lisboa", "9999-999"),
            "GTA 9999/" . (new Date())->getTimestamp(),
            "999999",
            "B",
            new Date(),
            "GT",
            new Date(),
            false,
            "561248923"
        );
    }

}
