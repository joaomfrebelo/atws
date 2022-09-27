<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs;

use SimpleXMLElement;

/**
 * Response
 *
 * @author João Rebelo
 * @since  1.0.0
 */
abstract class AResponse
{

    /**
     *
     * Response return code
     *
     * @var int|null
     * @since 1.0.0
     */
    protected ?int $code = null;

    /**
     *
     * Get response error
     *
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $message = null;

    /**
     * The SimpleXMElement instance of the response string
     * @var \SimpleXMLElement
     * @since 1.0.0
     */
    protected SimpleXMLElement $simpleXMLElement;

    /**
     *
     * @since 1.0.0
     */
    protected function __construct()
    {

    }

    /**
     *
     * @return int
     * @since 1.0.0
     */
    public function getCode(): int
    {
        return $this->code ?? 999999999;
    }

    /**
     * Get Response message
     *
     * @return string
     * @since 1.0.0
     */
    public function getMessage(): string
    {
        return \trim(\html_entity_decode($this->message ?? "No message returned from SOAP Server"));
    }

    /**
     *
     * @param string $xml
     *
     * @return self
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected static function factory(string $xml): self
    {
        if (false === $simpleXml = \simplexml_load_string($xml, SimpleXMLElement::class)) {
            throw new ATWsException("Wrong response from soap request");
        }

        $resp                   = new static(); //@phpstan-ignore-line
        $resp->simpleXMLElement = $simpleXml;

        try {

            try {
                if (false !== $body = $simpleXml->xpath("//env:Body")) {

                    if (\count($body) > 0) {

                        $registerInvoiceCode = $body[0]->children()->children()->children();

                        if ($registerInvoiceCode !== null) {
                            $code          = (string)$registerInvoiceCode->{"CodigoResposta"};
                            $message       = (string)$registerInvoiceCode->{"Mensagem"};
                            $resp->code    = \is_numeric($code) ? (int)$code : 9999;
                            $resp->message = \trim($message) === "" ?
                                "Unknown response message" :
                                \html_entity_decode($message);
                            return $resp;
                        }
                    }
                }
            } catch (\Throwable) {

            }

            try {
                if (false !== $simpleXml->xpath("//ResponseStatus")) {

                    $codeXml = $simpleXml->xpath("//ReturnCode");
                    if (\count($codeXml) > 0) {
                        $code          = (string)$codeXml[0];
                        $message       = (string)(($simpleXml->xpath("//ReturnMessage") ?: [])[0]);
                        $resp->code    = \is_numeric($code) ? (int)$code : 9999;
                        $resp->message = \trim($message) === "" ?
                            "Unknown response message" :
                            \html_entity_decode($message);
                        return $resp;
                    }
                }
            } catch (\Throwable) {

            }

            $authenticationException = $simpleXml->xpath("//Code");

            if (\count($authenticationException) > 0) {
                $resp->code    = (int)$authenticationException[0];
                $resp->message = (string)$simpleXml->xpath("//Message")[0];
                return $resp;
            }

            $atInternalError = $simpleXml->children("http://www.w3.org/2003/05/soap-envelope");
            if ($atInternalError->count() > 0) {
                $resp->code    = 999999999;
                $resp->message = (string)$atInternalError->{"Body"}->{"Fault"}->{"Code"}->{"Value"};
                $resp->message .= " - " . $atInternalError->{"Body"}->{"Fault"}->{"Reason"}->{"Text"};
                return $resp;
            }

            $fault = $simpleXml->xpath("//faultcode");
            if (\count($fault) > 0) {
                $resp->code    = (int)$fault[0];
                $resp->message = (string)$simpleXml->xpath("//faultstring")[0];
                return $resp;
            }

        } catch (\Throwable $e) {
            throw new ATWsException("Error parsing XML soap response: " . $e->getMessage());
        }

        throw new ATWsException("XML parser not available for XML response");

    }

    /**
     * get if the response is OK (Not error)
     * @return bool
     * @since 1.0.0
     */
    public function isResponseOk(): bool
    {
        return $this->code === 0;
    }

}
