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
 * @since 1.0.0
 */
class CancelWs extends ASeriesWs implements ICancelWs
{

    /**
     * @var \Rebelo\ATWs\Series\CancelSeries
     * @since 1.0.0
     */
    protected CancelSeries $cancelSeries;

    /**
     * @param \Rebelo\ATWs\Series\CancelSeries $cancelSeries
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submission(CancelSeries $cancelSeries): Response
    {
        $this->cancelSeries = $cancelSeries;
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
            "anularSerie",
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
        return "anularSerie";
    }
}
