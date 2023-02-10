<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs;

use PHPUnit\Framework\TestCase;

/**
 * Class AATWsTest
 *
 * @author João Rebelo
 */
class AATWsTest extends TestCase
{

    /**
     *
     * @return array
     */
    public function certPathProvider(): array
    {
        return [
            [ATWS_TEST_CERTIFICATE],
            //[\str_replace("pem", "pfx", ATWS_TEST_CERTIFICATE)]
        ];
    }

    /**
     * @dataProvider certPathProvider
     * @test
     * @param string $path
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testValidateCertificates(string $path): void
    {

        $atWs = new class extends AATWs {

            public function __construct()
            {
                parent::__construct("", "", "", "", true);
            }

            /**
             * @param string $path
             * @return void
             */
            public function setCertificatePath(string $path): void
            {
                $this->certificatePath = $path;
            }

            /**
             * @return string
             */
            public function getCertificatePath(): string
            {
                return $this->certificatePath;
            }

            /**
             * @param string $password
             * @return void
             */
            public function setCertificatePassword(string $password): void
            {
                $this->certificatePassword = $password;
            }

            /**
             * @return string
             */
            public function getCertificatePassword(): string
            {
                return $this->certificatePassword;
            }

            /**
             * @param \XMLWriter $xml
             * @return void
             */
            protected function buildBody(\XMLWriter $xml): void
            {

            }

            /**
             * @return string
             */
            public function getWsAction(): string
            {
                return "";
            }

            /**
             * @return string
             */
            public function getWsLocation(): string
            {
                return "";
            }

            /**
             * @return string
             */
            public function getWsdl(): string
            {
                return "";
            }

            /**
             * @return void
             */
            public function validateCertificates(): void
            {
                parent::validateCertificates();
            }
        };

        $atWs->setCertificatePath($path);
        $atWs->setCertificatePassword(ATWS_TEST_CERTIFICATE_PASSPHRASE);
        $atWs->validateCertificates();
        $this->assertTrue(true);
    }

}
