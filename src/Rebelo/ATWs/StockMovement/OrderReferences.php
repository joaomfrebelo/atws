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
class OrderReferences
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

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
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug("OriginatingOn set to: " . $originatingOn);
        $this->log->debug("MovementType set to: " . $movementType);
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
