<?php

namespace Rebelo\ATWs\Series;


/**
 *
 * @since 2.0.1
 */
interface ICancelWs
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
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsdl(): string;

    /**
     * Get the Webservice location
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsLocation(): string;

    /**
     * @param \Rebelo\ATWs\Series\CancelSeries $cancelSeries
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submission(CancelSeries $cancelSeries): Response;

    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string;
}
