<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;

/**
 *
 * @since 2.0.2
 */
class CancelSelfBillingWs extends ASelfBillingSeriesWs implements ICancelSelfBillingWs
{

    /**
     * @var \Rebelo\ATWs\Series\CancelSelfBillingSeries
     * @since 2.0.2
     */
    protected CancelSelfBillingSeries $cancelSeries;

    /**
     * @param \Rebelo\ATWs\Series\CancelSelfBillingSeries $cancelSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(CancelSelfBillingSeries $cancelSeries): SelfBillingResponse
    {
        $this->cancelSeries = $cancelSeries;
        return SelfBillingResponse::factory(
            parent::doRequest()
        );
    }

    /**
     * @param \XMLWriter $xml
     *
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "anularSerieAutofaturacao",
            "http://at.gov.pt/"
        );

        $xml->writeElementNs(
            null,
            "serie",
            null,
            $this->cancelSeries->getSeries()
        );

        $xml->writeElementNs(
            null,
            "classeDoc",
            null,
            $this->cancelSeries->getDocumentTypeCode()->getDocumentClassCode()->get()
        );

        $xml->writeElementNs(
            null,
            "tipoDoc",
            null,
            $this->cancelSeries->getDocumentTypeCode()->get()
        );

        $xml->writeElementNs(
            null,
            "codValidacaoSerie",
            null,
            $this->cancelSeries->getSeriesValidationCode()
        );

        $xml->writeElementNs(
            null,
            "motivo",
            null,
            "ER"
        );

        $xml->writeElementNs(
            null,
            "declaracaoNaoEmissao",
            null,
            $this->cancelSeries->isNoIssueDeclaration() ? "true" : "false"
        );

        $xml->writeElementNs(
            null,
            "acordoRegistadoCom",
            null,
            $this->cancelSeries->getSelfBillingEntityCode()->get()
        );

        $xml->writeElementNs(
            null,
            "nifAssociadoAoAcordo",
            null,
            $this->cancelSeries->getSelfBillingEntityCode()->get()
        );

        $xml->endElement(); //registarSerie
        $xml->endElement(); //Body
    }

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string
    {
        return "anularSerieAutofaturacao";
    }
}
