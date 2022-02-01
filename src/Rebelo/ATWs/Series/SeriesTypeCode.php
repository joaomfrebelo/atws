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
 * Series type codes
 * @method static SeriesTypeCode N() Series to normal use
 * @method static SeriesTypeCode F() Series to use in training mode
 * @method static SeriesTypeCode R() Series to recover documents
 * @since 1.0.0
 */
class SeriesTypeCode extends AEnum
{
    /**
     * Series to normal use
     * @since 1.0.0
     */
    const N = "N";

    /**
     * Series to use in training mode
     * @since 1.0.0
     */
    const F = "F";

    /**
     * Series to recover documents
     * @since 1.0.0
     */
    const R = "R";

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
