<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * StockMovement<br>
 * Transport documents In this section, the fields for the registration of a new transport document are defined.
 * The fields used in the request by Webservice are derived from that defined in Ordinance No. 160/2013
 * April 23 for SAF-T (PT) fields.
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class StockMovement extends AStockMovement
{

    /**
     * Stock movement
     *
     * @param string                 $taxRegistrationNumber Sender's TIN Fill in the Portuguese TIN without spaces
     * @param string                 $atcud                 It consists of the series validation code of the document, followed by a hyphen (-), and the sequential number of the document within the series There cannot be records with the same ATCUD
     * @param string                 $companyName           Company Name Corporate name of the company or name of the passive subject.
     * @param Address                $companyAddress        You must include the street name, police number and floor, if applicable.
     * @param string                 $documentNumber        Unique identification of the transport document It must be identical to that contained in the SAFT (PT) file, when generated from the computer system that issued this document;
     * @param string|null            $ATDocCodeID           Identification code assigned by the AT to the document, pursuant to Decree-Law No. 198/2012, of 24 August.
     * @param string                 $movementStatus        Document status. You can assume the following N - Normal; T - On behalf of third parties; A - Canceled.
     * @param \Rebelo\Date\Date      $movementDate          Date of issue of the transport document.
     * @param string                 $movementType          It must be filled in with: GR - Delivery note; GT - Transport Guide; GA - Asset movement guide own; GC - Consignment guide; GD - Guide or return note made by the client.
     * @param string|null            $customerTaxID         National customer's TIN, In the event that the recipient is not known must be filled in with the TIN <<999999990>>;
     * @param string|null            $supplierTaxID         National supplier's TIN, In the event that the recipient is not known must be filled in with the TIN <<999999990>>;
     * @param string|null            $customerName          Company name Client Corporate name of the company or name of the passive subject
     * @param Address|null           $customerAddress       Customer Company Address (CustomerAddress)
     * @param Address|null           $addressTo             Download location (AddressTo)
     * @param Address                $addressFrom           Loading location (AddressFrom)
     * @param \Rebelo\Date\Date|null $movementEndTime       Date and time type: << YYYY-MM-DD Thh: mm: ss >>
     * @param \Rebelo\Date\Date      $movementStartTime     Date and time type: << YYYY-MM-DD Thh: mm: ss >>
     * @param string|null            $vehicleID             Vehicle registration
     * @param array                  $lines                 Document lines with goods (Line)
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.1.0
     */
    public function __construct(
        string             $taxRegistrationNumber,
        string             $atcud,
        string             $companyName,
        Address            $companyAddress,
        string             $documentNumber,
        ?string            $ATDocCodeID,
        string             $movementStatus,
        Date               $movementDate,
        string             $movementType,
        protected ?string  $customerTaxID,
        protected ?string  $supplierTaxID,
        protected ?string  $customerName,
        protected ?Address $customerAddress,
        ?Address           $addressTo,
        Address            $addressFrom,
        protected ?Date    $movementEndTime,
        Date               $movementStartTime,
        ?string            $vehicleID,
        protected array    $lines = []
    )
    {

        parent::__construct(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            $ATDocCodeID,
            $movementStatus,
            $movementDate,
            $movementType,
            $movementStartTime,
            $addressTo,
            $addressFrom,
            $vehicleID
        );

        $this->log->debug("MovementType set to: " . $movementType);
        $this->log->debug("MovementStatus set to: " . $movementStatus);
        $this->log->debug("CustomerTaxID set to: " . $customerTaxID);
        $this->log->debug("SupplierTaxID set to: " . $supplierTaxID);
        $this->log->debug("CustomerName set to: " . $customerName);
        $this->log->debug(
            "MovementEndTime set to: " . (
                ($movementEndTime?->format(Date::DATE_T_TIME)) ?? "null")
        );

        if (\in_array($movementType, ["GR", "GT", "GA", "GC", "GD"]) === false) {
            $msg = "MovementType only can be 'GR', 'GT', 'GA', 'GC', 'GD'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (\in_array($movementStatus, ["N", "T", "A"]) === false) {
            $msg = "MovementStatus only can be 'N', 'T', 'A'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($supplierTaxID === null && $customerTaxID === null) {
            $msg = "SupplierTaxID and CustomerTaxID can not be mull at same time";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($supplierTaxID !== null && $customerTaxID !== null) {
            $msg = "SupplierTaxID and CustomerTaxID can not be set at same time";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }
    }

    /**
     * Company Name Corporate name of the company or name of the passive subject.
     * @return string
     * @since 1.0.0
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * National customer's TIN, In the event that the recipient is not
     * known must be filled in with the TIN <<999999990>>;
     * @return string|null
     * @since 1.0.0
     */
    public function getCustomerTaxID(): ?string
    {
        return $this->customerTaxID;
    }

    /**
     * National supplier's TIN, In the event that the recipient is not known
     * must be filled in with the TIN <<999999990>>;
     * @return string|null
     * @since 1.0.0
     */
    public function getSupplierTaxID(): ?string
    {
        return $this->supplierTaxID;
    }

    /**
     * Company name Client Corporate name of the company or name of the passive subject
     * @return string|null
     * @since 1.0.0
     */
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     * Customer Address
     * @return Address|null
     * @since 1.0.0
     */
    public function getCustomerAddress(): ?Address
    {
        return $this->customerAddress;
    }

    /**
     * Download location (AddressTo)
     * @return Address|null
     * @since 1.0.0
     */
    public function getAddressTo(): ?Address
    {
        return $this->addressTo;
    }

    /**
     * Loading location (AddressFrom)
     * @return \Rebelo\ATWs\StockMovement\Address
     * @since 1.0.0
     */
    public function getAddressFrom(): Address
    {
        return $this->addressFrom;
    }

    /**
     * Date and time type: YYYY-MM-DDThh:mm:ss
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementStartTime(): Date
    {
        return $this->movementStartTime;
    }

    /**
     * Vehicle registration
     * @return string|null
     * @since 1.0.0
     */
    public function getVehicleID(): ?string
    {
        return $this->vehicleID;
    }

    /**
     * Get Movement end time
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getMovementEndTime(): ?Date
    {
        return $this->movementEndTime;
    }

    /**
     * Document lines with goods (Line)
     * @return \Rebelo\ATWs\StockMovement\Line[]
     * @since 1.0.0
     */
    public function getLines(): array
    {
        return $this->lines;
    }

}
