<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class WorkHeaderTest
 *
 * @author João Rebelo
 */
class WorkHeaderTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(WorkHeader::class))->testReflection(WorkHeader::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testInstance(): void
    {

        foreach (['CM', 'CC', 'FC', 'FO', 'NE', 'OU', 'OR', 'PF', 'RP', 'RE', 'CS', 'LD', 'RA'] as $k => $workType) {

            $documentNumber       = "A A/999";
            $atcud                = "0";
            $workDate             = new Date();
            $customerTaxID        = "999999999";
            $customerTaxIDCountry = $k % 2 === 0 ? "PT" : "Desconhecido";

            $header = new WorkHeader(
                $documentNumber,
                $atcud,
                $workDate,
                $workType,
                $customerTaxID,
                $customerTaxIDCountry
            );

            $this->assertSame($documentNumber, $header->getDocumentNumber());
            $this->assertSame($atcud, $header->getAtcud());
            $this->assertSame($workDate, $header->getWorkDate());
            $this->assertSame($workType, $header->getWorkType());
            $this->assertSame($customerTaxID, $header->getCustomerTaxID());
            $this->assertSame($customerTaxIDCountry, $header->getCustomerTaxIDCountry());
        }
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWrongWorkType(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("WorkType type only can be 'CM', 'CC', 'FC', 'FO', 'NE', 'OU', 'OR', 'PF', 'RP', 'RE', 'CS', 'LD', 'RA'");

        new WorkHeader(
            "A A/999",
            "0",
            new Date(),
            "FT",
            "999999990",
            "KR"
        );
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWrongCustomerTaxIDCountry(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("Wrong format for CustomerTaxIdCountry");

        new WorkHeader(
            "A A/999",
            "0",
            new Date(),
            "FO",
            "999999990",
            "KOR"
        );
    }

}
