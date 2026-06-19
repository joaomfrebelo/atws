<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Consult Webservice Test
 */
class ConsultWsTest extends TestCase
{
    use TCredentials;

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(ConsultWs::class))->testReflection(ConsultWs::class);
        $this->assertTrue(true);
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testSubmission(): void
    {
        $consultWs = new ConsultWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $consultWs->submission(new ConsultSeries());
        $this->assertEquals(2002, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertNotEmpty($response->getSeriesInformation());
    }

    /**
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testSubmissionNotNull(): void
    {
        $consultSeries = new ConsultSeries(
            "A999",
            SeriesTypeCode::N,
            DocumentClassCode::SI,
            DocumentTypeCode::FT,
            "12345678",
            Date::parse(Pattern::SQL_DATE, "2021-01-09"),
            Date::parse(Pattern::SQL_DATE, "2021-01-10"),
            SeriesStatusCode::A,
            ProcessingMediumCodes::PI
        );

        $consultWs = new ConsultWs(
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
