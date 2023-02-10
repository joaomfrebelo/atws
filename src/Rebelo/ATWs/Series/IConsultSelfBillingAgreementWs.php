<?php

namespace Rebelo\ATWs\Series;


/**
 * Consult Self billing Agreement Webservice client
 * @since 2.0.2
 */
interface IConsultSelfBillingAgreementWs
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
     * @param \Rebelo\ATWs\Series\ConsultSelfBillingAgreement $consultSelfBillingAgreement
     *
     * @return \Rebelo\ATWs\Series\ConsultSelfBillingAgreementResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(ConsultSelfBillingAgreement $consultSelfBillingAgreement): ConsultSelfBillingAgreementResponse;

    /**
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string;
}
