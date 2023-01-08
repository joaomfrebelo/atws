<?php
/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use Rebelo\Date\Date;

/**
 * Consult the self billing settlements
 * @since 2.0.2
 */
class ConsultSelfBillingAgreement
{
    /**
     *
     * @var \Logger
     * @since 2.0.2
     */
    protected \Logger $log;

    /**
     * @param string|null                                          $tinAssociatedWithTheAgreement Indicate the TIN of the entity with which you established the prior Self-invoicing Agreement.
     * @param \Rebelo\ATWs\Series\SelfBillingSettlementStatus|null $settlementStatus              Indicate the status of the Self-Billing Agreement you wish to consult.
     * @param \Rebelo\Date\Date|null                               $authorizationPeriodFrom       Enter the start date of the search range.
     * @param \Rebelo\Date\Date|null                               $authorizationPeriodUntil      Enter the end date of the search range.
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.2
     */
    public function __construct(
        private ?string                      $tinAssociatedWithTheAgreement,
        private ?SelfBillingSettlementStatus $settlementStatus,
        private ?Date                        $authorizationPeriodFrom,
        private ?Date                        $authorizationPeriodUntil,
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug("TinAssociatedWithTheAgreement set to: " . ($this->tinAssociatedWithTheAgreement ?? "null"));
        $this->log->debug("settlementStatus set to: " . ($this->settlementStatus?->get() ?? "null"));
        $this->log->debug(
            "authorizationPeriodFrom set to: " . (
                $this->authorizationPeriodFrom?->format(Date::SQL_DATE) ?? "null")
        );
        $this->log->debug(
            "authorizationPeriodUntil set to: " . (
                $this->authorizationPeriodUntil?->format(Date::SQL_DATE) ?? "null")
        );
    }

    /**
     * Indicate the TIN of the entity with which you established the prior Self-invoicing Agreement.
     * @return string|null
     * @since 2.0.2
     */
    public function getTinAssociatedWithTheAgreement(): ?string
    {
        return $this->tinAssociatedWithTheAgreement;
    }

    /**
     * Indicate the status of the Self-Billing Agreement you wish to consult.
     * @return \Rebelo\ATWs\Series\SelfBillingSettlementStatus|null
     * @since 2.0.2
     */
    public function getSettlementStatus(): ?SelfBillingSettlementStatus
    {
        return $this->settlementStatus;
    }

    /**
     * Enter the start date of the search range.
     * @return \Rebelo\Date\Date|null
     * @since 2.0.2
     */
    public function getAuthorizationPeriodFrom(): ?Date
    {
        return $this->authorizationPeriodFrom;
    }

    /**
     * Enter the end date of the search range.
     * @return \Rebelo\Date\Date|null
     * @since 2.0.2
     */
    public function getAuthorizationPeriodUntil(): ?Date
    {
        return $this->authorizationPeriodUntil;
    }

}
