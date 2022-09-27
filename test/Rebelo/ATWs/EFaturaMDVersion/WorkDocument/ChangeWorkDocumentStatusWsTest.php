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
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Change Work Document Status Ws Test
 * @author João Rebelo
 */
class ChangeWorkDocumentStatusWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(ChangeWorkDocumentStatusWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\ChangeWokDocumentStatus[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function changeWorkStatusDataProvider(): array
    {
        $data                  = [];
        $taxRegistrationNumber = static::$taxRegistrationNumber;
        $documentNumber        = "A A/999";
        $atcud                 = "0";
        $workDate              = new Date();
        $customerTaxID         = "999999999";
        $customerTaxIDCountry  = "KR";

        $workHeader = new WorkHeader(
            $documentNumber,
            $atcud,
            $workDate,
            "FO",
            $customerTaxID,
            $customerTaxIDCountry
        );

        $newWorkStatus = new WorkStatus("A", new Date());

        $data[] = new ChangeWokDocumentStatus(
            $taxRegistrationNumber,
            $workHeader,
            $newWorkStatus,
            new RecordChannel("System TUX", "9.9.9")
        );

        $data[] = new ChangeWokDocumentStatus(
            $taxRegistrationNumber,
            $workHeader,
            $newWorkStatus,
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

        $ws = new ChangeWorkDocumentStatusWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("ChangeWorkStatus", $ws->getWsAction());

        foreach ($this->changeWorkStatusDataProvider() as $changeWokDocumentStatus) {
            $response = $ws->submit($changeWokDocumentStatus);
            $this->assertSame(-19, $response->getCode());
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

        foreach ($this->changeWorkStatusDataProvider() as $changeWorkStatus) {

            $ws = new ChangeWorkDocumentStatusWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("changeWokDocumentStatus");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $changeWorkStatus);

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
                $changeWorkStatus->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getDocumentNumber(),
                (string)(($xml->xpath("//doc:DocumentNumber") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getWorkDate()->format(Date::SQL_DATE),
                (string)(($xml->xpath("//doc:WorkDate") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getWorkType(),
                (string)(($xml->xpath("//doc:WorkType") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getCustomerTaxID(),
                (string)(($xml->xpath("//doc:CustomerTaxID") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getWorkHeader()->getCustomerTaxIDCountry(),
                (string)(($xml->xpath("//doc:CustomerTaxIDCountry") ?: [])[0])
            );

            $this->assertSame(
                $changeWorkStatus->getNewWorkStatus()->getWorkStatus(),
                (string)(($xml->xpath("//doc:WorkStatus") ?: [])[1])
            );

            $this->assertSame(
                $changeWorkStatus->getNewWorkStatus()->getWorkStatusDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:WorkStatusDate") ?: [])[0])
            );

            if ($changeWorkStatus->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $changeWorkStatus->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $changeWorkStatus->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
