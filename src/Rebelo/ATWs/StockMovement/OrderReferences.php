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
class OrderReferences
{
    /**
     * Order References
     *
     * @param string $originatingOn Origin document
     * @param string $movementType  Document type
     * @since 1.0.0
     */
    public function __construct(
        protected string $originatingOn,
        protected string $movementType
    )
    {
        AATWs::$logger?->debug(__METHOD__);
        AATWs::$logger?->debug("OriginatingOn set to: " . $originatingOn);
        AATWs::$logger?->debug("MovementType set to: " . $movementType);
    }

    /**
     * Get OriginatingOn
     * @return string
     * @since 1.0.0
     */
    public function getOriginatingOn(): string
    {
        return $this->originatingOn;
    }

    /**
     * get Movement Type
     * @return string
     * @since 1.0.0
     */
    public function getMovementType(): string
    {
        return $this->movementType;
    }

}
