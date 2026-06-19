<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;

/**
 * The results returned by the operation.
 *
 * @since 1.0.0
 */
readonly class OperationResultInformation
{
    /**
     * The results returned by the operation.
     * @param int    $operationResultCode    Operation result code
     * @param string $operationResultMessage Operation result message
     * @since 1.0.0
     */
    public function __construct(
        private int    $operationResultCode,
        private string $operationResultMessage
    )
    {
        AATWs::$logger?->debug("OperationResultCode: " . $this->operationResultCode);
        AATWs::$logger?->debug("OperationResultMessage: " . $this->operationResultMessage);
    }

    /**
     * Operation result code
     * @return int
     * @since 1.0.0
     */
    public function getOperationResultCode(): int
    {
        return $this->operationResultCode;
    }

    /**
     * Operation result message
     * @return string
     * @since 1.0.0
     */
    public function getOperationResultMessage(): string
    {
        return $this->operationResultMessage;
    }


}
