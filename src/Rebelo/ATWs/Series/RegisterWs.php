<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;
use Rebelo\Date\Pattern;

/**
 *
 * @since 1.0.0
 */
class RegisterWs extends ASeriesWs implements IRegisterWs
{
    /**
     * @var \Rebelo\ATWs\Series\SeriesRegister
     * @since 1.0.0
     */
    protected SeriesRegister $seriesRegister;

    /**
     * Submit the request to the webservice
     *
     * @param \Rebelo\ATWs\Series\SeriesRegister $seriesRegister
     *
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function submission(SeriesRegister $seriesRegister): Response
    {
        $this->seriesRegister = $seriesRegister;
        return Response::factory(
            parent::doRequest()
        );
    }

    /**
     * @param \XMLWriter $xml
     *
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "registarSerie",
            "http://at.gov.pt/"
        );

        $xml->writeElementNs(
            null,
            "serie",
            null,
            $this->seriesRegister->getSeries()
        );

        $xml->writeElementNs(
            null,
            "tipoSerie",
            null,
            $this->seriesRegister->getSeriesTypeCode()->value
        );

        $xml->writeElementNs(
            null,
            "classeDoc",
            null,
            $this->seriesRegister->getDocumentTypeCode()->getDocumentClassCode()->value
        );

        $xml->writeElementNs(
            null,
            "tipoDoc",
            null,
            $this->seriesRegister->getDocumentTypeCode()->value
        );

        $xml->writeElementNs(
            null,
            "numInicialSeq",
            null,
            (string)$this->seriesRegister->getSeriesInitialSequenceNumber()
        );

        $xml->writeElementNs(
            null,
            "dataInicioPrevUtiliz",
            null,
            $this->seriesRegister->getExpectedInitialDateUse()->format(Pattern::SQL_DATE)
        );

        $xml->writeElementNs(
            null,
            "numCertSWFatur",
            null,
            (string)$this->seriesRegister->getSoftwareCertificate()
        );

        $xml->writeElementNs(
            null,
            "meioProcessamento",
            null,
            $this->seriesRegister->getProcessingMediumCode()->value
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
        return "registarSerie";
    }

}
