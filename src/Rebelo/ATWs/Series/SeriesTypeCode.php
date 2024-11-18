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
 * @since 1.0.0
 */
enum SeriesTypeCode: string
{
    /**
     * Series to normal use
     * @since 1.0.0
     */
    case N = "N";

    /**
     * Series to use in training mode
     * @since 1.0.0
     */
    case F = "F";

    /**
     * Series to recover documents
     * @since 1.0.0
     */
    case R = "R";

}
