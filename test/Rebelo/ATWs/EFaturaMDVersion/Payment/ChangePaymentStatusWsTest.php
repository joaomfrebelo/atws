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
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Payment Ws Test
 * @author João Rebelo
 */
class ChangePaymentStatusWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ChangePaymentStatusWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\ChangePaymentStatus[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function changePaymentStatusDataProvider(): array
    {
        $data = [];

        $header = new PaymentHeader(
            \sprintf("RC %s/%s", \rand(1, 99999), \rand(1, 99999)),
            "0",
            new Date(),
            "RC",
            "999999990",
            "KR"
        );

        $data[] = new ChangePaymentStatus(
            self::$taxRegistrationNumber,
            $header,
            new PaymentStatus("A", new Date()),
            null
        );

        $data[] = new ChangePaymentStatus(
            self::$taxRegistrationNumber,
            $header,
            new PaymentStatus("A", new Date()),
            new RecordChannel("System TUX", "9.9.9")
        );

        return $data;
    }


    /**
     * @test
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testInstance(): void
    {
        foreach ($this->changePaymentStatusDataProvider() as $changePaymentStatus) {

            $ws = new ChangePaymentStatusWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $this->assertSame("ChangePaymentStatus", $ws->getWsAction());

            $response = $ws->submit($changePaymentStatus);
            $this->assertSame(-38, $response->getCode());
            $this->assertNotEmpty($response->getMessage());
        }
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     */
    public function testXml(): void
    {

        foreach ($this->changePaymentStatusDataProvider() as $changePaymentStatus) {

            $ws = new ChangePaymentStatusWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("changePaymentStatus");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $changePaymentStatus);

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
                $changePaymentStatus->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getPaymentRefNo(),
                (string)(($xml->xpath("//doc:PaymentRefNo") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getTransactionDate()->format(Date::SQL_DATE),
                (string)(($xml->xpath("//doc:TransactionDate") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getPaymentType(),
                (string)(($xml->xpath("//doc:PaymentType") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getCustomerTaxID(),
                (string)(($xml->xpath("//doc:CustomerTaxID") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getPaymentHeader()->getCustomerTaxIDCountry(),
                (string)(($xml->xpath("//doc:CustomerTaxIDCountry") ?: [])[0])
            );

            $this->assertSame(
                $changePaymentStatus->getNewPaymentStatus()->getPaymentStatus(),
                (string)(($xml->xpath("//doc:PaymentStatus") ?: [])[1])
            );

            $this->assertSame(
                $changePaymentStatus->getNewPaymentStatus()->getPaymentStatusDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:PaymentStatusDate") ?: [])[0])
            );

            if ($changePaymentStatus->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $changePaymentStatus->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $changePaymentStatus->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
