<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

/**
 * @since 2.0.0
 */
class RecordChannel
{
    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param string      $system
     * @param string|null $version
     * @since 2.0.0
     */
    public function __construct(
        protected string  $system,
        protected ?string $version
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("System set to " . $this->system);
        $this->log->info("Version set to " . ($this->version ?? "null"));

    }

    /**
     * @return string
     * @since 2.0.0
     */
    public function getSystem(): string
    {
        return $this->system;
    }

    /**
     * @return string|null
     * @since 2.0.0
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @since 2.0.0
     */
    public function buildXml(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AWs::NS_AT_WS_BODY,
            "CanalRegisto",
            null
        );

        $xml->writeElementNs(
            AWs::NS_AT_WS_BODY,
            "Sistema",
            null,
            $this->system
        );

        if ($this->version !== null) {
            $xml->writeElementNs(
                AWs::NS_AT_WS_BODY,
                "Versao",
                null,
                $this->version
            );
        }

        $xml->endElement();
    }

}
