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
 * Consult Webservice
 * @since 1.0.0
 */
class ConsultWs extends ASeriesWs implements IConsultWs
{

    /**
     * @var \Rebelo\ATWs\Series\ConsultSeries
     * @since 1.0.0
     */
    protected ConsultSeries $consultSeries;

    /**
     * Submit the consult series to the web service
     * @param \XMLWriter $xml
     * @return void
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "consultarSeries",
            "http://at.gov.pt/"
        );

        if($this->consultSeries->getSeries() !== null){
            $xml->writeElementNs(
                null,
                "serie",
                null,
                $this->consultSeries->getSeries()
            );
        }

        if($this->consultSeries->getSeriesTypeCode() !== null){
            $xml->writeElementNs(
                null,
                "tipoSerie",
                null,
                $this->consultSeries->getSeriesTypeCode()->value
            );
        }

        if($this->consultSeries->getDocumentClassCode() !== null){
            $xml->writeElementNs(
                null,
                "classeDoc",
                null,
                $this->consultSeries->getDocumentClassCode()->value
            );
        }

        if($this->consultSeries->getDocumentTypeCode() !== null){
            $xml->writeElementNs(
                null,
                "tipoDoc",
                null,
                $this->consultSeries->getDocumentTypeCode()->value
            );
        }

        if($this->consultSeries->getSeriesValidationCode() !== null){
            $xml->writeElementNs(
                null,
                "codValidacaoSerie",
                null,
                $this->consultSeries->getSeriesValidationCode()
            );
        }

        if($this->consultSeries->getFromRegistrationDate() !== null){
            $xml->writeElementNs(
                null,
                "dataRegistoDe",
                null,
                $this->consultSeries->getFromRegistrationDate()->format(Pattern::SQL_DATE)
            );
        }

        if($this->consultSeries->getToRegistrationDate() !== null){
            $xml->writeElementNs(
                null,
                "dataRegistoAte",
                null,
                $this->consultSeries->getToRegistrationDate()->format(Pattern::SQL_DATE)
            );
        }

        if($this->consultSeries->getSeriesStatusCode() !== null){
            $xml->writeElementNs(
                null,
                "estado",
                null,
                $this->consultSeries->getSeriesStatusCode()->value
            );
        }

        if($this->consultSeries->getProcessingMediumCodes() !== null){
            $xml->writeElementNs(
                null,
                "meioProcessamento",
                null,
                $this->consultSeries->getProcessingMediumCodes()->value
            );
        }

        $xml->endElement(); //registarSerie
        $xml->endElement(); //Body
    }


    /**
     * @param \Rebelo\ATWs\Series\ConsultSeries $consultSeries
     *
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function submission(ConsultSeries $consultSeries): Response
    {
        $this->consultSeries = $consultSeries;
        return Response::factory(
            parent::doRequest()
        );
    }
    /**
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string
    {
       return "consultarSeries";
    }
}
