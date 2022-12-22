<?php

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;


use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Delete Invoice Webservice
 *
 * @author João Rebelo
 * @since  2.0.1
 */
interface IDeleteInvoiceWs
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
     * Submit to the AT webservice the invoices to delete
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\DeleteInvoice $deleteInvoice
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(DeleteInvoice $deleteInvoice): Response;

    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string;
}
