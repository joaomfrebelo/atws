<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\Response;

/**
 * Change Work Document Status Ws
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class ChangeWorkDocumentStatusWs extends AWs implements IChangeWorkDocumentStatusWs
{

    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\ChangeWokDocumentStatus
     * @since  2.0.0
     */
    protected ChangeWokDocumentStatus $changeWokDocumentStatus;

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
     * Build the xml
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since  2.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(
            AATWs::NS_ENVELOPE,
            "Body", null
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "ChangeWorkStatusRequest",
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
            $this->changeWokDocumentStatus->getTaxRegistrationNumber()
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "WorkHeader",
            null
        );

        $this->changeWokDocumentStatus->getWorkHeader()->buildXml($xml);

        $xml->endElement(); //WorkHeader

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "WorkStatus",
            null
        );

        $this->changeWokDocumentStatus->getNewWorkStatus()->buildXml($xml);

        $xml->endElement(); //NewWorkStatusType

        $this->changeWokDocumentStatus->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //ChangeWorkStatusRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit the change document status to teh AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\ChangeWokDocumentStatus $changeWokDocumentStatus
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(ChangeWokDocumentStatus $changeWokDocumentStatus): Response
    {
        $this->changeWokDocumentStatus = $changeWokDocumentStatus;
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
        return "ChangeWorkStatus";
    }

}
