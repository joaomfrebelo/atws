<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;

/**
 * Withholding Tax (WithholdingTax)
 * If necessary, this structure can be generated as many times as necessary.
 * @since 2.0.0
 */
class WithholdingTax
{
    /**
     * @param string $withholdingTaxType   IRS – Personal Income Tax; IRC – Corporate Income Tax; IS – Stamp Duty.
     * @param float  $withholdingTaxAmount The amount of tax withheld or to be withheld must be indicated.
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected string $withholdingTaxType,
        protected float  $withholdingTaxAmount
    )
    {
        AATWs::$logger?->debug(__METHOD__);

        $allowType = ['IRS', 'IRC', 'IS'];

        if (\in_array($this->withholdingTaxType, $allowType) === false) {
            $msg = \sprintf(
                "Withholding Tax Type only allow '%s'",
                \join("', '", $allowType)
            );
            AATWs::$logger?->error($msg);
            throw new ATWsException($msg);
        }

        AATWs::$logger?->info("Withholding Tax Type set to " . $this->withholdingTaxType);

        if ($this->withholdingTaxAmount < 0.0) {
            $msg = "Withholding Tax Amount cannot be less than zero";
            AATWs::$logger?->error($msg);
            throw new ATWsException($msg);
        }

        AATWs::$logger?->info("Withholding Tax Amount " . $this->withholdingTaxAmount);
    }

    /**
     * In this field, the type of tax withheld must be indicated,
     * IRS – Personal Income Tax;
     * IRC – Corporate Income Tax;
     * IS – Stamp Duty.
     * @return string
     * @since 2.0.0
     */
    public function getWithholdingTaxType(): string
    {
        return $this->withholdingTaxType;
    }

    /**
     * The amount of tax withheld or to be withheld must be indicated.
     * @return float
     * @since 2.0.0
     */
    public function getWithholdingTaxAmount(): float
    {
        return $this->withholdingTaxAmount;
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
            "WithholdingTax",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "WithholdingTaxType",
            null,
            $this->getWithholdingTaxType()
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "WithholdingTaxAmount",
            null,
            \number_format(
                $this->getWithholdingTaxAmount(),
                2, ".", ""
            )
        );

        $xml->endElement();
    }


}
