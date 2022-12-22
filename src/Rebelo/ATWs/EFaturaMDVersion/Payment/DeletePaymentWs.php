<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Delete payment
 * @since  2.0.0
 * @author João Rebelo
 */
class DeletePaymentWs extends AWs implements IDeletePaymentWs
{

    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\Payment\DeletePayment
     * @since 2.0.0
     */
    protected DeletePayment $deletePayment;

    /**
     *
     * @param string $username            AT (e-fatura) username
     * @param string $password            AT (e-fatura) password
     * @param string $certificatePath     The certificate path
     * @param string $certificatePassword The certificate password
     * @param bool   $isTest              Define if teh SOAP request is to the test soap server
     * @since 2.0.0
     */
    public function __construct(string $username, string $password, string $certificatePath, string $certificatePassword, bool $isTest)
    {
        parent::__construct(
            $username, $password, $certificatePath, $certificatePassword, $isTest
        );
    }

    /**
     * Build xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 2.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AATWs::NS_ENVELOPE,
            "Body", null
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "DeletePaymentRequest",
            "http://factemi.at.min_financas.pt/documents"
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "eFaturaMDVersion",
            null,
            static::E_FATURA_MD_VERSION
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxRegistrationNumber",
            null,
            $this->deletePayment->getTaxRegistrationNumber()
        );

        if (\count($this->deletePayment->getDocumentList() ?? []) > 0) {

            $xml->startElementNs(
                static::NS_AT_WS_BODY,
                "documentsList",
                null
            );

            foreach ($this->deletePayment->getDocumentList() as $payment) {

                $xml->startElementNs(
                    static::NS_AT_WS_BODY,
                    "payment",
                    null
                );

                $payment->buildXml($xml);

                $xml->endElement();
            }

            $xml->endElement();
        }

        $this->deletePayment->getDateRange()?->buildXml($xml);

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "reason",
            null,
            $this->deletePayment->getReason()
        );

        $this->deletePayment->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //DeletePaymentRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit the change document status to teh AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\Payment\DeletePayment $deletePayment
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(DeletePayment $deletePayment): Response
    {
        $this->deletePayment = $deletePayment;
        return Response::factory(
            parent::doRequest()
        );
    }

    /**
     * Get the Webservice action
     * @return string
     * @since 2.0.0
     */
    public function getWsAction(): string
    {
        return "DeletePayment";
    }

}
