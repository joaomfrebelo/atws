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

/**
 * Cancel Webservice test
 */
class CancelWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(CancelWs::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testSubmission(): void
    {
        $cancelSeries = new CancelSeries(
            \strtoupper(\substr(\md5(\microtime()), 0, 10)),
            DocumentTypeCode::NC,
            \strtoupper(\substr(\md5(\microtime()), 0, 8)),
            true,
        );

        $cancelWs = new CancelWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $cancelWs->submission($cancelSeries);
        $this->assertEquals(4004, $response->getOperationResultInformation()->getOperationResultCode());
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
        $this->assertEmpty($response->getSeriesInformation());
    }


}
