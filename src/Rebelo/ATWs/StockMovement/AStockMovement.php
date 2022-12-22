<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use Rebelo\Date\Date;

/**
 * AStockMovement
 *
 * @author João Rebelo
 */
abstract class AStockMovement
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     *
     *
     * @param string                 $taxRegistrationNumber
     * @param string                 $atcud
     * @param string                 $companyName
     * @param Address                $companyAddress
     * @param string                 $documentNumber
     * @param string|null            $ATDocCodeID
     * @param string                 $movementStatus
     * @param \Rebelo\Date\Date      $movementDate
     * @param string                 $movementType
     * @param \Rebelo\Date\Date|null $movementStartTime
     * @param Address|null           $addressTo
     * @param Address|null           $addressFrom
     * @param string|null            $vehicleID
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function __construct(
        protected string   $taxRegistrationNumber,
        protected string   $atcud,
        protected string   $companyName,
        protected Address  $companyAddress,
        protected string   $documentNumber,
        protected ?string  $ATDocCodeID,
        protected string   $movementStatus,
        protected Date     $movementDate,
        protected string   $movementType,
        protected ?Date    $movementStartTime,
        protected ?Address $addressTo,
        protected ?Address $addressFrom,
        protected ?string  $vehicleID
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->debug("TaxRegistrationNumber set to: " . $taxRegistrationNumber);
        $this->log->debug("ATCUD set to: " . $atcud);
        $this->log->debug("CompanyName set to: " . $companyName);
        $this->log->debug("DocumentNumber set to: " . $documentNumber);
        $this->log->debug(
            "MovementDate set to: " . $movementDate->format(
                Date::SQL_DATE
            )
        );
        $this->log->debug("MovementType set to: " . $movementType);
        $this->log->debug(
            "MovementStartTime set to: " . (
                ($movementStartTime?->format(Date::DATE_T_TIME)) ?? "null"
            )
        );
        $this->log->debug("VehicleID set to: " . ($vehicleID ?? "null"));
    }

    /**
     * Sender's TIN Fill in the Portuguese TIN without spaces
     * @return string
     * @since 1.0.0
     */
    public function getTaxRegistrationNumber(): string
    {
        return $this->taxRegistrationNumber;
    }

    /**
     * Company Name Corporate name of the company or name of the passive subject.
     * @return \Rebelo\ATWs\StockMovement\Address
     * @since 1.0.0
     */
    public function getCompanyAddress(): Address
    {
        return $this->companyAddress;
    }

    /**
     * Unique identification of the transport document Must be identical to the one
     * appears in the SAFT (PT) file, when generated from the system
     * computer person who issued this document;
     * @return string
     * @since 1.0.0
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * Identification code assigned by the AT to the document,
     * pursuant to Decree-Law No. 198/2012, of 24 August.
     * @return string|null
     * @since 1.0.0
     */
    public function getATDocCodeID(): ?string
    {
        return $this->ATDocCodeID;
    }

    /**
     * Date of issue of the transport document.
     * @return \Rebelo\Date\Date
     * @since 1.0.0
     */
    public function getMovementDate(): Date
    {
        return $this->movementDate;
    }

    /**
     * It must be filled in with:
     * GR - Delivery note;
     * GT - Transport Guide;
     * GA - Asset movement guide own;
     * GC - Consignment guide;
     * GD - Guide or return note made by the client.
     * @return string
     * @since 1.0.0
     */
    public function getMovementType(): string
    {
        return $this->movementType;
    }

    /**
     * Document status. You can assume the following
     * N - Normal; T - On behalf of third parties; A - Canceled.
     * @return string
     * @since 1.0.0
     */
    public function getMovementStatus(): string
    {
        return $this->movementStatus;
    }

    /**
     * It consists of the series validation code of the document, followed by a hyphen (-), and the sequential number
     * of the document within the series There cannot be records with the same ATCUD
     * @return string
     * @since 2.1.0
     */
    public function getAtcud(): string
    {
        return $this->atcud;
    }

}
