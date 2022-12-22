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
 * Delete Work Document Ws
 *
 * @author João Rebelo
 * @since  2.0.0
 */
class DeleteWorkDocumentWs extends AWs implements IDeleteWorkDocumentWs
{

    /**
     * @var \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\DeleteWorkDocument
     * @since 2.0.0
     */
    protected DeleteWorkDocument $deleteWorkDocument;

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
            "DeleteWorkRequest",
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
            $this->deleteWorkDocument->getTaxRegistrationNumber()
        );

        if (\count($this->deleteWorkDocument->getDocumentList() ?? []) > 0) {

            $xml->startElementNs(
                static::NS_AT_WS_BODY,
                "documentsList",
                null
            );

            foreach ($this->deleteWorkDocument->getDocumentList() as $work) {

                $xml->startElementNs(
                    static::NS_AT_WS_BODY,
                    "work",
                    null
                );

                $work->buildXml($xml);

                $xml->endElement();
            }

            $xml->endElement();
        }

        $this->deleteWorkDocument->getDateRange()?->buildXml($xml);

        $xml->writeElementNs(
            static::NS_AT_WS_BODY,
            "reason",
            null,
            $this->deleteWorkDocument->getReason()
        );

        $this->deleteWorkDocument->getRecordChannel()?->buildXml($xml);

        $xml->endElement(); //DeleteWorkDocumentRequest
        $xml->endElement(); //Body
    }

    /**
     * Submit the change document status to teh AT webservice
     * @param \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\DeleteWorkDocument $deleteWorkDocument
     * @return \Rebelo\ATWs\EFaturaMDVersion\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function submit(DeleteWorkDocument $deleteWorkDocument): Response
    {
        $this->deleteWorkDocument = $deleteWorkDocument;
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
        return "DeleteWork";
    }

}
