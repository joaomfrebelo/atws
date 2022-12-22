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
 * Prior Agricultural Stock Movement<br>
 * Prior Communication of Global Guide for the Acquisition of Products from Producers
 * Agricultural In this section, fields are defined for the registration of a prior guide communication
 * acquisition of products from agricultural producers.
 * The fields used in the request by Webservice are derived from that defined in Ordinance No. 161/2013
 * of April 23.
 * FarmersPurchaseDocRequest
 * @author João Rebelo
 * @since  1.0.0
 */
class PriorAgriculturalStockMovement extends AStockMovement
{

    /**
     * Prior Agricultural Stock Movement - FarmersPurchaseDocRequest
     *
     * @param string            $taxRegistrationNumber Sender's TIN Fill in the Portuguese TIN without spaces
     * @param string            $atcud                 It consists of the series validation code of the document, followed by a hyphen (-), and the sequential number of the document within the series There cannot be records with the same ATCUD
     * @param string            $companyName           Company Name Corporate name of the company or name of the passive subject.
     * @param Address           $companyAddress        You must include the street name, police number and floor, if applicable.
     * @param string            $documentNumber        Unique identification of the transport document It must be identical to that contained in the SAFT (PT) file, when generated from the computer system that issued this document;
     * @param string|null       $ATDocCodeID           Identification code assigned by the AT to the document, pursuant to Decree-Law No. 198/2012, of 24 August.
     * @param string            $movementStatus        Document status. You can assume the following N - Normal; T - On behalf of third parties; A - Canceled; M - Changed.
     * @param \Rebelo\Date\Date $movementDate          Date of issue of the transport document.
     * @param string            $movementType          It must be filled in with: GR - Delivery note; GT - Transport Guide; GA - Asset movement guide own; GC - Consignment guide; GD - Guide or return note made by the client.
     * @param \Rebelo\Date\Date $movementStartTime     Date type: <<YYYY-MM-DD>>.
     * @param bool              $inAzores              Field indicating whether the loading place is located in Azores or on the Mainland and Madeira
     * @param string|array      $farmerTaxID           Producer's TIN. Fill in the Portuguese TIN without spaces and without any country prefix.
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.1.0
     */
    public function __construct(
        string                 $taxRegistrationNumber,
        string                 $atcud,
        string                 $companyName,
        Address                $companyAddress,
        string                 $documentNumber,
        ?string                $ATDocCodeID,
        string                 $movementStatus,
        Date                   $movementDate,
        string                 $movementType,
        Date                   $movementStartTime,
        protected bool         $inAzores,
        protected string|array $farmerTaxID
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
            null,
            null,
            null
        );

        $this->log->debug("MovementType set to: " . $movementType);
        $this->log->debug("MovementStatus set to: " . $movementStatus);
        $this->log->debug("InAzores set to: " . ($inAzores ? "true" : "false"));
        $this->log->debug(
            "FarmerTaxID set to: " .
            \join("; ", \is_array($farmerTaxID) ? $farmerTaxID : [$farmerTaxID])
        );

        if (\in_array($movementType, ["GR", "GT"]) === false) {
            $msg = "MovementType only can be 'GR', 'GT'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (\in_array($movementStatus, ["N", "T", "A", "M"]) === false) {
            $msg = "MovementStatus only can be 'N', 'T', 'A', 'M'";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }
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
     * get in Azores
     * @return bool
     * @since 1.0.0
     */
    public function getInAzores(): bool
    {
        return $this->inAzores;
    }

    /**
     * Get Farmer Tax ID
     * @return string|array
     * @since 1.0.0
     */
    public function getFarmerTaxID(): string|array
    {
        return $this->farmerTaxID;
    }

    /**
     * Get Movement start time
     * @return \Rebelo\Date\Date|null
     * @since 1.0.0
     */
    public function getMovementStartTime(): ?Date
    {
        return $this->movementStartTime;
    }

}
