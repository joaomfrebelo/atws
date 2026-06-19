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
 * FinalizeSeries Webservice Test
 */
class FinalizeSelfBillingWsTest extends TestCase
{

    use TCredentials;

    /**
     * @return void
     */
    public function testReflection(): void
    {
        (new Base(FinalizeSelfBillingWs::class))->testReflection(FinalizeSelfBillingWs::class);
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
        foreach (["The reason", null] as $reason) {
            $finalizeSeries = new FinalizeSelfBillingSeries(
                \strtoupper(\substr(\md5(\microtime()), 0, 10)),
                SelfBillingDocumentTypeCode::FT,
                "99999999",
                999,
                SelfBillingEntityCode::CE,
                "999999990",
                $reason
            );

            $finalizeWs = new FinalizeSelfBillingWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $response = $finalizeWs->submission($finalizeSeries);
            $this->assertEquals(4005, $response->getOperationResultInformation()->getOperationResultCode());
            $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
            $this->assertEmpty($response->getSeriesInformation());
        }
    }

}
