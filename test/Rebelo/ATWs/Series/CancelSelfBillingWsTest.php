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

/**
 * Cancel Webservice test
 */
class CancelSelfBillingWsTest extends TestCase
{
    use TCredentials;

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(CancelSelfBillingWs::class))->testReflection(CancelSelfBillingWs::class);
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
        $cancelSeries = new CancelSelfBillingSeries(
            \strtoupper(\substr(\md5(\microtime()), 0, 10)),
            SelfBillingDocumentTypeCode::NC,
            \strtoupper(\substr(\md5(\microtime()), 0, 8)),
            true,
            SelfBillingEntityCode::CE,
            "999999999"
        );

        $cancelWs = new CancelSelfBillingWs(
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
