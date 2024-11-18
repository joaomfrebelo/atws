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
 * @since 2.0.2
 */
class ConsultSelfBillingWs extends ASelfBillingSeriesWs implements IConsultSelfBillingWs
{

    /**
     * @var \Rebelo\ATWs\Series\ConsultSelfBillingSeries
     * @since 2.0.2
     */
    protected ConsultSelfBillingSeries $consultSeries;

    /**
     * Submit the consult series to the web service
     *
     * @param \XMLWriter $xml
     *
     * @return void
     * @since 2.0.2
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "consultarSeriesAutofaturacao",
            "http://at.gov.pt/"
        );

        if ($this->consultSeries->getSeries() !== null) {
            $xml->writeElementNs(
                null,
                "serie",
                null,
                $this->consultSeries->getSeries()
            );
        }

        if ($this->consultSeries->getDocumentTypeCode() !== null) {
            $xml->writeElementNs(
                null,
                "tipoDoc",
                null,
                $this->consultSeries->getDocumentTypeCode()->value
            );
        }

        if ($this->consultSeries->getSeriesValidationCode() !== null) {
            $xml->writeElementNs(
                null,
                "codValidacaoSerie",
                null,
                $this->consultSeries->getSeriesValidationCode()
            );
        }

        if ($this->consultSeries->getFromRegistrationDate() !== null) {
            $xml->writeElementNs(
                null,
                "dataRegistoDe",
                null,
                $this->consultSeries->getFromRegistrationDate()->format(Pattern::SQL_DATE)
            );
        }

        if ($this->consultSeries->getToRegistrationDate() !== null) {
            $xml->writeElementNs(
                null,
                "dataRegistoAte",
                null,
                $this->consultSeries->getToRegistrationDate()->format(Pattern::SQL_DATE)
            );
        }

        if ($this->consultSeries->getSupplierTin() !== null) {
            $xml->writeElementNs(
                null,
                "nifAssociadoAoAcordo",
                null,
                $this->consultSeries->getSupplierTin()
            );
        }

        if ($this->consultSeries->getSelfBillingEntityCode() !== null) {
            $xml->writeElementNs(
                null,
                "acordoRegistadoCom",
                null,
                $this->consultSeries->getSelfBillingEntityCode()->value
            );
        }

        $xml->endElement(); //registarSerie
        $xml->endElement(); //Body
    }


    /**
     * @param \Rebelo\ATWs\Series\ConsultSelfBillingSeries $consultSeries
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 2.0.2
     */
    public function submission(ConsultSelfBillingSeries $consultSeries): SelfBillingResponse
    {
        $this->consultSeries = $consultSeries;
        return SelfBillingResponse::factory(
            parent::doRequest()
        );
    }

    /**
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string
    {
        return "consultarSeriesAutofaturacao";
    }
}
