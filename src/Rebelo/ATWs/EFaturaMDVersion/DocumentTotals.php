<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\AATWs;

/**
 * DocumentTotals
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class DocumentTotals
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * DocumentTotals
     * @param float $taxPayable Amount of tax payable
     * @param float $netTotal   Total document without tax
     * @param float $grossTotal Total document with tax
     * @since 1.0.0
     */
    public function __construct(
        protected float $taxPayable,
        protected float $netTotal,
        protected float $grossTotal
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
    }

    /**
     * Amount of tax payable.
     * Only include the taxes included in the summary lines by
     * fee in “1.7 - Document Lines by Fee (Line)”
     * @return float
     * @since 1.0.0
     */
    public function getTaxPayable(): float
    {
        return $this->taxPayable;
    }

    /**
     * Total document without tax
     * @return float
     * @since 1.0.0
     */
    public function getNetTotal(): float
    {
        return $this->netTotal;
    }

    /**
     * Total document with tax.
     * It must include the taxable amount and all taxes
     * applicable to the document, even if not included in
     * the summary lines for fee in “1.7 - Document Lines for Fee (Line)”.
     * @return float
     * @since 1.0.0
     */
    public function getGrossTotal(): float
    {
        return $this->grossTotal;
    }

    /**
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "DocumentTotals",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "TaxPayable",
            null,
            \number_format(
                $this->taxPayable,
                AATWs::DECIMALS, ".", ""
            )
        );
        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "NetTotal",
            null,
            \number_format(
                $this->netTotal,
                AATWs::DECIMALS, ".", ""
            )
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "GrossTotal",
            null,
            \number_format(
                $this->grossTotal,
                2, ".", ""
            )
        );

        $xml->endElement(); //DocumentTotals
    }

}
