<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

/**
 * Line
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Line extends ALine
{

    /**
     * Document lines with goods (Line)
     *
     * @param string   $productDescription Description of the invoice line, linked to the product table and services
     * @param float    $quantity
     * @param string   $unitOfMeasure
     * @param float    $unitPrice          Description of the invoice line, linked to the product table Unit price without tax and less discounts line and header. In non-valued documents must be completed with << 0.00 >>.
     * @param string[] $originatingON      Reference to source document (OrderReferences)
     * @since 1.0.0
     */
    public function __construct(
        protected string $productDescription,
        protected float  $quantity,
        protected string $unitOfMeasure,
        float            $unitPrice,
        protected array  $originatingON = []
    )
    {
        parent::__construct($productDescription, $quantity, $unitOfMeasure, $unitPrice);
        foreach ($originatingON as $ordRef) {
            $this->log->debug("OriginatingON set to :" . $ordRef);
        }
    }

    /**
     * Get OriginatingON (Order References)
     * @return string[]
     * @since 1.0.0
     */
    public function getOriginatingON(): array
    {
        return $this->originatingON;
    }

    /**
     * Build the Line xml body
     * @param \XMLWriter $xml
     * @return void
     * @since 1.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        if (\count($this->originatingON) > 0) {
            $xml->startElement("OrderReferences");
            foreach ($this->originatingON as $originatingON) {
                $xml->writeElement(
                    "OriginatingON", $originatingON
                );
            }
            $xml->endElement();
        }
        parent::buildXml($xml);
    }

}
