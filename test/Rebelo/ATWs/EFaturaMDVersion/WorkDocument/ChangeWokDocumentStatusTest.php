<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Change Wok Document Status Test
 *
 * @author João Rebelo
 */
class ChangeWokDocumentStatusTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ChangeWokDocumentStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {

        $taxRegistrationNumber = "555555555";
        $documentNumber        = "A A/999";
        $atcud                 = "0";
        $workDate              = new Date();
        $customerTaxID         = "999999999";
        $customerTaxIDCountry  = "KR";

        $workHeader = new WorkHeader(
            $documentNumber,
            $atcud,
            $workDate,
            "FO",
            $customerTaxID,
            $customerTaxIDCountry
        );

        $newWorkStatus = new WorkStatus("A", new Date());

        foreach ([new RecordChannel("System TUX", "9.9.9"), null] as $recordChannel) {

            $changeStatus = new ChangeWokDocumentStatus(
                $taxRegistrationNumber,
                $workHeader,
                $newWorkStatus,
                $recordChannel
            );

            $this->assertSame($taxRegistrationNumber, $changeStatus->getTaxRegistrationNumber());
            $this->assertSame($workHeader, $changeStatus->getWorkHeader());
            $this->assertSame($newWorkStatus, $changeStatus->getNewWorkStatus());
            $this->assertSame($recordChannel, $changeStatus->getRecordChannel());
        }
    }

}
