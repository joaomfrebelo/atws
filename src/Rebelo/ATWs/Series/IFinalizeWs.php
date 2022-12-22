<?php

namespace Rebelo\ATWs\Series;


/**
 * Finalize series webservice
 * @since 2.0.1
 */
interface IFinalizeWs
{
    /**
     *
     * @param string $username            AT (e-fatura) username
     * @param string $password            AT (e-fatura) password
     * @param string $certificatePath     The certificate path
     * @param string $certificatePassword The certificate password
     * @param bool   $isTest              Define if teh SOAP request is to the test soap server
     *
     * @since 1.0.0
     */
    public function __construct(string $username, string $password, string $certificatePath, string $certificatePassword, bool $isTest);

    /**
     * Do the SOAP request
     *
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function doRequest(): string;

    /**
     * Get the Invoice webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @noinspection
     * @since 1.0.0
     */
    public function getWsdl(): string;

    /**
     * Get the Webservice location
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @noinspection
     * @since 1.0.0
     */
    public function getWsLocation(): string;

    /**
     * @param \Rebelo\ATWs\Series\FinalizeSeries $finalizeSeries
     * @return \Rebelo\ATWs\Series\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submission(FinalizeSeries $finalizeSeries): Response;

    /**
     * Get the Webservice action
     * @return string
     * @since 1.0.0
     */
    public function getWsAction(): string;
}
