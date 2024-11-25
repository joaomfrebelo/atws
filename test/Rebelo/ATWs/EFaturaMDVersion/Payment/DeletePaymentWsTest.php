<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Delete payment test
 * @since 2.0.0
 */
class DeletePaymentWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeletePaymentWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\DeletePayment[]
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function deletePaymentDataProvider(): array
    {
        $data = [];

        $data[] = new DeletePayment(
            self::$taxRegistrationNumber,
            [
                new PaymentHeader("RC A/999", "0", new Date(), "RC", "999999990", "KR"),
            ],
            null,
            "Test framework",
            null
        );

        $data[] = new DeletePayment(
            self::$taxRegistrationNumber,
            [
                new PaymentHeader("RC A/999", "0", new Date(), "RC", "999999990", "KR"),
                new PaymentHeader("RC B/999", "0", new Date(), "RC", "999999990", "KR"),
            ],
            null,
            "Test framework",
            new RecordChannel("System TUX", "9.9.9")
        );

        $data[] = new DeletePayment(
            self::$taxRegistrationNumber,
            [],
            new DateRange(new Date(), new Date()),
            "Test framework",
            new RecordChannel("System TUX", "9.9.9")
        );

        $data[] = new DeletePayment(
            self::$taxRegistrationNumber,
            null,
            new DateRange(new Date(), new Date()),
            "Test framework",
            new RecordChannel("System TUX", "9.9.9")
        );

        return $data;
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstance(): void
    {
        $ws = new DeletePaymentWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("DeletePayment", $ws->getWsAction());

        foreach ($this->deletePaymentDataProvider() as $deletePayment) {
            $response = $ws->submit($deletePayment);
            $this->assertSame(0, $response->getCode());
            $this->assertNotEmpty($response->getMessage());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \ReflectionException
     */
    public function testXml(): void
    {

        foreach ($this->deletePaymentDataProvider() as $deletePayment) {

            $ws = new DeletePaymentWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("deletePayment");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $deletePayment);

            $refMeth = $ref->getMethod("buildBody");
            $refMeth->setAccessible(true);

            $xmlWriter = new \XMLWriter();
            $xmlWriter->openMemory();
            $xmlWriter->startDocument();
            $xmlWriter->startElementNs(
                AATWs::NS_ENVELOPE,
                "Envelope",
                "http://schemas.xmlsoap.org/soap/envelope/"
            );

            $refMeth->invokeArgs($ws, [$xmlWriter]);
            $xmlWriter->endElement();
            $xmlWriter->endDocument();

            /** @var string $xmlStr */
            $xmlStr = $xmlWriter->flush();

            if (false === $xml = \simplexml_load_string($xmlStr)) {
                $this->fail("XML not parsed");
            }

            $xml->registerXPathNamespace(
                "doc",
                "http://factemi.at.min_financas.pt/documents"
            );

            $this->assertSame(
                AWs::E_FATURA_MD_VERSION,
                (string)(($xml->xpath("//doc:eFaturaMDVersion") ?: [])[0])
            );

            $this->assertSame(
                $deletePayment->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );


            $this->assertSameSize(
                $deletePayment->getDocumentList() ?: [],
                $xml->xpath("//doc:payment") ?: []
            );

            foreach ($deletePayment->getDocumentList() ?: [] as $k => $header) {

                $this->assertSame(
                    $header->getPaymentRefNo(),
                    (string)(($xml->xpath("//doc:PaymentRefNo") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getAtcud(),
                    (string)(($xml->xpath("//doc:ATCUD") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getTransactionDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:TransactionDate") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getPaymentType(),
                    (string)(($xml->xpath("//doc:PaymentType") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getCustomerTaxID(),
                    (string)(($xml->xpath("//doc:CustomerTaxID") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getCustomerTaxIDCountry(),
                    (string)(($xml->xpath("//doc:CustomerTaxIDCountry") ?: [])[$k])
                );

            }

            if ($deletePayment->getDateRange() === null) {
                $this->assertEmpty($xml->xpath("//doc:dateRange"));
            } else {

                $this->assertSame(
                    $deletePayment->getDateRange()->getStartDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:StartDate") ?: [])[0])
                );

                $this->assertSame(
                    $deletePayment->getDateRange()->getStartDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:EndDate") ?: [])[0])
                );
            }

            if ($deletePayment->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $deletePayment->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $deletePayment->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
