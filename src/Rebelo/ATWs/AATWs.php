<?php /** @noinspection PhpRedundantOptionalArgumentInspection */

/**
 * MIT License
 *
 * @license https://github.com/joaomfrebelo/at-ws/blob/master/LICENSE
 * Copyright (c) 2021 Joao M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs;

use Monolog\Logger;
use Rebelo\Date\Date;
use Rebelo\Date\DateNtpException;
use Rebelo\Date\Pattern;

/**
 * The  class
 *
 * @author Joao M F Rebelo
 * @since  1.0.0
 */
abstract class AATWs
{
    /**
     * @var \Monolog\Logger|null
     * @since 4.0.0
     */
    public static Logger|null  $logger = null;

    /**
     * The encryption cipher
     * @since 1.0.0
     */
    const string CIPHER = "aes-128-ecb";

    /**
     * Symmetric key length in bytes
     *
     * @since 1.0.1
     */
    const int SYMMETRIC_KEY_LENGTH = 16;

    /**
     * The decimals to be use in float format
     *
     * @since 1.0.0
     */
    const int DECIMALS = 7;

    /**
     * The xml header block name space
     *
     * @since 1.0.0
     */
    const string NS_ENVELOPE = "S";

    /**
     * The xml security block name space
     *
     * @since 1.0.0
     */
    const string NS_SECURITY = "wss";

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
    public function __construct(
        protected string $username,
        protected string $password,
        protected string $certificatePath,
        protected string $certificatePassword,
        protected bool   $isTest
    )
    {
        AATWs::$logger?->debug(__METHOD__);
        AATWs::$logger?->debug("Username set to:" . $username);
        AATWs::$logger?->debug("Password set to:" . \str_pad("", \strlen($password), "*"));
        AATWs::$logger?->debug("Certificate path set to:" . $certificatePath);
        AATWs::$logger?->debug(
            "Certificate password set to: " . \str_pad(
                "", \strlen($certificatePassword), "*"
            )
        );
    }

    /**
     *
     * Symmetric key to encrypt the password
     *
     * @var string
     * @since 1.0.0
     */
    protected string $symmetricKey;

    /**
     *
     * Encrypt the string with the AT public key
     *
     * @param string $data
     *
     * @return string the encrypted string encoded in base64
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected function base64EncodeRsaPublicEncrypt(string $data): string
    {
        if (false === $fileContents = \file_get_contents(ATWS_AT_PUB_KEY)) {
            throw new ATWsException("Failure reading contents of public key for encryption");
        }

        if (false === $pubKey = \openssl_pkey_get_public($fileContents)) {
            throw new ATWsException("Failure getting public key for encryption: " . \openssl_error_string());
        }

        $cryptoText = "";
        if (false === \openssl_public_encrypt($data, $cryptoText, $pubKey, OPENSSL_PKCS1_PADDING)) {
            throw new ATWsException("Data encryption failure: " . \openssl_error_string());
        }
        return \base64_encode($cryptoText);
    }

    /**
     *
     * Encrypt with the symmetric key
     *
     * @param string $data
     * @return string Encrypted data in base64 encoded
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected function base64EncodeEncryptAesEcbPkcs5pad(string $data): string
    {
        $keyLength = strlen($this->symmetricKey);

        if (self::CIPHER === 'aes-128-ecb' && $keyLength !== 16) {
            throw new ATWsException("AES-128-ECB requires a 16-byte key");
        }elseif (self::CIPHER === 'aes-256-ecb' && $keyLength !== 32) {
            throw new ATWsException("AES-256-ECB requires a 32-byte key");
        }

        if (false === $encrypt = \openssl_encrypt($data, self::CIPHER, $this->symmetricKey)) {
            throw new ATWsException("Failing to encrypt data");
        }
        return $encrypt;
    }

    /**
     * Symmetric key generator
     *
     * @return void
     * @since 1.0.0
     */
    private function genSimKey(): void
    {
        $this->symmetricKey = \substr(\md5(\uniqid(\microtime())), 0, self::SYMMETRIC_KEY_LENGTH);
    }

    /**
     * Get to encrypt date created
     *
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Exception
     * @since 1.0.0
     */
    protected function getDateCreated(): string
    {
        $gmtFormat = 'Y-m-d\TH:i:s\.00\Z';
        try {
            $gmt = Date::ntp(null, new \DateTimeZone("UTC"))->format($gmtFormat);
        } catch (DateNtpException $e) {
            AATWs::$logger?->error($e->getMessage());
            $gmt = \gmdate($gmtFormat);
        }
        AATWs::$logger?->debug("Date create (UTC): " . $gmt);
        return $this->base64EncodeEncryptAesEcbPkcs5pad($gmt);
    }

