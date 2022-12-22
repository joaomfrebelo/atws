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
use Rebelo\Date\Date;

/**
 * Work Document Webservice
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class WorkDocumentWs extends AWs implements IWorkDocumentWs
{

    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkDocument
     * @since 2.0.0
     */
    protected WorkDocument $workDocument;

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
     * Build xml soap body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since  2.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "RegisterWorkRequest",
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
            "AuditFileVersion",
            null,
            $this->getAuditFileVersion()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxRegistrationNumber",
            null,
            $this->workDocument->getTaxRegistrationNumber()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "TaxEntity",
            null,
            $this->workDocument->getTaxEntity()
        );

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SoftwareCertificateNumber",
            null,
            (string)$this->workDocument->getSoftwareCertificateNumber()
        );

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "WorkData",
            null
        );

        $data = $this->workDocument->getWorkData();
        $data->getWorkHeader()->buildXml($xml);

        $xml->startElementNs(
            static::NS_AT_WS_BODY,
            "DocumentStatus",
            null
        );

        $data->getDocumentStatus()->buildXml($xml);

        $xml->endElement();

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "HashCharacters",
            null,
            $data->getHashCharacters()
        );

        if ($data->getEacCode() !== null) {
            $xml->writeElementNs(
                static::NS_AT_WS_BODY,
                "EACCode",
                null,
                $data->getEacCode()
            );
        }

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "SystemEntryDate",
            null,
            $data->getSystemEntryDate()->format(Date::DATE_T_TIME)
        );

        foreach ($data->getLines() as $line) {
            $line->buildXml($xml);
        }

        $data->getDocumentTotals()->buildXml($xml);

        $xml->endElement(); //WorkData

        $this->workDocument->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //RegisterWork
        $xml->endElement(); //Body
    }

    /**
     * Submit the work document to the AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkDocument $workDocument
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(WorkDocument $workDocument): Response
    {
        $this->workDocument = $workDocument;
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
        return "RegisterWork";
    }
}
