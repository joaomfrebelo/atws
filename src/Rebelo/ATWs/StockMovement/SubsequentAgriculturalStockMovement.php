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
 * Subsequent Agricultural Stock Movement<br>
 * Subsequent Communication of Product Acquisition Guides from Producers
 * Agricultural In this section, fields are defined for the
 * registration of a later communication of a guide
 * purchasing products from agricultural producers
 * The fields used in the request by Webservice are derived
 * from the one defined in Ordinance No. 161/2013
 * of April 23.
 * FarmersPurchasePartialDocRequest
 * @author João Rebelo
 * @since  1.0.0
 */
class SubsequentAgriculturalStockMovement extends AStockMovement
{

    /**
     * Subsequent Agricultural Stock Movement - FarmersPurchasePartialDocRequest
     *
     * @param string                                        $taxRegistrationNumber Sender's TIN Fill in the Portuguese TIN without spaces
     * @param string                                        $atcud                 It consists of the series validation code of the document, followed by a hyphen (-), and the sequential number of the document within the series There cannot be records with the same ATCUD
     * @param string                                        $companyName           Company Name Corporate name of the company or name of the passive subject.
     * @param Address                                       $companyAddress        You must include the street name, police number and floor, if applicable.
     * @param string                                        $documentNumber        Unique identification of the transport document It must be identical to that contained in the SAF-T (PT) file, when generated from the computer system that issued this document
     * @param string                                        $movementStatus        Document status. You can assume the following N - Normal; T - On behalf of third parties; A - Canceled; M - Changed.
     * @param \Rebelo\Date\Date                             $movementDate          Date of issue of the transport document.
     * @param string                                        $movementType          It must be filled in with: GR - Delivery note; GT - Transport Guide; GA - Asset movement guide own; GC - Consignment guide; GD - Guide or return note made by the client.
     * @param string                                        $farmerTaxID           Producer's TIN. Fill in the Portuguese TIN without spaces and without any country prefix.
     * @param \Rebelo\ATWs\StockMovement\Address|null       $addressTo             Download address
     * @param \Rebelo\ATWs\StockMovement\Address            $addressFrom           Load address
     * @param ?\Rebelo\Date\Date                            $movementEndTime       $movementEndTime Movement end time
     * @param \Rebelo\Date\Date                             $movementStartTime     $movementStartTime Movement start time
     * @param ?string                                       $vehicleID
     * @param ?\Rebelo\ATWs\StockMovement\OrderReferences   $orderReferences
     * @param \Rebelo\ATWs\StockMovement\AgriculturalLine[] $lines                 Document lines with goods (Line)
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.1.0
     */
    public function __construct(
        string                     $taxRegistrationNumber,
        string                     $atcud,
        string                     $companyName,
        Address                    $companyAddress,
        string                     $documentNumber,
        string                     $movementStatus,
        Date                       $movementDate,
        string                     $movementType,
        protected string           $farmerTaxID,
        ?Address                   $addressTo,
        Address                    $addressFrom,
        protected ?Date            $movementEndTime,
        Date                       $movementStartTime,
        ?string                    $vehicleID,
        protected ?OrderReferences $orderReferences,
        protected array            $lines = []
    )
    {

        parent::__construct(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            null,
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

        if (\in_array($movementType, ["GR", "GT"]) === false) {
            $msg = "MovementType only can be 'GR', 'GT'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (\in_array($movementStatus, ["N", "T"]) === false) {
            $msg = "MovementStatus only can be 'N', 'T'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        parent::__construct(
            $taxRegistrationNumber,
            $atcud,
            $companyName,
            $companyAddress,
            $documentNumber,
            null,
            $movementStatus,
            $movementDate,
            $movementType,
            $movementStartTime,
            $addressTo,
            $addressFrom,
            $vehicleID
        );
    }

    /**
     * Get company name
     * @return string
     * @since 1.0.0
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * Get farmer Tax ID
     * @return string
     * @since 1.0.0
     */
    public function getFarmerTaxID(): string
    {
        return $this->farmerTaxID;
    }

    /**
     * Get Address to
     * @return \Rebelo\ATWs\StockMovement\Address|null
     * @since 1.0.0
     */
    public function getAddressTo(): ?Address
    {
        return $this->addressTo;
    }

    /**
     * Get Address from
     * @return \Rebelo\ATWs\StockMovement\Address
     * @since 1.0.0
     */
    public function getAddressFrom(): Address
    {
        return $this->addressFrom;
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
     * Get Movement Start Time
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementStartTime(): Date
    {
        return $this->movementStartTime;
    }

    /**
     * Get Vehicle ID
     * @return string|null
     * @since 1.0.0
     */
    public function getVehicleID(): ?string
    {
        return $this->vehicleID;
    }

    /**
     * Get Order References
     * @return OrderReferences|null
     * @since 1.0.0
     */
    public function getOrderReferences(): ?OrderReferences
    {
        return $this->orderReferences;
    }

    /**
     * Get Lines
     * @return \Rebelo\ATWs\StockMovement\AgriculturalLine[]
     * @since 1.0.0
     */
    public function getLines(): array
    {
        return $this->lines;
    }

}
