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
 * Consult Self billing Agreement Webservice client
 * @since 2.0.2
 */
class ConsultSelfBillingAgreementWs extends ASelfBillingSeriesWs implements IConsultSelfBillingAgreementWs
{

    /**
     * @var \Rebelo\ATWs\Series\ConsultSelfBillingAgreement
     * @since 2.0.2
     */
    protected ConsultSelfBillingAgreement $consultSelfBillingAgreement;

    /**
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.2
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);
        $xml->startElementNs(
            static::NS_REGISTARSERIE,
            "consultarAcordosAutofaturacao",
            "http://at.gov.pt/"
        );

        if ($this->consultSelfBillingAgreement->getTinAssociatedWithTheAgreement() !== null) {
            $xml->writeElementNs(
                null,
                "nifAssociadoAoAcordo",
                null,
                $this->consultSelfBillingAgreement->getTinAssociatedWithTheAgreement()
            );
        }

        if ($this->consultSelfBillingAgreement->getSettlementStatus() !== null) {
            $xml->writeElementNs(
                null,
                "estado",
                null,
                $this->consultSelfBillingAgreement->getSettlementStatus()->get()
            );
        }

        if ($this->consultSelfBillingAgreement->getAuthorizationPeriodFrom() !== null) {
            $xml->writeElementNs(
                null,
                "periodoDeAutorizacaoDe",
                null,
                $this->consultSelfBillingAgreement->getAuthorizationPeriodFrom()->format(
                    Date::SQL_DATE
                )
            );
        }

        if ($this->consultSelfBillingAgreement->getAuthorizationPeriodUntil() !== null) {
            $xml->writeElementNs(
                null,
                "periodoDeAutorizacaoAte",
                null,
                $this->consultSelfBillingAgreement->getAuthorizationPeriodUntil()->format(
                    Date::SQL_DATE
                )
            );
        }

        $xml->endElement(); //consultarAcordosAutofaturacao
        $xml->endElement(); //Body
    }

    /**
     * Submit the request to the webservice
     *
     * @param \Rebelo\ATWs\Series\ConsultSelfBillingAgreement $consultSelfBillingAgreement
     *
     * @return \Rebelo\ATWs\Series\ConsultSelfBillingAgreementResponse
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.2
     */
    public function submission(ConsultSelfBillingAgreement $consultSelfBillingAgreement): ConsultSelfBillingAgreementResponse
    {
        $this->consultSelfBillingAgreement = $consultSelfBillingAgreement;
        return ConsultSelfBillingAgreementResponse::factory(
            parent::doRequest()
        );
    }

    /**
     * @return string
     * @since 2.0.2
     */
    public function getWsAction(): string
    {
        return "consultarAcordosAutofaturacao";
    }

}
