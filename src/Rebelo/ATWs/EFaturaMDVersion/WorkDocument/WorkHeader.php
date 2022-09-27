<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;

/**
 * Work Document data header
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class WorkHeader
{
    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string            $documentNumber       Unique document ID: It must be identical to what appears in the SAF-T (PT) file, when generated from the invoicing system that issued this document; It must respect the format defined in the legislation on the SAF-T (PT) file, in force at the time of communication of the elements of the conference documents:     o It consists of the document's internal code, followed by a space, followed by the document series identifier, followed by a slash (/), and a sequential number of the document within the series; There cannot be records with the same identification.
     * @param string            $atcud                This field must contain the Document's Unique Code. The field must be filled in with «0» (zero) until its regulation.
     * @param \Rebelo\Date\Date $workDate             Document issuance date
     * @param string            $workType             Document Type. It can take on the following values: CM – Table consultations; CC – Consignment credit; FC – Consignment invoice pursuant to article 38 of the VAT code; FO – Worksheets; NE – Purchase Order; OR – Others; OR – Budgets; PF – Pro-forma; RP – Prize or receipt of prize; RE – Chargeback or chargeback receipt; CS – Assignment to co-insurers; LD – Assignment to lead co-insurer; RA – Reinsurance accepted.
     * @param string            $customerTaxID        NIF of the national/international acquirer. National TIN: Portuguese Tax Identification Number (without any country prefix). International TIN: NIF or equivalent in the respective country that has been collected in the issuer's invoicing system. When it has not been collected in the issuer's invoicing system, it must be filled in with 999999990.
     * @param string            $customerTaxIDCountry Country of the acquirer's TIN: Two-character code (alpha2) according to the ISO 3166-1 standard.
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    public function __construct(
        protected string $documentNumber,
        protected string $atcud,
        protected Date   $workDate,
        protected string $workType,
        protected string $customerTaxID,
        protected string $customerTaxIDCountry
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("Document number set to " . $documentNumber);
        $this->log->info("ATCUD set to " . $atcud);
        $this->log->info("WorkDate set to " . $workDate->format(Date::SQL_DATE));

        $allowTypes = ['CM', 'CC', 'FC', 'FO', 'NE', 'OU', 'OR', 'PF', 'RP', 'RE', 'CS', 'LD', 'RA'];

        if (\in_array($this->workType, $allowTypes) === false) {
            $msg = \sprintf(
                "WorkType type only can be '%s'",
                \join("', '", $allowTypes),
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("WorkType set to " . $workType);

        $this->log->info("CustomerTaxID set to " . $customerTaxID);

        if (\preg_match("/^([A-Z]{2}|Desconhecido)$/", $this->customerTaxIDCountry) !== 1) {
            $msg = "Wrong format for CustomerTaxIdCountry";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("CustomerTaxIDCountry" . $customerTaxIDCountry);

    }

    /**
     * Unique document ID:
     * It must be identical to what appears in the SAF-T (PT) file,
     * when generated from the invoicing system that issued this document;
     * It must respect the format defined in the legislation on the SAF-T (PT) file,
     * in force at the time of communication of the elements of the conference documents:
     * o It consists of the document's internal code, followed by a space,
     * followed by the document series identifier, followed by a slash (/),
     * and a sequential number of the document within the series;
     * There cannot be records with the same identification.
     * @return string
     * @since 2.0.0
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * This field must contain the Document's Unique Code.
     * The field must be filled in with «0» (zero) until its regulation.
     * @return string
     * @since 2.0.0
     */
    public function getAtcud(): string
    {
        return $this->atcud;
    }

    /**
     * Document issuance date
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getWorkDate(): Date
    {
        return $this->workDate;
    }

    /**
     * Document Type. It can take on the following values:
     * CM – Table consultations;
     * CC – Consignment credit;
     * FC – Consignment invoice pursuant to article 38 of the VAT code;
     * FO – Worksheets;
     * NE – Purchase Order;
     * OR – Others;
     * OR – Budgets;
     * PF – Pro-forma;
     * RP – Prize or receipt of prize;
     * RE – Chargeback or chargeback receipt;
     * CS – Assignment to co-insurers;
     * LD – Assignment to lead co-insurer;
     * RA – Reinsurance accepted.
     * @return string
     * @since 2.0.0
     */
    public function getWorkType(): string
    {
        return $this->workType;
    }

    /**
     * NIF of the national/international acquirer.
     * National TIN:
     * Portuguese Tax Identification Number (without any country prefix).
     * International TIN:
     * NIF or equivalent in the respective country that has been collected in the issuer's invoicing system.
     * When it has not been collected in the issuer's invoicing system, it must be filled in with 999999990.
     * @return string
     * @since 2.0.0
     */
    public function getCustomerTaxID(): string
    {
        return $this->customerTaxID;
    }

    /**
     * Country of the acquirer's TIN:
     * Two-character code (alpha2) according to the ISO 3166-1 standard.
     * @return string
     * @since 2.0.0
     */
    public function getCustomerTaxIDCountry(): string
    {
        return $this->customerTaxIDCountry;
    }

    /**
     * Build the xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "DocumentNumber",
            null,
            $this->getDocumentNumber()
        );

        $xml->writeElementNs(
            Aws::NS_AT_WS_BODY,
            "ATCUD",
            null,
            $this->getAtcud()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "WorkDate",
            null,
            $this->getWorkDate()->format(Date::SQL_DATE)
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "WorkType",
            null,
            $this->getWorkType()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "CustomerTaxID",
            null,
            $this->getCustomerTaxID()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "CustomerTaxIDCountry",
            null,
            $this->getCustomerTaxIDCountry()
        );
    }
}
