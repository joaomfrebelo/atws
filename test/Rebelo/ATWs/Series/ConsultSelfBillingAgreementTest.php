<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Consult Self Billing Agreement Test
 */
class ConsultSelfBillingAgreementTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ConsultSelfBillingAgreement::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function instanceDataProvider(): array
    {
        $data = [];
        $data[] = [
            "999999990", SelfBillingSettlementStatus::A(), new Date(), (new Date())->addDays(1)
        ];

        $data[] = [
            null, SelfBillingSettlementStatus::A(), new Date(), (new Date())->addDays(1)
        ];

        $data[] = [
            "999999990", null, new Date(), (new Date())->addDays(1)
        ];

        $data[] = [
            "999999990", SelfBillingSettlementStatus::A(), null, (new Date())->addDays(1)
        ];


        $data[] = [
            "999999990", SelfBillingSettlementStatus::A(), new Date(), null
        ];

        return $data;
    }

    /**
     * @test
     * @dataProvider instanceDataProvider
     *
     * @param string|null                                          $tinAssociatedWithTheAgreement
     * @param \Rebelo\ATWs\Series\SelfBillingSettlementStatus|null $settlementStatus
     * @param \Rebelo\Date\Date|null                               $authorizationPeriodFrom
     * @param \Rebelo\Date\Date|null                               $authorizationPeriodUntil
     *
     * @return void
     */
    public function testInstance(
        ?string                      $tinAssociatedWithTheAgreement,
        ?SelfBillingSettlementStatus $settlementStatus,
        ?Date                        $authorizationPeriodFrom,
        ?Date                        $authorizationPeriodUntil,
    ): void
    {
        $consult = new ConsultSelfBillingAgreement(
            $tinAssociatedWithTheAgreement,
            $settlementStatus,
            $authorizationPeriodFrom,
            $authorizationPeriodUntil
        );

        $this->assertEquals(
            $tinAssociatedWithTheAgreement, $consult->getTinAssociatedWithTheAgreement()
        );

        $this->assertEquals($settlementStatus, $consult->getSettlementStatus());

        $this->assertEquals(
            $authorizationPeriodFrom?->format(Pattern::SQL_DATE),
            $consult->getAuthorizationPeriodFrom()?->format(Pattern::SQL_DATE)
        );

        $this->assertEquals(
            $authorizationPeriodUntil?->format(Pattern::SQL_DATE),
            $consult->getAuthorizationPeriodUntil()?->format(Pattern::SQL_DATE)
        );

    }

}
