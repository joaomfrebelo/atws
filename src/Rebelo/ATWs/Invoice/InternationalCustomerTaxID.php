<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

/**
 * InternationalCustomerTaxID
 *
 * @author João Rebelo
 */
class InternationalCustomerTaxID
{

    /**
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * Foreign Purchaser TIN (InternationalCustomerTaxID)<br>
     * This field is mutually exclusive with the field
     * “1.5 - Acquiring TIN (CustomerTaxID)”. One and only one of
     * the fields must be completed.
     * It must be filled in whenever it is a foreign acquirer,
     * whose TIN has been collected in the issuer's billing system;
     * @param string $taxIDNumber  TIN of the foreign acquirer. Foreign Tax Identification Number (without country prefix).
     * @param string $taxIDCountry Foreign purchaser's TIN country
     */
    public function __construct(
        protected string $taxIDNumber,
        protected string $taxIDCountry
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug("taxIDNumber set to: " . $this->taxIDNumber);
        $this->log->debug("taxIDCountry set to: " . $this->taxIDCountry);
    }

    /**
     * Get the TAX id number<br>
     * TIN of the foreign acquirer. Foreign Tax Identification Number (without country prefix).
     * @return string
     * @since 1.0.0
     */
    public function getTaxIDNumber(): string
    {
        return $this->taxIDNumber;
    }

    /**
     * Get the tax ID country<br>
     * Foreign purchaser's TIN country
     * @return string
     * @since 1.0.0
     */
    public function getTaxIDCountry(): string
    {
        return $this->taxIDCountry;
    }

}