    /**
     * Build the xml header
     *
     * @param \XMLWriter $xml
     *
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    protected function buildHeader(\XMLWriter $xml): void
    {
        $this->genSimKey();
        $xml->startElementNs(self::NS_ENVELOPE, "Header", null);
        $xml->startElementNs(self::NS_SECURITY, "Security", "http://schemas.xmlsoap.org/ws/2002/12/secext");
        $xml->startElementNs(self::NS_SECURITY, "UsernameToken", null);
        $xml->writeElementNs(self::NS_SECURITY, "Username", null, $this->username);
        $xml->writeElementNs(
            self::NS_SECURITY,
            "Password",
            null,
            $this->base64EncodeEncryptAesEcbPkcs5pad($this->password)
        );
        $xml->writeElementNs(
            self::NS_SECURITY,
            "Nonce",
            null,
            $this->base64EncodeRsaPublicEncrypt($this->symmetricKey)
        );
        $xml->writeElementNs(
            self::NS_SECURITY,
            "Created",
            null,
            $this->getDateCreated()
        );

        $xml->endElement(); //UsernameToken
        $xml->endElement(); //Security
        $xml->endElement(); //Header
    }

    /**
     * Do the SOAP request
     *
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    public function doRequest(): string
    {
        try {
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->startDocument("1.0", "utf-8", "no");
            $xml->startElementNs(
                self::NS_ENVELOPE,
                "Envelope",
                "http://schemas.xmlsoap.org/soap/envelope/"
            );
            $this->buildHeader($xml);
            $this->buildBody($xml);
            $xml->endElement();// EndEnvelop
            $xml->endDocument();

            $context = stream_context_create(
                [
                'ssl' => [
                    'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT
                ]
                ]
            );

            $soap = new \SoapClient(
                $this->getWsdl(),
                [
                    'local_cert' => $this->certificatePath,
                    'passphrase' => $this->certificatePassword,
                    'trace' => true,
                    "exceptions" => true,
                    "style" => SOAP_RPC,
                    "use" => SOAP_ENCODED,
                    "soap_version" => SOAP_1_2,
                    "wsdl_cache" => $this->isTest ? WSDL_CACHE_NONE : WSDL_CACHE_MEMORY,
                    'context' => $context,
                ]
            );

            $soapXml = $xml->outputMemory();
            AATWs::$logger?->debug($soapXml);

            $response = $soap->__doRequest(
                $soapXml,
                $this->getWsLocation(),
                $this->getWsAction(),
                SOAP_1_2,
                false
            );

            if ($response === null) {
                throw new ATWsException("atws_con_error " . \openssl_error_string());
            }

            AATWs::$logger?->debug("Response: " . $response);

            return $response;
        } catch (\Throwable $e) {
            try {
                $this->validateCertificates();
            } catch (ATWsException $ex) {
                AATWs::$logger?->error($ex->getMessage());
            }
            AATWs::$logger?->error($e->getMessage());
            throw new ATWsException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Validate the AT public key and the company certificate
     *
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     * @since 1.0.0
     */
    protected function validateCertificates(): void
    {
        if (\file_exists(ATWS_AT_PUB_KEY) === false) {
            AATWs::$logger?->error("File not exist: " . ATWS_AT_PUB_KEY);
            throw new ATWsException("AT public certificate file not exists");
        }

        $now = new Date();
        $stamp = Pattern::UNIX_TIMESTAMP;
        $dateFormat = Pattern::DATE_TIME;
        $certError = [];

        if ($pubCertParse = \openssl_x509_parse("file://" . ATWS_AT_PUB_KEY)) {
            $pubFromDate = Date::parse($stamp, (string)$pubCertParse["validFrom_time_t"]);
            $pubToDate = Date::parse($stamp, (string)$pubCertParse["validTo_time_t"]);

            AATWs::$logger?->debug("AT public cert name: " . $pubCertParse["name"]);
            AATWs::$logger?->debug(
                "AT public cert valid from: " . $pubFromDate->format($dateFormat)
            );
            AATWs::$logger?->debug(
                "AT public cert valid to: " . $pubToDate->format($dateFormat)
            );

            if ($now->isEarlier($pubFromDate) || $now->isLater($pubToDate)) {
                $certError[] = "AT public key out of date";
            }
        } else {
            AATWs::$logger?->error("Error debugging AT public key");
        }


        $certificates = [];
        \openssl_pkcs12_read(
            \file_get_contents($this->certificatePath) ?: "",
            $certificates,
            $this->certificatePassword
        );

        if (\count($certificates) === 0) {
            if (false === $certContents = \file_get_contents($this->certificatePath)) {
                throw new ATWsException("Error reading certificate");
            }
            $cert = \openssl_x509_read($certContents);
        } else {
            $cert = $certificates["cert"];
        }

        if ($cert = \openssl_x509_parse($cert)) {

            $certFromDate = Date::parse($stamp, (string)$cert["validFrom_time_t"]);
            $certTodate = Date::parse($stamp, (string)$cert["validTo_time_t"]);

            AATWs::$logger?->debug("Company cert name: " . $cert["name"]);
            AATWs::$logger?->debug(
                "Company cert valid from: " . $certFromDate->format($dateFormat)
            );
            AATWs::$logger?->debug(
                "Company cert valid to: " . $certTodate->format($dateFormat)
            );

            if ($now->isEarlier($certFromDate) || $now->isLater($certTodate)) {
                $certError[] = "Company certificate out of date";
            }
        } else {
            AATWs::$logger?->error("Error debugging company certificate");
        }
        if (\count($certError) > 0) {
            foreach ($certError as $error) {
                AATWs::$logger?->error($error);
            }
            throw new ATWsException("Certificates out of date");
        }
    }

    /**
     * Build the envelope xml body
     *
     * @since 1.0.0
     */
    protected abstract function buildBody(\XMLWriter $xml): void;

    /**
     * Get the wsdl URI
     *
     * @since 1.0.0
     */
    public abstract function getWsdl(): string;

    /**
     * Get the Webservice location
     *
     * @since 1.0.0
     */
    public abstract function getWsLocation(): string;

    /**
     * Get the Webservice action
     *
     * @since 1.0.0
     */
    public abstract function getWsAction(): string;
}
