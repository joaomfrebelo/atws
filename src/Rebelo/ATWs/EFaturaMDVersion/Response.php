<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\AResponse;

/**
 * Response
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Response extends AResponse
{

    /**
     *
     * @param string $xml
     * @return static
     * @throws \Rebelo\ATWs\ATWsException
     * @since        1.0.0
     * @noinspection all
     */
    public static function factory(string $xml): static
    {
        return parent::factory($xml);//@phpstan-ignore-line
    }

}
