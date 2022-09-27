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
use Rebelo\ATWs\EFaturaMDVersion\DateRange;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Response;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Delete Invoice Ws Test
 *
 * @author João Rebelo
 */
class DeleteInvoiceWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeleteInvoiceWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\DeleteInvoice[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function deleteInvoiceDataProvider(): array
    {
        $data = [];

        $taxRegistrationNumber = static::$taxRegistrationNumber;

        $data[] = new DeleteInvoice(
            $taxRegistrationNumber,
            null,
            new DateRange(new Date(), new Date()),
            "Test framework",
            new RecordChannel("System TUX", "9.9.9")
        );

        $documentList = [];

        $documentList[] = new InvoiceHeader(
            \sprintf("FT %s/%s", \rand(1, 9999), \rand(1, 9999)),
            "0",
            new Date(),
            "FT",
            false,
            "999999990",
            "KR"
        );

        $data[] = new DeleteInvoice(
            $taxRegistrationNumber,
            $documentList,
            null,
            "Test framework",
            new RecordChannel("System TUX", "9.9.9")
        );

        $documentList[] = new InvoiceHeader(
            \sprintf("FT %s/%s", \rand(1, 9999), \rand(1, 9999)),
            "0",
            new Date(),
            "FT",
            false,
            "999999990",
            "Desconhecido"
        );

        $data[] = new DeleteInvoice(
            $taxRegistrationNumber,
            $documentList,
            null,
            "Test framework",
            null
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

        $ws = new DeleteInvoiceWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("DeleteInvoice", $ws->getWsAction());

        foreach ($this->deleteInvoiceDataProvider() as $deleteInvoice) {
            $response = $ws->submit($deleteInvoice);
            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame(0, $response->getCode());
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

        foreach ($this->deleteInvoiceDataProvider() as $deleteInvoice) {

            $ws = new DeleteInvoiceWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("deleteInvoice");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $deleteInvoice);

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
                $deleteInvoice->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );


            $this->assertSameSize(
                $deleteInvoice->getDocumentList() ?: [],
                $xml->xpath("//doc:invoice") ?: []
            );

            foreach ($deleteInvoice->getDocumentList() ?: [] as $k => $header) {

                $this->assertSame(
                    $header->getInvoiceNo(),
                    (string)(($xml->xpath("//doc:InvoiceNo") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getAtcud(),
                    (string)(($xml->xpath("//doc:ATCUD") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getInvoiceDate()->format(Date::SQL_DATE),
                    (string)(($xml->xpath("//doc:InvoiceDate") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getInvoiceType(),
                    (string)(($xml->xpath("//doc:InvoiceType") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getSelfBillingIndicator() ? 1 : 0,
                    (int)(($xml->xpath("//doc:SelfBillingIndicator") ?: [])[$k])
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

            if ($deleteInvoice->getDateRange() === null) {
                $this->assertEmpty($xml->xpath("//doc:dateRange"));
            } else {

                $this->assertSame(
                    $deleteInvoice->getDateRange()->getStartDate()->format(Date::SQL_DATE),
                    (string)(($xml->xpath("//doc:StartDate") ?: [])[0])
                );

                $this->assertSame(
                    $deleteInvoice->getDateRange()->getStartDate()->format(Date::SQL_DATE),
                    (string)(($xml->xpath("//doc:EndDate") ?: [])[0])
                );
            }

            if ($deleteInvoice->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $deleteInvoice->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $deleteInvoice->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
