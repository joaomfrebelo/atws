<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * Self billing series WS client register
 * @since 2.0.2
 */
class SelfBillingRegisterWs extends ASelfBillingSeriesWs implements ISelfBillingRegisterWs
{
    /**
     * @var \Rebelo\ATWs\Series\SelfBillingSeriesRegister
     * @since 2.0.2
     */
    protected SelfBillingSeriesRegister $seriesRegister;

    /**
     * Submit the request to the webservice
     *
     * @param \Rebelo\ATWs\Series\SelfBillingSeriesRegister $seriesRegister
     *
     * @return \Rebelo\ATWs\Series\SelfBillingResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(SelfBillingSeriesRegister $seriesRegister): SelfBillingResponse
    {
        $this->seriesRegister = $seriesRegister;
        return SelfBillingResponse::factory(
            parent::doRequest()
        );
    }

    /**
     * @param \XMLWriter $xml
     *
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "registarSerieAutofaturacao",
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
            "classeDoc",
            null,
            "SI"
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
            "comunicarEmNomeDe",
            null,
            $this->seriesRegister->getSelfBillingEntityCode()->get()
        );

        $xml->writeElementNs(
            null,
            "nifAssociadoAoAcordo",
            null,
            $this->seriesRegister->getSupplierTin()
        );

        $supplierCountry = $this->seriesRegister->getSupplierCountry();

        if ($supplierCountry !== null && $supplierCountry !== "PT") {

            $xml->writeElementNs(
                null,
                "paisEstrangeiro",
                null,
                $this->seriesRegister->getSupplierCountry()
            );

            $xml->writeElementNs(
                null,
                "nomeEstrangeiro",
                null,
                ($this->seriesRegister->getForeignSupplierName() ??
                         throw new ATWsException("Self billing settlement with foreign entities must have name ")
                )
            );
        }

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
        return "registarSerieAutofaturacao";
    }

}
