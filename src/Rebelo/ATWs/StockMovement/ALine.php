<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use Rebelo\ATWs\AATWs;

/**
 * Line
 *
 * @author João Rebelo
 * @since  1.0.0
 */
abstract class ALine
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * Document lines with goods (Line)
     *
     * @param string     $productDescription Description of the invoice line, linked to the product table and services
     * @param float      $quantity
     * @param string     $unitOfMeasure
     * @param float|null $unitPrice
     * @since 1.0.0
     */
    public function __construct(
        protected string $productDescription,
        protected float  $quantity,
        protected string $unitOfMeasure,
        protected ?float $unitPrice
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->debug("ProductDescription set to :" . $productDescription);
        $this->log->debug("Quantity set to :" . $quantity);
        $this->log->debug("UnitOfMeasure set to:" . $unitOfMeasure);
        $this->log->debug("UnitPrice amount set to :" . $unitPrice);
    }

    /**
     * Get product description
     * @return string
     * @since 1.0.0
     */
    public function getProductDescription(): string
    {
        return $this->productDescription;
    }

    /**
     * Get quantity
     * @return float
     * @since 1.0.0
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Get unit of measure
     * @return string
     * @since 1.0.0
     */
    public function getUnitOfMeasure(): string
    {
        return $this->unitOfMeasure;
    }

    /**
     * Get unit price
     * @return float
     * @since 1.0.0
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * Build the Line xml body
     * @param \XMLWriter $xml
     * @return void
     * @since 1.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->writeElement(
            "ProductDescription",
            $this->getProductDescription()
        );

        $xml->writeElement(
            "Quantity",
            \number_format($this->getQuantity(), AATWs::DECIMALS, ".", "")
        );

        $xml->writeElement(
            "UnitOfMeasure",
            $this->getUnitOfMeasure()
        );

        $xml->writeElement(
            "UnitPrice",
            \number_format($this->getUnitPrice(), AATWs::DECIMALS, ".", "")
        );
    }

}
