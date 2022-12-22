<?php

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;


use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Change Invoice Status Webservice
 *
 * @author João Rebelo
 * @since  2.0.1
 */
interface IChangeInvoiceStatusWs
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
     * Submit the change invoice status to the AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\ChangeInvoiceStatus $changeInvoiceStatus
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(ChangeInvoiceStatus $changeInvoiceStatus): Response;

    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string;
}
