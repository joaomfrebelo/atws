<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use Rebelo\Enum\AEnum;

/**
 * Processing Medium Codes
 * @method static ProcessingMediumCodes PI() Billing Computer Program
 * @method static ProcessingMediumCodes PF() Tax Authority portal
 * @method static ProcessingMediumCodes OM() Other electronic mediums
 * @noinspection all
 */
class ProcessingMediumCodes extends AEnum
{

    /**
     * Billing Computer Program
     * @since 1.0.0
     */
    const PI = "PI";

    /**
     * Tax Authority portal
     * @since 1.0.0
     */
    const PF = "PF";

    /**
     * Other electronic mediums
     * @since 1.0.0
     */
    const OM = "OM";

    /**
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the string value
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string)parent::get();
    }
}
