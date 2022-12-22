<?php

namespace Rebelo\ATWs\StockMovement;


/**
 * StockMovement Ws
 *
 * @author João Rebelo
 * @since  2.0.1
 */
interface IWs
{
    /**
     * Do the SOAP request
     *
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function doRequest(): string;

    /**
     * Build the Stock Movement request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodyStockMovement(\XMLWriter $xml): void;

    /**
     * Build the Prior Agricultural request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodyPriorAgricultural(\XMLWriter $xml): void;

    /**
     * Build the Subsequent Agricultural request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodySubsequentAgricultural(\XMLWriter $xml): void;

    /**
     * Submit the stock movement
     * @param \Rebelo\ATWs\StockMovement\StockMovement|\Rebelo\ATWs\StockMovement\PriorAgriculturalStockMovement|\Rebelo\ATWs\StockMovement\SubsequentAgriculturalStockMovement $stockMovement
     * @return \Rebelo\ATWs\StockMovement\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(StockMovement|PriorAgriculturalStockMovement|SubsequentAgriculturalStockMovement $stockMovement): Response;

    /**
     * Get the StockMovement webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsdl(): string;

    /**
     * Get the Webservice action
     * @return string
     * @since        1.0.0
     * @noinspection
     */
    public function getWsAction(): string;

    /**
     * Get the Webservice location
     * @return string
     * @since        1.0.0
     */
    public function getWsLocation(): string;
}
