<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

/**
 * The results returned by the operation.
 * @since 1.0.0
 */
class OperationResultInformation
{

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * The results returned by the operation.
     * @param int    $operationResultCode    Operation result code
     * @param string $operationResultMessage Operation result message
     * @since 1.0.0
     */
    public function __construct(
        private readonly int $operationResultCode,
        private readonly string $operationResultMessage
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug("OperationResultCode: " . $this->operationResultCode);
        $this->log->debug("OperationResultMessage: " . $this->operationResultMessage);
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
