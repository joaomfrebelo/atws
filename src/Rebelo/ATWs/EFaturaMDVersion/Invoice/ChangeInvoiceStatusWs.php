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
 * Change Invoice Status Webservice
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class ChangeInvoiceStatusWs extends AWs implements IChangeInvoiceStatusWs
{

    /**
     *
     * @var \Rebelo\ATWs\EFaturaMDVersion\Invoice\ChangeInvoiceStatus
     * @since 1.0.0
     */
    protected ChangeInvoiceStatus $changeInvoiceStatus;

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
            "ChangeInvoiceStatusRequest",
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
            $this->changeInvoiceStatus->getTaxRegistrationNumber()
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "InvoiceHeader",
            null
        );

        $this->changeInvoiceStatus->getInvoiceHeader()->buildXml($xml);

        $xml->endElement(); //InvoiceHeader

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "InvoiceStatus",
            null
        );

        $this->changeInvoiceStatus->getNewInvoiceStatus()->buildXml($xml);

        $xml->endElement(); //NewInvoiceStatusType

        $this->changeInvoiceStatus->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //ChangeInvoiceStatusRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit the change invoice status to the AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\ChangeInvoiceStatus $changeInvoiceStatus
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(ChangeInvoiceStatus $changeInvoiceStatus): Response
    {
        $this->changeInvoiceStatus = $changeInvoiceStatus;
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
        return "ChangeInvoiceStatus";
    }

}
