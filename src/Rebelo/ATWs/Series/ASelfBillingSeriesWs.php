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
 * Self billing series register base class
 * @since 2.0.2
 */
abstract class ASelfBillingSeriesWs extends AATWs
{

    /**
     * Namespace prefix
     * @since 2.0.2
     */
    const NS_REGISTARSERIE = "ns0";

    /**
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function getWsdl(): string
    {
        $wsdl = __DIR__ . DIRECTORY_SEPARATOR . "SeriesAutoFaturacaoWSService.wsdl";
        if (\file_exists($wsdl) === false) {
            throw new ATWsException("WSDL file not exist: " . $wsdl);
        }
        return "file://" . $wsdl;
    }

    /**
     * Get the Webservice location
     * @return string
     * @since 2.0.2
     */
    public function getWsLocation(): string
    {
        return $this->isTest ?
            "https://servicos.portaldasfinancas.gov.pt:722/SeriesAutoFaturacaoWSService" :
            "https://servicos.portaldasfinancas.gov.pt:422/SeriesAutoFaturacaoWSService";
    }
}
