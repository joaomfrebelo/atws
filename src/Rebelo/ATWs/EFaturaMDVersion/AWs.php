<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\EFaturaMDVersion;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;

/**
 * @since 2.0.0
 */
abstract class AWs extends AATWs
{
    /**
     * The WSDL file path
     * @since 2.0.0
     */
    public const string WSDL = __DIR__ . DIRECTORY_SEPARATOR . "eFaturaMDVersion.wsdl";

    /**
     * Namespace of
     * RegisterInvoiceElem xmlns:doc="http://factemi.at.min_financas.pt/documents"
     *
     * @since 2.0.0
     */
    const string NS_AT_WS_BODY = "doc";

    /**
     * The web services version
     * @since 2.0.0
     */
    const string E_FATURA_MD_VERSION = "0.0.1";

    /**
     *
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     *
     * @param string $username            AT (e-fatura) username
     * @param string $password            AT (e-fatura) password
     * @param string $certificatePath     The certificate path
     * @param string $certificatePassword The certificate password
     * @param bool   $isTest              Define if teh SOAP request is to the test soap server     *
     * @since 2.0.0
     */
    public function __construct(string $username, string $password, string $certificatePath, string $certificatePassword, bool $isTest)
    {
        parent::__construct(
            $username, $password, $certificatePath, $certificatePassword, $isTest
        );
    }

    /**
     * Get the Webservice location
     * @return string
     * @since 2.0.0
     */
    public function getWsLocation(): string
    {
        return $this->isTest ?
            "https://servicos.portaldasfinancas.gov.pt:723/fatcorews/ws/" :
            "https://servicos.portaldasfinancas.gov.pt:423/fatcorews/ws/";
    }

    /**
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function getWsdl(): string
    {
        if (\file_exists(static::WSDL) === false) {
            throw new ATWsException("WSDL file not exist: " . static::WSDL);
        }
        return "file://" . static::WSDL;
    }

    /**
     * Get the Audit File Version
     * @return string
     * @since 2.0.0
     */
    public function getAuditFileVersion(): string
    {
        return "1.04_01";
    }

}
