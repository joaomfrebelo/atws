<?php

namespace Rebelo\ATWs\Series;


/**
 * Self billing series WS client register
 * @since 2.0.2
 */
interface ISelfBillingRegisterWs
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
     * @since 1.0.0
     */
    public function getWsLocation(): string;

    /**
     * Submit the request to the webservice
     *
     * @param \Rebelo\ATWs\Series\SelfBillingSeriesRegister $seriesRegister
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(SelfBillingSeriesRegister $seriesRegister): SelfBillingResponse;

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string;
}
