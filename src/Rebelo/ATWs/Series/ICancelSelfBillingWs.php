<?php

namespace Rebelo\ATWs\Series;


/**
 *
 * @since 2.0.2
 */
interface ICancelSelfBillingWs
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
     * @since 2.0.2
     */
    public function getWsdl(): string;

    /**
     * Get the Webservice location
     * @return string
     * @since 2.0.2
     */
    public function getWsLocation(): string;

    /**
     * @param \Rebelo\ATWs\Series\CancelSelfBillingSeries $cancelSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(CancelSelfBillingSeries $cancelSeries): SelfBillingResponse;

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string;
}
