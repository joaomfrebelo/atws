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
 *
 */
class ConsultSelfBillingAgreementWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ConsultSelfBillingAgreementWs::class);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testSubmission(): void
    {
        $consult = new ConsultSelfBillingAgreement(
            null, null, null, null
        );

        $consultWs = new ConsultSelfBillingAgreementWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $consultWs->submission($consult);

        $this->assertTrue($response->isResponseOk());

    }

    /**
     * @test
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testSubmissionAll(): void
    {
        $consult = new ConsultSelfBillingAgreement(
            "555555559",
            SelfBillingSettlementStatus::F(),
            (new Date())->addDays(-99),
            new Date()
        );

        $consultWs = new ConsultSelfBillingAgreementWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $consultWs->submission($consult);

        $this->assertTrue($response->isResponseOk());
    }

}
