<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\EFaturaMDVersion\AWs;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Response;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Change Invoice Status Webservice Test
 *
 * @author João Rebelo
 */
class ChangeInvoiceStatusWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ChangeInvoiceStatusWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\ChangeInvoiceStatus[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function changeInvoiceStatusDataProvider(): array
    {
        $data = [];

        $taxRegistrationNumber = static::$taxRegistrationNumber;
        $header                = new InvoiceHeader(
            "FT 9/9999",
            "0",
            new Date(),
            "FT",
            false,
            "999999990",
            "PT"
        );

        $data[] = new ChangeInvoiceStatus(
            $taxRegistrationNumber,
            $header,
            new InvoiceStatus("F", new Date()),
            new RecordChannel("System Tux", "9.9.0")
        );

        return $data;
    }


    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testInstance(): void
    {
        $ws = new ChangeInvoiceStatusWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("ChangeInvoiceStatus", $ws->getWsAction());

        foreach ($this->changeInvoiceStatusDataProvider() as $changeInvoiceStatus) {
            $response = $ws->submit($changeInvoiceStatus);
            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame(-11, $response->getCode());
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

        foreach ($this->changeInvoiceStatusDataProvider() as $changeInvoiceStatus) {

            $ws = new ChangeInvoiceStatusWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("changeInvoiceStatus");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $changeInvoiceStatus);

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
                $changeInvoiceStatus->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getInvoiceNo(),
                (string)(($xml->xpath("//doc:InvoiceNo") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getInvoiceDate()->format(Date::SQL_DATE),
                (string)(($xml->xpath("//doc:InvoiceDate") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getInvoiceType(),
                (string)(($xml->xpath("//doc:InvoiceType") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getSelfBillingIndicator(),
                ((int)(($xml->xpath("//doc:InvoiceType") ?: [])[0])) === 1
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getCustomerTaxID(),
                (string)(($xml->xpath("//doc:CustomerTaxID") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getInvoiceHeader()->getCustomerTaxIDCountry(),
                (string)(($xml->xpath("//doc:CustomerTaxIDCountry") ?: [])[0])
            );

            $this->assertSame(
                $changeInvoiceStatus->getNewInvoiceStatus()->getInvoiceStatus(),
                (string)(($xml->xpath("//doc:InvoiceStatus") ?: [])[1])
            );

            $this->assertSame(
                $changeInvoiceStatus->getNewInvoiceStatus()->getInvoiceStatusDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:InvoiceStatusDate") ?: [])[0])
            );

            if ($changeInvoiceStatus->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $changeInvoiceStatus->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $changeInvoiceStatus->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
