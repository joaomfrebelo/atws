<?php
/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Payment Header
 * @since  2.0.0
 * @author João M F Rebelo
 */
class PaymentHeader
{
    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string            $paymentRefNo
     * @param string            $atcud
     * @param \Rebelo\Date\Date $transactionDate
     * @param string            $paymentType
     * @param string            $customerTaxID
     * @param string            $customerTaxIDCountry
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    public function __construct(
        protected string $paymentRefNo,
        protected string $atcud,
        protected Date   $transactionDate,
        protected string $paymentType,
        protected string $customerTaxID,
        protected string $customerTaxIDCountry
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("PaymentRefNo set to " . $paymentRefNo);
        $this->log->info("ATCUD set to " . $atcud);
        $this->log->info(
            "TransactionDate set to " . $transactionDate->format(Pattern::SQL_DATE)
        );

        if ($this->paymentType !== "RC") {
            $msg = "PaymentType type only can be 'RC' Cash vat payment";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Payment type set to " . $paymentType);

        $this->log->info("CustomerTaxID set to " . $customerTaxID);

        if (\preg_match("/^([A-Z]{2}|Desconhecido)$/", $this->customerTaxIDCountry) !== 1) {
            $msg = "Wrong format for CustomerTaxIdCountry";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("CustomerTaxIDCountry" . $customerTaxIDCountry);

    }

    /**
     * Unique Receipt ID:
     * It must be identical to what appears in the SAF-T (PT) file, when generated from the invoicing system that issued this receipt;
     * It must respect the format defined in the legislation on the SAF-T (PT) file, in force when communicating the elements of the receipts:
     * o It consists of the internal code of the receipt, followed by a space, followed by the identifier of the receipt series, followed by a slash (/), and a sequential number of the receipt within the series;
     * There cannot be records with the same identification.
     * @return string
     * @since 2.0.0
     */
    public function getPaymentRefNo(): string
    {
        return $this->paymentRefNo;
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
     * Receipt issue date.
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getTransactionDate(): Date
    {
        return $this->transactionDate;
    }

    /**
     * Receipt type. It can take on the following values:
     * RC – Receipt issued under the Cash VAT regime (including those relating to advances under this regime).
     * @return string
     * @since 2.0.0
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
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
            "PaymentRefNo",
            null,
            $this->getPaymentRefNo()
        );

        $xml->writeElementNs(
            Aws::NS_AT_WS_BODY,
            "ATCUD",
            null,
            $this->getAtcud()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TransactionDate",
            null,
            $this->getTransactionDate()->format(Pattern::SQL_DATE)
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "PaymentType",
            null,
            $this->getPaymentType()
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
