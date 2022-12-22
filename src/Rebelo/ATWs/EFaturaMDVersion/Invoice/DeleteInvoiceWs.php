<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Delete Invoice Webservice
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class DeleteInvoiceWs extends AWs implements IDeleteInvoiceWs
{

    /**
     *
     * @var \Rebelo\ATWs\EFaturaMDVersion\Invoice\DeleteInvoice
     * @since 1.0.0
     */
    protected DeleteInvoice $deleteInvoice;

    /**
     * Build the invoice request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AATWs::NS_ENVELOPE,
            "Body", null
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "DeleteInvoiceRequest",
            "http://factemi.at.min_financas.pt/documents"
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "eFaturaMDVersion",
            null,
            static::E_FATURA_MD_VERSION
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxRegistrationNumber",
            null,
            $this->deleteInvoice->getTaxRegistrationNumber()
        );

        if (\count($this->deleteInvoice->getDocumentList() ?? []) > 0) {

            $xml->startElementNs(
                static::NS_AT_WS_BODY,
                "documentsList",
                null
            );

            foreach ($this->deleteInvoice->getDocumentList() as $invoice) {

                $xml->startElementNs(
                    static::NS_AT_WS_BODY,
                    "invoice",
                    null
                );

                $invoice->buildXml($xml);

                $xml->endElement();
            }

            $xml->endElement();
        }

        $this->deleteInvoice->getDateRange()?->buildXml($xml);

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "reason",
            null,
            $this->deleteInvoice->getReason()
        );

        $this->deleteInvoice->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //ChangeInvoiceStatusRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit to the AT webservice the invoices to delete
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\DeleteInvoice $deleteInvoice
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(DeleteInvoice $deleteInvoice): Response
    {
        $this->deleteInvoice = $deleteInvoice;
        return Response::factory(
            parent::doRequest()
        );
    }


    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string
    {
        return "DeleteInvoice";
    }

}
