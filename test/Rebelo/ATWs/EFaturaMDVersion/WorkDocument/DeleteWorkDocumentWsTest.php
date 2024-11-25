<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\WorkDocument;

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
 * Delete Work Document Ws Test
 * @author João Rebelo
 */
class DeleteWorkDocumentWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(DeleteWorkDocumentWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\DeleteWorkDocument[]
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function deleteWorkDataProvider(): array
    {
        $data = [];

        $taxRegistrationNumber = static::$taxRegistrationNumber;
        $documentList          = [
            new WorkHeader(
                "A A/999",
                "0",
                new Date(),
                "FO",
                "999999990",
                "KR"
            ),
        ];

        $dateRange = null;
        $reason    = "Test framework";

        $data[] = new DeleteWorkDocument(
            $taxRegistrationNumber,
            $documentList,
            $dateRange,
            $reason,
            new RecordChannel("System TUX", "9.9.9")
        );

        $documentList[] = new WorkHeader(
            "A B/999",
            "0",
            new Date(),
            "CM",
            "999999990",
            "Desconhecido"
        );

        $data[] = new DeleteWorkDocument(
            $taxRegistrationNumber,
            $documentList,
            $dateRange,
            $reason,
            new RecordChannel("System TUX", "9.9.9")
        );

        $dateRange = new DateRange(new Date(), new Date());
        $data[]    = new DeleteWorkDocument(
            $taxRegistrationNumber,
            null,
            $dateRange,
            $reason,
            new RecordChannel("System TUX", "9.9.9")
        );

        $data[] = new DeleteWorkDocument(
            $taxRegistrationNumber,
            [],
            $dateRange,
            $reason,
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
    public function testInstanceDocumentList(): void
    {

        $ws = new DeleteWorkDocumentWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("DeleteWork", $ws->getWsAction());

        foreach ($this->deleteWorkDataProvider() as $delete) {
            $response = $ws->submit($delete);
            $this->assertSame(0, $response->getCode());
            $this->assertTrue($response->isResponseOk());
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

        foreach ($this->deleteWorkDataProvider() as $deleteWork) {

            $ws = new DeleteWorkDocumentWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("deleteWorkDocument");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $deleteWork);

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
                $deleteWork->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );


            $this->assertSameSize(
                $deleteWork->getDocumentList() ?: [],
                $xml->xpath("//doc:work") ?: []
            );

            foreach ($deleteWork->getDocumentList() ?: [] as $k => $header) {

                $this->assertSame(
                    $header->getDocumentNumber(),
                    (string)(($xml->xpath("//doc:DocumentNumber") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getAtcud(),
                    (string)(($xml->xpath("//doc:ATCUD") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getWorkDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:WorkDate") ?: [])[$k])
                );

                $this->assertSame(
                    $header->getWorkType(),
                    (string)(($xml->xpath("//doc:WorkType") ?: [])[$k])
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

            if ($deleteWork->getDateRange() === null) {
                $this->assertEmpty($xml->xpath("//doc:dateRange"));
            } else {

                $this->assertSame(
                    $deleteWork->getDateRange()->getStartDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:StartDate") ?: [])[0])
                );

                $this->assertSame(
                    $deleteWork->getDateRange()->getStartDate()->format(Pattern::SQL_DATE),
                    (string)(($xml->xpath("//doc:EndDate") ?: [])[0])
                );
            }

            if ($deleteWork->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $deleteWork->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $deleteWork->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
