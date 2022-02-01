<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;

/**
 *
 * @since 1.0.0
 */
abstract class ASeriesWs extends AATWs
{

    /**
     * Namespace prefix
     * @since 1.0.0
     */
    const NS_REGISTARSERIE = "ns0";

    /**
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsdl(): string
    {
        $wsdl = __DIR__ . DIRECTORY_SEPARATOR . "Comunicacao_Series.wsdl";
        if (\file_exists($wsdl) === false) {
            throw new ATWsException("WSDL file not exist: " . $wsdl);
        }
        return "file://" . $wsdl;
    }

    /**
     * Get the Webservice location
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsLocation(): string
    {
        return $this->isTest ?
            "https://servicos.portaldasfinancas.gov.pt:722/SeriesWSService" :
            throw new ATWsException("Production WSDL not defined by AT");
    }
}
