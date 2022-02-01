<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs;

use JetBrains\PhpStorm\Pure;

/**
 * ATWsException
 *
 * @author João Rebelo
 */
class ATWsException extends \Exception
{

    /**
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @since 1.0.0
     */
    #[Pure] public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
