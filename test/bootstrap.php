<?php
/**
 * MIT License
 * @license https://github.com/joaomfrebelo@gmail.com/at-ws/blob/master/LICENSE
 * Copyright (c) 2021 Joao M F Rebelo
 */

require_once __DIR__
    . DIRECTORY_SEPARATOR . ".."
    . DIRECTORY_SEPARATOR . "vendor"
    . DIRECTORY_SEPARATOR . "autoload.php";

require_once __DIR__
    . DIRECTORY_SEPARATOR . 'Base.php';

/**
 * Indicate that is a UNITTEST running
 */
const IS_UNIT_TEST = true;

/**
 * The test resources folder
 */
const ATWS_RESOURCES_DIR = __DIR__ . DIRECTORY_SEPARATOR . "Resources";

/**
 * The XML Responses examples folder
 */
define(
    "ATWS_INVOICE_RESPONSE_DIR",
    \join(DIRECTORY_SEPARATOR, [ATWS_RESOURCES_DIR, "Responses", "Invoice"])
);

/**
 * The XML Responses examples folder
 */
define(
    "ATWS_SERIES_RESPONSE_DIR",
    \join(DIRECTORY_SEPARATOR, [ATWS_RESOURCES_DIR, "Responses", "Series"])
);

/**
 * The XML Responses examples folder
 */
define(
    "ATWS_STOCK_MOVEMENT_RESPONSE_DIR",
    \join(DIRECTORY_SEPARATOR, [ATWS_RESOURCES_DIR, "Responses", "StockMovement"])
);

/**
 * The AT WS Certificate test
 */
define(
    "ATWS_TEST_CERTIFICATE",
    \join(DIRECTORY_SEPARATOR, [ATWS_RESOURCES_DIR, "Certificates", "TestWebservices.pem"])
);

/**
 * The AT WS Certificate test
 */
const ATWS_TEST_CERTIFICATE_PASSPHRASE = "TESTEwebservice";

/**
 * The path of AT portal credentials file
 */
const ATWS_TEST_CREDENTIALS = __DIR__ . DIRECTORY_SEPARATOR . "AtWebservicesTestCredentials.ini";

\spl_autoload_register(
    function ($class) {
        if (\str_starts_with("\\", $class)) {
            /** @var string Class name Stripped of the first backslash */
            $class = \substr($class, 1, \strlen($class) - 1);
        }

        $pathBase = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
        $pathSrc = $pathBase . "src" . DIRECTORY_SEPARATOR . $class . ".php";
        if (is_file($pathSrc)) {
            require_once $pathSrc;
            return;
        }

        $pathTests = $pathBase . "tests" . DIRECTORY_SEPARATOR . $class . ".php";
        if (is_file($pathTests)) {
            require_once $pathTests;
        }
    }
);

