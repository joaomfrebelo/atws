<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Consult Webservice Test
 */
class ConsultSelfBillingWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ConsultSelfBillingWs::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testSubmission(): void
    {
        $consultWs = new ConsultSelfBillingWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $consultWs->submission(new ConsultSelfBillingSeries());
        $this->assertEquals(2002, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertEmpty($response->getSeriesInformation());
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testSubmissionNotNull(): void
    {
        $consultSeries = new ConsultSelfBillingSeries(
            "A999",
            SelfBillingDocumentTypeCode::FT(),
            "12345678",
            Date::parse(Date::SQL_DATE, "2021-01-09"),
            Date::parse(Date::SQL_DATE, "2021-01-10"),
            SelfBillingEntityCode::CE(),
            "999999999"
        );

        $consultWs = new ConsultSelfBillingWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $consultWs->submission($consultSeries);
        $this->assertEquals(2002, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertEmpty($response->getSeriesInformation());
    }

}
