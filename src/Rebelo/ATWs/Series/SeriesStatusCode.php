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
 * Series status codes
 * @method static SeriesStatusCode A() Series in Active status
 * @method static SeriesStatusCode N() Series in Canceled status code
 * @method static SeriesStatusCode F() Series in Finalized status code
 * @since        1.0.0
 * @noinspection all
 */
class SeriesStatusCode extends AEnum
{
    /**
     * Series in Active status
     * @since 1.0.0
     */
    const A = "A";

    /**
     * Series in Canceled status code
     * @since 1.0.0
     */
    const N = "N";

    /**
     * Series in Finalized status code
     * @since 1.0.0
     */
    const F = "F";

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
