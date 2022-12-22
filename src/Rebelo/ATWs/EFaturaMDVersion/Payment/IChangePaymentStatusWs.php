<?php

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;


use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Change Payment Status Ws
 *
 * @author João Rebelo
 * @since  2.0.1
 */
interface IChangePaymentStatusWs
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
     * Get the Webservice location
     * @return string
     * @since 2.0.0
     */
    public function getWsLocation(): string;

    /**
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function getWsdl(): string;

    /**
     * Get the Audit File Version
     * @return string
     * @since 2.0.0
     */
    public function getAuditFileVersion(): string;

    /**
     * Submit the change document status to teh AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\ChangePaymentStatus $changePaymentStatus
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(ChangePaymentStatus $changePaymentStatus): Response;

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.0
     */
    public function getWsAction(): string;
}
