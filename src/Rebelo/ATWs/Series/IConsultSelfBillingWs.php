<?php

namespace Rebelo\ATWs\Series;


/**
 * Consult Webservice
 * @since 2.0.2
 */
interface IConsultSelfBillingWs
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
     * @param \Rebelo\ATWs\Series\ConsultSelfBillingSeries $consultSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(ConsultSelfBillingSeries $consultSeries): SelfBillingResponse;

    /**
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string;
}
