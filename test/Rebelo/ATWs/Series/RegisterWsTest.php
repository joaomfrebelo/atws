<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Register Webservice Test
 */
class RegisterWsTest extends TestCase
{
    use TCredentials;

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(RegisterWs::class))->testReflection(RegisterWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     */
    public static function codesDataProvider(): array
    {
        $constProcMedium = [ProcessingMediumCodes::PF]; // ProcessingMediumCodes::cases();
        $constSeriesType = [SeriesTypeCode::N];         // SeriesTypeCode::cases();
        $constDocType    = DocumentTypeCode::cases();

        $data = [];
        foreach ($constProcMedium as $procMedium) {
            foreach ($constSeriesType as $seriesType) {
                foreach ($constDocType as $docType) {
                    $data[] = [$procMedium, $seriesType, $docType];
                }
            }
        }

        return $data;
    }

    /**
     *
     * @param \Rebelo\ATWs\Series\ProcessingMediumCodes $procMedium
     * @param \Rebelo\ATWs\Series\SeriesTypeCode        $seriesType
     * @param \Rebelo\ATWs\Series\DocumentTypeCode      $docType
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    #[DataProvider("codesDataProvider")]
    public function testSubmission(
        ProcessingMediumCodes $procMedium,
        SeriesTypeCode        $seriesType,
        DocumentTypeCode      $docType
    ): void
    {
        $seriesRegister = new SeriesRegister(
            \strtoupper(\substr(\md5(\microtime()), 0, 10)),
            $seriesType,
            $docType,
            999,
            (new Date()),
            9999,
            $procMedium
        );

        $registerWs = new RegisterWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $registerWs->submission($seriesRegister);
        $this->assertEquals(2001, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertNotEmpty($response->getSeriesInformation());
    }
}
