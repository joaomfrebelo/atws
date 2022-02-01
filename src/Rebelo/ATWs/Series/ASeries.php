<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

/**
 *
 * @since 1.0.0
 */
class ASeries
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    public function __construct()
    {
        $this->log = \Logger::getLogger(\get_class($this));
    }

    /**
     * Validate the SeriesInitialSequenceNumber
     * @param int $seriesInitialSequenceNumber
     * @return bool
     * @since 1.0.0
     */
    public static function isValidSeriesInitialSequenceNumber(int $seriesInitialSequenceNumber): bool
    {
        if ($seriesInitialSequenceNumber < 1) {
            return false;
        }
        if (\strlen((string)$seriesInitialSequenceNumber) > 25) {
            return false;
        }
        return true;
    }

    /**
     * Validate teg Series identifier
     * @param string $series
     * @return bool
     * @since 1.0.0
     */
    public static function isValidSeries(string $series): bool
    {
        $regExp = "/^([A-Za-z0-9]+([A-Za-z0-9._-]+[A-Za-z0-9])?){1,35}$/";
        return \preg_match($regExp, $series) === 1;
    }

}
