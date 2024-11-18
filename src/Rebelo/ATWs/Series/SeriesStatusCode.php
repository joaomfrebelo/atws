<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

/**
 * Series status codes
 * @since        1.0.0
 * @noinspection all
 */
enum SeriesStatusCode: string
{
    /**
     * Series in Active status
     * @since 1.0.0
     */
    case A = "A";

    /**
     * Series in Canceled status code
     * @since 1.0.0
     */
    case N = "N";

    /**
     * Series in Finalized status code
     * @since 1.0.0
     */
    case F = "F";

}
