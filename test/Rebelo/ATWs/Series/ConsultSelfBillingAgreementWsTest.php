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

/**
 *
 */
class ConsultSelfBillingAgreementWsTest extends TestCase
{
    use TCredentials;

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(ConsultSelfBillingAgreementWs::class))->testReflection(ConsultSelfBillingAgreementWs::class);
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
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    #[Test]
    public function testSubmissionAll(): void
    {
        $consult = new ConsultSelfBillingAgreement(
            "555555559",
            SelfBillingSettlementStatus::F,
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
