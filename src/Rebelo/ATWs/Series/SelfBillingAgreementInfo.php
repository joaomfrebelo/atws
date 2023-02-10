<?php

namespace Rebelo\ATWs\Series;

use Rebelo\Date\Date;

/**
 * Info self billing agreement
 * @since 2.0.2
 */
class SelfBillingAgreementInfo
{

    /**
     *
     * @var \Logger
     * @since 2.0.2
     */
    protected \Logger $log;

    /**
     * @param \Rebelo\ATWs\Series\SelfBillingEntityCode       $selfBillingEntityCode Type of entity with whom the prior Self-Billing Agreement was established.
     * @param string                                          $tinBuyer TIN of the other entity with which you established the prior Self-billing Agreement.
     * @param string                                          $nameBuyer Name of the other entity with which the prior Self-Billing Agreement was established.
     * @param string                                          $tinAssociatedWithTheAgreement NIF of the entity with which the prior Self-billing Agreement was established.
     * @param string                                          $nameAssociatedWithTheAgreement Name of the entity with whom the prior Self-Billing Agreement was established.
     * @param string|null                                     $foreignCountry Country of your Foreign Purchaser/Supplier
     * @param \Rebelo\ATWs\Series\SelfBillingSettlementStatus $selfBillingSettlementStatus Status code that the Self-Billing Agreement has during the communication process.
     * @param \Rebelo\Date\Date                               $authorizationPeriodFrom Date from which the use of the Self-Billing Agreement is expected.
     * @param \Rebelo\Date\Date|null                          $authorizationPeriodUntil Date by which the use of the Self-Billing Agreement is expected.
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.2
     */
    public function __construct(
        private SelfBillingEntityCode       $selfBillingEntityCode,
        private string                      $tinBuyer,
        private string                      $nameBuyer,
        private string                      $tinAssociatedWithTheAgreement,
        private string                      $nameAssociatedWithTheAgreement,
        private ?string                     $foreignCountry,
        private SelfBillingSettlementStatus $selfBillingSettlementStatus,
        private Date                        $authorizationPeriodFrom,
        private ?Date                       $authorizationPeriodUntil
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug("Agreement With set to:" . $this->selfBillingEntityCode->get());
        $this->log->debug("Tin buyer set to:" . $this->tinBuyer);
        $this->log->debug("Name buyer set to:" . $this->nameBuyer);
        $this->log->debug("Tin associated with the agreement set to:" . $this->tinAssociatedWithTheAgreement);
        $this->log->debug("Name associated with the agreement set to:" . $this->nameAssociatedWithTheAgreement);
        $this->log->debug("Foreign country set to:" . ($this->foreignCountry ?? "null"));
        $this->log->debug(
            "Foreign country set to:" . $this->authorizationPeriodFrom->format(Date::SQL_DATE)
        );
        $this->log->debug(
            "Foreign country set to:" . ($this->authorizationPeriodUntil?->format(Date::SQL_DATE) ?? "null")
        );

    }

    /**
     * Type of entity with whom the prior Self-Billing Agreement was established.
     * @return \Rebelo\ATWs\Series\SelfBillingEntityCode
     * @since 2.0.2
     */
    public function getSelfBillingEntityCode(): SelfBillingEntityCode
    {
        return $this->selfBillingEntityCode;
    }

    /**
     * TIN of the other entity with which you established the prior Self-billing Agreement.
     * @return string
     * @since 2.0.2
     */
    public function getTinBuyer(): string
    {
        return $this->tinBuyer;
    }

    /**
     * Name of the other entity with which the prior Self-Billing Agreement was established.
     * @return string
     * @since 2.0.2
     */
    public function getNameBuyer(): string
    {
        return $this->nameBuyer;
    }

    /**
     * NIF of the entity with which the prior Self-billing Agreement was established.
     * @return string
     * @since 2.0.2
     */
    public function getTinAssociatedWithTheAgreement(): string
    {
        return $this->tinAssociatedWithTheAgreement;
    }

    /**
     * Name of the entity with whom the prior Self-Billing Agreement was established.
     * @return string
     */
    public function getNameAssociatedWithTheAgreement(): string
    {
        return $this->nameAssociatedWithTheAgreement;
    }

    /**
     * Country of your Foreign Purchaser/Supplier
     * @return string|null
     * @since 2.0.2
     */
    public function getForeignCountry(): ?string
    {
        return $this->foreignCountry;
    }

    /**
     * Status code that the Self-Billing Agreement has during the communication process.
     * @return \Rebelo\ATWs\Series\SelfBillingSettlementStatus
     * @since 2.0.2
     */
    public function getSelfBillingSettlementStatus(): SelfBillingSettlementStatus
    {
        return $this->selfBillingSettlementStatus;
    }

    /**
     * Date from which the use of the Self-Billing Agreement is expected.
     * @return \Rebelo\Date\Date
     * @since 2.0.2
     */
    public function getAuthorizationPeriodFrom(): Date
    {
        return $this->authorizationPeriodFrom;
    }

    /**
     * Date by which the use of the Self-Billing Agreement is expected.
     * @return \Rebelo\Date\Date|null
     * @since 2.0.2
     */
    public function getAuthorizationPeriodUntil(): ?Date
    {
        return $this->authorizationPeriodUntil;
    }

}
