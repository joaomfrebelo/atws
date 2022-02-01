<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

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
     * @return array[]
     */
    public function responsesProvider(): array
    {
        $baseDir = ATWS_SERIES_RESPONSE_DIR . DIRECTORY_SEPARATOR;
        return [
            [$baseDir . "FaultResponse.xml", 33, "The fault"],
            [$baseDir . "ErrorResponse.xml", 5000, "Erro Técnico Inespecífico"],
        ];
    }

    /**
     * @test
     * @dataProvider responsesProvider
     * @param string $filePath
     * @param int $code
     * @param string $message
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Exception
     */
    public function testResponses(string $filePath, int $code, string $message): void
    {
        $xml      = \file_get_contents($filePath);
        $response = Response::factory($xml ?: throw new \Exception("Fail to load file " . $filePath));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($code, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertSame($message, $response->getOperationResultInformation()->getOperationResultMessage());
    }

    /***
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testWrongXmlResponses(): void
    {
        $this->expectException(ATWsException::class);
        $xml      = "<Test></Test>";
        $response = Response::factory($xml);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     */
    public function testRegisterResponse(): void
    {
        $filePath = ATWS_SERIES_RESPONSE_DIR . DIRECTORY_SEPARATOR . "RegisterResponse.xml";
        $xml      = \file_get_contents($filePath);
        $response = Response::factory($xml ?: throw new \Exception("Fail to load file " . $filePath));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(2001, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertNotEmpty($response->getSeriesInformation());

        $seriesInformation = $response->getSeriesInformation()[0];

        $this->assertSame("2511232477", $seriesInformation->getSeries());
        $this->assertSame("N", $seriesInformation->getSeriesTypeCode()->get());
        $this->assertSame("SI", $seriesInformation->getDocumentClassCode()->get());
        $this->assertSame("FT", $seriesInformation->getDocumentTypeCode()->get());
        $this->assertSame(999, $seriesInformation->getSeriesInitialSequenceNumber());
        $this->assertSame("2022-04-01", $seriesInformation->getExpectedInitialDateUse()->format(Date::SQL_DATE));
        $this->assertSame("PF", $seriesInformation->getProcessingMediumCode()->get());
        $this->assertSame(9999, $seriesInformation->getSoftwareCertificate());
        $this->assertSame("AAJFF5JWJN", $seriesInformation->getSeriesValidationCode());
        $this->assertSame("2022-02-01", $seriesInformation->getRegisterDate()->format(Date::SQL_DATE));
        $this->assertSame("A", $seriesInformation->getSeriesStatusCode()->get());
        $this->assertSame("2022-02-01T12:31:20", $seriesInformation->getStatusDate()->format(Date::DATE_T_TIME));
        $this->assertSame("555555550", $seriesInformation->getRegistrationNif());
        $this->assertNull($seriesInformation->getStatusReasonCode());
        $this->assertNull($seriesInformation->getStatusReason());
        $this->assertNull($seriesInformation->getSeriesLastSequenceNumber());
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     */
    public function testRegisterResponseMultiple(): void
    {
        $filePath = ATWS_SERIES_RESPONSE_DIR . DIRECTORY_SEPARATOR . "RegisterResponseMultiple.xml";
        $xml      = \file_get_contents($filePath);
        $response = Response::factory($xml ?: throw new \Exception("Fail to load file " . $filePath));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(2001, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertNotEmpty($response->getSeriesInformation());

        $seriesInformation = $response->getSeriesInformation()[0];

        $this->assertSame("2511232477", $seriesInformation->getSeries());
        $this->assertSame("N", $seriesInformation->getSeriesTypeCode()->get());
        $this->assertSame("SI", $seriesInformation->getDocumentClassCode()->get());
        $this->assertSame("FT", $seriesInformation->getDocumentTypeCode()->get());
        $this->assertSame(999, $seriesInformation->getSeriesInitialSequenceNumber());
        $this->assertSame("2022-04-01", $seriesInformation->getExpectedInitialDateUse()->format(Date::SQL_DATE));
        $this->assertSame("PF", $seriesInformation->getProcessingMediumCode()->get());
        $this->assertSame(9999, $seriesInformation->getSoftwareCertificate());
        $this->assertSame("AAJFF5JWJN", $seriesInformation->getSeriesValidationCode());
        $this->assertSame("2022-02-01", $seriesInformation->getRegisterDate()->format(Date::SQL_DATE));
        $this->assertSame("A", $seriesInformation->getSeriesStatusCode()->get());
        $this->assertSame("2022-02-01T12:31:20", $seriesInformation->getStatusDate()->format(Date::DATE_T_TIME));
        $this->assertSame("555555550", $seriesInformation->getRegistrationNif());
        $this->assertNull($seriesInformation->getStatusReasonCode());
        $this->assertNull($seriesInformation->getStatusReason());
        $this->assertNull($seriesInformation->getSeriesLastSequenceNumber());

        $seriesInformation = $response->getSeriesInformation()[1];

        $this->assertSame("2511232999", $seriesInformation->getSeries());
        $this->assertSame("N", $seriesInformation->getSeriesTypeCode()->get());
        $this->assertSame("SI", $seriesInformation->getDocumentClassCode()->get());
        $this->assertSame("FT", $seriesInformation->getDocumentTypeCode()->get());
        $this->assertSame(1, $seriesInformation->getSeriesInitialSequenceNumber());
        $this->assertSame("2022-09-01", $seriesInformation->getExpectedInitialDateUse()->format(Date::SQL_DATE));
        $this->assertSame("PF", $seriesInformation->getProcessingMediumCode()->get());
        $this->assertSame(9999, $seriesInformation->getSoftwareCertificate());
        $this->assertSame("AAJFF5J999", $seriesInformation->getSeriesValidationCode());
        $this->assertSame("2022-02-19", $seriesInformation->getRegisterDate()->format(Date::SQL_DATE));
        $this->assertSame("A", $seriesInformation->getSeriesStatusCode()->get());
        $this->assertSame("2022-02-01T19:31:20", $seriesInformation->getStatusDate()->format(Date::DATE_T_TIME));
        $this->assertSame("999999999", $seriesInformation->getRegistrationNif());
        $this->assertSame("The status reason", $seriesInformation->getStatusReasonCode());
        $this->assertSame("The justify", $seriesInformation->getStatusReason());
        $this->assertSame(999, $seriesInformation->getSeriesLastSequenceNumber());
    }

}
