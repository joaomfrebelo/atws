<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
/**
 * The src folder
 */
const ATWS_SRC = __DIR__;

/**
 * AT public key file path
 */
define(
    "ATWS_AT_PUB_KEY", ATWS_SRC . DIRECTORY_SEPARATOR . \join(
        DIRECTORY_SEPARATOR, ["Rebelo", "ATWs", "atpubkey.pem"]
    )
);

/**
 * Schema for the SOAP/1.1 envelope
 */
define(
    "ATWS_SOAP_ENVELOPE_SCHEMA", ATWS_SRC . DIRECTORY_SEPARATOR . \join(
        DIRECTORY_SEPARATOR, ["Rebelo", "ATWs", "schemas.xmlsoap.xsd"]
    )
);
