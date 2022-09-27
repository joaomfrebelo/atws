<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\ATWsException;

/**
 * Tax Rate (Tax)
 * @since 2.0.0
 */
class Tax
{
    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string     $taxType          Fee type. It must take on value: VAT – Value Added Tax; IS – Stamp Duty; NS – Not subject to VAT or IS.
     * @param string     $taxCountryRegion Must be filled in with: Two-character code (alpha2) according to the ISO 3166-1 standard; PT-AC – fiscal space of the Autonomous Region of the Azores; PT-MA – tax area of the Autonomous Region of Madeira.
     * @param string     $taxCode          Rate code in the tax table. If the tax type (TaxType) = VAT, it must be filled in with:Rate code in the tax table. If the tax type (TaxType) = VAT, it must be filled in with: RED – Reduced rate; INT – Intermediate rate; NOR – Normal rate; ISE – Exempt; OUT – Others, applicable for special VAT regimes. If the tax type (TaxType) = IS, it must be filled in with:  The code of the respective budget;  ISE – Exempt. In the case of non-submission, it must be filled in with NS.  INT – Intermediate rate;  NOR – Normal rate;  ISE – Exempt;  OUT – Others, applicable for special VAT regimes. If the tax type (TaxType) = IS, it must be filled in with: The code of the respective budget; ISE – Exempt. In the case of non-submission, it must be filled in with NS.
     * @param float|null $taxPercentage    This field is mandatory if it is a tax percentage.VAT rate applied: It must be filled in with the percentage of the rate corresponding to the tax applicable to the fields “1.6.14.5 – Total taxable (TotalTaxBase)” or “1.6.14.6 – Amount of the line without tax in the document (Amount)”; It must be filled in with “0” (zero) in the case of an exempt transmission or provision of services or in which, justifiably, there is no VAT payment.This field is mutually exclusive with the field “1.6.14.7.5 – (TotalTaxAmount)”. Only one of the fields must be filled in.
     * @param float|null $totalTaxAmount   Completion is mandatory, in the case of a fixed amount of Stamp Duty. Calculation of the Total Tax Amount.: Quantity * TaxAmount. Note: fields according to SAF-T (PT). This field is mutually exclusive with the field “1.6.14.7.4 – Tax Percentage (TaxPercentage)”. Only one of the fields must be filled in.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $taxType,
        protected string $taxCountryRegion,
        protected string $taxCode,
        protected ?float $taxPercentage,
        protected ?float $totalTaxAmount
    )
    {

        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $allowType = ['IVA', 'IS', 'NS'];
        if (\in_array($this->taxType, $allowType) === false) {
            $msg = \sprintf(
                "Tax Type only can be on of '%s'",
                \join("', '", $allowType)
            );
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Tax type set to " . $this->taxType);

        if (\preg_match("/^([A-Z]{2}|PT-MA|PT-AC)$/", $this->taxCountryRegion) !== 1) {
            $msg = "Wrong Tax Country Region";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("TaxCountryRegion was set to " . $this->taxCountryRegion);

        if ($this->taxType === "IVA") {

            $allowCode = ['RED', 'INT', 'NOR', 'ISE', 'OUT'];

            if (\in_array($this->taxCode, $allowCode) === false) {
                $msg = \sprintf(
                    "For Tax type IVA the allowed codes are '%s'",
                    \join("', '", $allowCode)
                );
                $this->log->error($msg);
                throw new ATWsException($msg);
            }
        } elseif (\preg_match("/^(RED|INT|NOR|ISE|OUT|([a-zA-Z0-9.])*|NS|NA)$/", $this->taxCode) !== 1) {
            $msg = "Wrong Tax Code";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("Tax code set to " . $this->taxCode);

        if ($this->taxPercentage === null && $this->totalTaxAmount === null) {
            $msg = "Fields TaxPercentage and TotalTaxAmount are mutual exclusive, only one must be set";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if ($this->taxPercentage !== null && $this->totalTaxAmount !== null) {
            $msg = "Fields TaxPercentage and TotalTaxAmount are mutual exclusive, only one can be set";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        if (($this->taxPercentage ?? 0.0) < 0.0 || ($this->taxPercentage ?? 0.0) > 100.0) {
            $msg = "TaxPercentage cannot be lower than zero and greater than 100";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info(
            \sprintf("TaxPercentage set to %s", $this->taxPercentage ?? "null")
        );

        if (($this->totalTaxAmount ?? 0.0) < 0.0) {
            $msg = "TaxAmount cannot be lower than zero";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info(
            \sprintf("TaxAmount set to %s", $this->totalTaxAmount ?? "null")
        );

    }

    /**
     * Fee type. It must take on value:
     * VAT – Value Added Tax;
     * IS – Stamp Duty;
     * NS – Not subject to VAT or IS.
     * @return string
     * @since 2.0.0
     */
    public function getTaxType(): string
    {
        return $this->taxType;
    }

    /**
     * Must be filled in with:
     * Two-character code (alpha2) according to the ISO 3166-1 standard;
     * PT-AC – fiscal space of the Autonomous Region of the Azores;
     * PT-MA – tax area of the Autonomous Region of Madeira.
     * @return string
     * @since 2.0.0
     */
    public function getTaxCountryRegion(): string
    {
        return $this->taxCountryRegion;
    }

    /**
     * Rate code in the tax table.
     * If the tax type (TaxType) = VAT, it must be filled in with:
     * RED – Reduced rate;
     * INT – Intermediate rate;
     * NOR – Normal rate;
     * ISE – Exempt;
     * OUT – Others, applicable for special VAT regimes.
     * If the tax type (TaxType) = IS, it must be filled in with:
     * The code of the respective budget;
     * ISE – Exempt.
     * In the case of non-submission, it must be filled in with NS.
     * @return string
     * @since 2.0.0
     */
    public function getTaxCode(): string
    {
        return $this->taxCode;
    }

    /**
     * This field is mandatory if it is a tax percentage.
     * VAT rate applied:
     * It must be filled in with the percentage of the rate corresponding to the tax applicable to the fields “1.6.14.5 – Total taxable (TotalTaxBase)” or “1.6.14.6 – Amount of the line without tax in the document (Amount)”;
     * It must be filled in with “0” (zero) in the case of an exempt transmission or provision of services or in which, justifiably, there is no VAT payment.
     * This field is mutually exclusive with the field “1.6.14.7.5 – (TotalTaxAmount)”. Only one of the fields must be filled in.
     * @return float|null
     * @since 2.0.0
     */
    public function getTaxPercentage(): ?float
    {
        return $this->taxPercentage;
    }

    /**
     * Completion is mandatory, in the case of a fixed amount of Stamp Duty.
     * Calculation of the Total Tax Amount.:
     * Quantity * TaxAmount.
     * Note: fields according to SAF-T (PT).
     * This field is mutually exclusive with the field “1.6.14.7.4 – Tax Percentage (TaxPercentage)”. Only one of the fields must be filled in.
     * @return float|null
     * @since 2.0.0
     */
    public function getTotalTaxAmount(): ?float
    {
        return $this->totalTaxAmount;
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {

        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "Tax",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxType",
            null,
            $this->getTaxType()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxCountryRegion",
            null,
            $this->getTaxCountryRegion()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxCode",
            null,
            $this->getTaxCode()
        );

        if ($this->taxPercentage !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "TaxPercentage",
                null,
                \number_format(
                    $this->getTaxPercentage(), 2, ".", ""
                )
            );
        }

        if ($this->totalTaxAmount !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "TotalTaxAmount",
                null,
                \number_format(
                    $this->getTotalTaxAmount(), 2, ".", ""
                )
            );
        }

        $xml->endElement(); //Tax
    }

}
