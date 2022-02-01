<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs;

use JetBrains\PhpStorm\ArrayShape;

/**
 *
 */
trait TCredentials
{
    /**
     * The AT portal credentials
     * @var array
     */
    #[ArrayShape(["username" => "string", "password" => "string"])]
    public static array $credentials = [];

    /**
     * The tax registration number extracted from the credentials username
     * @var string
     */
    public static string $taxRegistrationNumber;

    /**
     * @beforeClass
     * @throws \Exception
     */
    public static function before(): void
    {
        if (false === $credentials = \parse_ini_file(ATWS_TEST_CREDENTIALS)) {
            throw new \Exception("Fail opening credentials file . " . ATWS_TEST_CREDENTIALS);
        }
        static::$credentials = $credentials;
        list(static::$taxRegistrationNumber,) = \explode("/", static::$credentials["username"]);
    }
}
