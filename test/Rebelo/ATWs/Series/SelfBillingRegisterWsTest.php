<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Register Webservice Test
 */
class SelfBillingRegisterWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(SelfBillingRegisterWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return array
     * @throws \Rebelo\Enum\EnumException
     */
    public function codesDataProvider(): array
    {
        $constDocType            = (new \ReflectionClass(SelfBillingDocumentTypeCode::class))->getConstants();
        $selfBillingEntitiesCode = (new \ReflectionClass(SelfBillingEntityCode::class))->getConstants();

        $data = [];

        $n = 0;
        foreach ($selfBillingEntitiesCode as $entityCode) {
            foreach ($constDocType as $docType) {

                if($docType !== "FT" && $entityCode !== "FN"){
                    continue;
                }

                if($docType === "FT" && $entityCode === "CE"){
                    continue;
                }

                $data[] = [
                    new SelfBillingDocumentTypeCode($docType),
                    new SelfBillingEntityCode($entityCode),
                    ($n % 2 === 0) ? "KR" : null,
                    ($n % 2 === 0) ? "Korean company" : null,
                ];
                $n++;
            }
        }
        return $data;
    }

    /**
     *
     * @test
     * @dataProvider codesDataProvider
     *
     * @param \Rebelo\ATWs\Series\SelfBillingDocumentTypeCode $docType
     * @param \Rebelo\ATWs\Series\SelfBillingEntityCode       $entityCode
     * @param string|null                                     $supplierCountry
     * @param string|null                                     $supplierName
     *
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testSubmission(
        SelfBillingDocumentTypeCode $docType,
        SelfBillingEntityCode       $entityCode,
        ?string                     $supplierCountry,
        ?string                     $supplierName
    ): void
    {
        $seriesRegister = new SelfBillingSeriesRegister(
            \strtoupper(\substr(\md5(\microtime()), 0, 10)),
            $docType,
            999,
            (new Date()),
            9999,
            $entityCode,
            "555555550",
            $supplierCountry,
            $supplierName
        );

        $registerWs = new SelfBillingRegisterWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $response = $registerWs->submission($seriesRegister);
        $this->assertTrue(
            \in_array(
                $response->getOperationResultInformation()->getOperationResultCode(),
                [4002, 4003]
            )
        );
        $this->assertNotEmpty($response->getOperationResultInformation()->getOperationResultMessage());
    }
}
