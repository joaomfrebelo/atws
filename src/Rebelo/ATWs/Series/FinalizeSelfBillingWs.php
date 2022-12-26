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
 * Finalize series webservice
 * @since 2.0.2
 */
class FinalizeSelfBillingWs extends ASelfBillingSeriesWs implements IFinalizeSelfBillingWs
{

    /**
     * @var \Rebelo\ATWs\Series\FinalizeSelfBillingSeries
     * @since 2.0.2
     */
    private FinalizeSelfBillingSeries $finalizeSeries;

    /**
     * @param \Rebelo\ATWs\Series\FinalizeSelfBillingSeries $finalizeSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(FinalizeSelfBillingSeries $finalizeSeries): SelfBillingResponse
    {
        $this->finalizeSeries = $finalizeSeries;
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
            "finalizarSerieAutofaturacao",
            "http://at.gov.pt/"
        );

        $xml->writeElementNs(
            null,
            "serie",
            null,
            $this->finalizeSeries->getSeries()
        );

        $xml->writeElementNs(
            null,
            "classeDoc",
            null,
            $this->finalizeSeries->getDocumentTypeCode()->getDocumentClassCode()->get(),
        );

        $xml->writeElementNs(
            null,
            "tipoDoc",
            null,
            $this->finalizeSeries->getDocumentTypeCode()->get()
        );

        $xml->writeElementNs(
            null,
            "codValidacaoSerie",
            null,
            $this->finalizeSeries->getSeriesValidationCode()
        );

        $xml->writeElementNs(
            null,
            "seqUltimoDocEmitido",
            null,
            (string)$this->finalizeSeries->getLastSequenceDocNumber()
        );

        if ($this->finalizeSeries->getReason() !== null) {
            $xml->writeElementNs(
                null,
                "justificacao",
                null,
                (string)$this->finalizeSeries->getReason()
            );
        }

        $xml->writeElementNs(
            null,
            "acordoRegistadoCom",
            null,
            $this->finalizeSeries->getSelfBillingEntityCode()->get()
        );

        $xml->writeElementNs(
            null,
            "nifAssociadoAoAcordo",
            null,
            $this->finalizeSeries->getSupplierTin()
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
        return "finalizarSerieAutofaturacao";
    }
}
