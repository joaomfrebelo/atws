<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

/**
 * Address
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Address
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * The address structure
     * @param string $addressDetail The Address
     * @param string $city          The address city
     * @param string $postalCode    The address postal code
     * @since 1.0.0
     */
    public function __construct(
        protected string $addressDetail,
        protected string $city,
        protected string $postalCode
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug("AddressDetail set to: " . $addressDetail);
        $this->log->debug("City set to: " . $city);
        $this->log->debug("Postal code set to: " . $postalCode);
    }

    /**
     * Get the address detail
     * @return string|null
     * @since 1.0.0
     */
    public function getAddressDetail(): ?string
    {
        return $this->addressDetail;
    }

    /**
     * Get the address city
     * @return string|null
     * @since 1.0.0
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Get the address postal code
     * @return string|null
     * @since 1.0.0
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Build the Address xml
     * @param \XMLWriter $xml
     * @return void
     * @since 1.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        if ($this->getAddressDetail() !== null) {
            $xml->writeElement(
                "Addressdetail", $this->getAddressDetail()
            );
        }

        if ($this->getCity() !== null) {
            $xml->writeElement(
                "City", $this->getCity()
            );
        }

        if ($this->getPostalCode() !== null) {
            $xml->writeElement(
                "PostalCode", $this->getPostalCode()
            );
        }

        $xml->writeElement("Country", "PT");
    }

}
