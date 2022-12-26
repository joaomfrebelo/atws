<?php

namespace Rebelo\ATWs\Series;


/**
 * Finalize series webservice
 * @since 2.0.2
 */
interface IFinalizeSelfBillingWs
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
     * @param \Rebelo\ATWs\Series\FinalizeSelfBillingSeries $finalizeSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(FinalizeSelfBillingSeries $finalizeSeries): SelfBillingResponse;

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string;
}
