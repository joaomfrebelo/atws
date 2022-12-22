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
 * @since 1.0.0
 */
class FinalizeWs extends ASeriesWs implements IFinalizeWs
{

    /**
     * @var \Rebelo\ATWs\Series\FinalizeSeries
     * @since 1.0.0
     */
    private FinalizeSeries $finalizeSeries;

    /**
     * @param \Rebelo\ATWs\Series\FinalizeSeries $finalizeSeries
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submission(FinalizeSeries $finalizeSeries): Response
    {
        $this->finalizeSeries = $finalizeSeries;
        return Response::factory(
            parent::doRequest()
        );
    }

    /**
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "finalizarSerie",
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

        $xml->endElement(); //registarSerie
        $xml->endElement(); //Body
    }

    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string
    {
        return "finalizarSerie";
    }
}
