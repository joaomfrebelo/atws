<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;
use Rebelo\Date\Date;

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
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
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
     * @return void
     * @throws \Rebelo\Date\DateFormatException
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
            $this->seriesRegister->getSeriesTypeCode()->get()
        );

        $xml->writeElementNs(
            null,
            "classeDoc",
            null,
            $this->seriesRegister->getDocumentTypeCode()->getDocumentClassCode()->get()
        );

        $xml->writeElementNs(
            null,
            "tipoDoc",
            null,
            $this->seriesRegister->getDocumentTypeCode()->get()
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
            $this->seriesRegister->getExpectedInitialDateUse()->format(Date::SQL_DATE)
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
            $this->seriesRegister->getProcessingMediumCode()->get()
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
