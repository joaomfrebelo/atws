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
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Work Document Webservice test
 *
 * @author João Rebelo
 */
class WorkDocumentWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(WorkDocumentWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\WorkDocument\WorkDocument[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function workDataProvider(): array
    {

        $data = [];

        foreach (['CM', 'CC', 'FC', 'FO', 'NE', 'OU', 'OR', 'PF', 'RP', 'RE', 'CS', 'LD', 'RA'] as $k => $workType) {

            $taxRegistrationNumber     = static::$taxRegistrationNumber;
            $taxEntity                 = "Global";
            $softwareCertificateNumber = 9999;

            $workHeader = new WorkHeader(
                \sprintf("%s %s/%s", $workType, \rand(1, 99999999), \rand(1, 99999999)),
                "0",
                (new Date())->addDays(-1),
                $workType,
                "999999990",
                $k % 2 === 0 ? "KR" : "Desconhecido"
            );

            $documentStatus  = new WorkStatus("N", new Date());
            $hashCharacters  = "ABCD";
            $systemEntryDate = new Date();

            $lines   = [];
            $lines[] = new Line(
                null, new Date(),
                null, "C",
                999.99,
                null,
                new Tax(
                    "IVA", "PT", "NOR", 23.00, null
                ),
                null
            );

            if ($k % 2 === 0) {
                $lines[] = new Line(
                    null,
                    new Date(),
                    null,
                    "D",
                    null,
                    9.99,
                    new Tax(
                        "IVA", "PT", "ISE", null, 0.0
                    ),
                    "M99"
                );
            }

            $documentTotals = new DocumentTotals(230.00, 999.99, 1229.99);

            $workData = new WorkData(
                $workHeader,
                $documentStatus,
                $hashCharacters,
                null,
                $systemEntryDate,
                $lines,
                $documentTotals
            );

            $data[] = new WorkDocument(
                $taxRegistrationNumber,
                $taxEntity,
                $softwareCertificateNumber,
                $workData,
                $k % 2 === 0 ? null : new RecordChannel("System TUX", "9.9.9")
            );
        }

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
        $ws = new WorkDocumentWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $this->assertSame("RegisterWork", $ws->getWsAction());

        foreach ($this->workDataProvider() as $workDocument) {
            $response = $ws->submit($workDocument);
            $this->assertSame(0, $response->getCode());
            $this->assertTrue($response->isResponseOk());
            $this->assertNotEmpty($response->getMessage());
        }

    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testXml(): void
    {
        foreach ($this->workDataProvider() as $work) {

            $ws = new WorkDocumentWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("workDocument");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $work);

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
                $ws->getAuditFileVersion(),
                (string)(($xml->xpath("//doc:AuditFileVersion") ?: [])[0])
            );

            $this->assertSame(
                $work->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $work->getTaxEntity(),
                (string)(($xml->xpath("//doc:TaxEntity") ?: [])[0])
            );

            $this->assertSame(
                $work->getSoftwareCertificateNumber(),
                (int)(($xml->xpath("//doc:SoftwareCertificateNumber") ?: [])[0])
            );

            $data   = $work->getWorkData();
            $header = $data->getWorkHeader();

            $this->assertSame(
                $header->getDocumentNumber(),
                (string)(($xml->xpath("//doc:DocumentNumber") ?: [])[0])
            );

            $this->assertSame(
                $header->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $header->getWorkDate()->format(Date::SQL_DATE),
                (string)(($xml->xpath("//doc:WorkDate") ?: [])[0])
            );

            $this->assertSame(
                $header->getWorkType(),
                (string)(($xml->xpath("//doc:WorkType") ?: [])[0])
            );

            $this->assertSame(
                $header->getCustomerTaxID(),
                (string)(($xml->xpath("//doc:CustomerTaxID") ?: [])[0])
            );

            $this->assertSame(
                $header->getCustomerTaxIDCountry(),
                (string)(($xml->xpath("//doc:CustomerTaxIDCountry") ?: [])[0])
            );

            $this->assertSame(
                $data->getDocumentStatus()->getWorkStatus(),
                (string)(($xml->xpath("//doc:WorkStatus") ?: [])[0])
            );

            $this->assertSame(
                $data->getDocumentStatus()->getWorkStatusDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:WorkStatusDate") ?: [])[0])
            );

            $this->assertSame(
                $data->getHashCharacters(),
                (string)(($xml->xpath("//doc:HashCharacters") ?: [])[0])
            );

            if ($data->getEacCode() === null) {
                $this->assertEmpty($xml->xpath("//doc:EACCode"));
            } else {
                $this->assertSame(
                    $data->getEacCode(),
                    (string)(($xml->xpath("//doc:EACCode") ?: [])[0])
                );
            }

            $this->assertSame(
                $data->getSystemEntryDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:SystemEntryDate") ?: [])[0])
            );

            $xpathLineStack = $xml->xpath("//doc:LineSummary") ?:
                throw  new \Exception("Fail to parse xml doc lines");

            $this->assertSameSize($data->getLines(), $xpathLineStack);

            foreach ($data->getLines() as $k => $line) {

                $xpathSourceIDStack = $xml->xpath(
                    \sprintf("//doc:LineSummary[%s]/doc:OrderReferences", $k + 1)
                ) ?: [];

                $this->assertSameSize($line->getOrderReference() ?: [], $xpathSourceIDStack);

                foreach ($line->getOrderReference() ?: [] as $n => $sourceID) {

                    $xpathSourceId = $xpathSourceIDStack[$n];

                    $this->assertSame(
                        $sourceID->getOriginatingON(),
                        (string)(($xpathSourceId->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:SourceDocumentID[%s]/doc:OriginatingON",
                                $k + 1, $n + 1
                            )
                        ) ?: [])[0])
                    );

                    $this->assertSame(
                        $sourceID->getInvoiceDate()->format(Date::SQL_DATE),
                        (string)(($xpathSourceId->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:SourceDocumentID[%s]/doc:InvoiceDate",
                                $k + 1, $n + 1
                            )
                        ) ?: [])[0])
                    );
                }

                $this->assertSame(
                    $line->getTaxPointDate()->format(Date::SQL_DATE),
                    (string)(($xml->xpath("//doc:TaxPointDate") ?: [])[$k])
                );

                $xpathReferenceStack = $xml->xpath(
                    \sprintf("//doc:LineSummary[%s]/doc:Reference", $k + 1)
                ) ?: [];

                $this->assertSameSize($line->getReference() ?: [], $xpathReferenceStack);

                foreach ($line->getReference() ?: [] as $x => $reference) {

                    $this->assertSame(
                        $reference,
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Reference[%s]",
                                $k + 1, $x + 1
                            )
                        ) ?: [])[0])
                    );
                }

                $this->assertSame(
                    $line->getDebitCreditIndicator(),
                    (string)(($xml->xpath("//doc:DebitCreditIndicator") ?: [])[$k])
                );

                if ($line->getTotalTaxBase() === null) {
                    $this->assertEmpty(
                        $xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:TotalTaxBase",
                                $k + 1
                            )
                        ) ?: []
                    );
                } else {
                    $this->assertSame(
                        $line->getTotalTaxBase(),
                        (float)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:TotalTaxBase",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );
                }

                if ($line->getAmount() === null) {
                    $this->assertEmpty(
                        $xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Amount",
                                $k + 1
                            )
                        ) ?: []
                    );
                } else {
                    $this->assertSame(
                        $line->getAmount(),
                        (float)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Amount",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );
                }

                if ($line->getTax() === null) {
                    $this->assertEmpty(
                        $xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Tax",
                                $k + 1
                            )
                        ) ?: []
                    );
                } else {
                    $this->assertSame(
                        $line->getTax()->getTaxType(),
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Tax/doc:TaxType",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );

                    $this->assertSame(
                        $line->getTax()->getTaxCountryRegion(),
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Tax/doc:TaxCountryRegion",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );

                    $this->assertSame(
                        $line->getTax()->getTaxCode(),
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:Tax/doc:TaxCode",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );

                    if ($line->getTax()->getTaxPercentage() === null) {
                        $this->assertEmpty(
                            $xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:Tax/doc:TaxPercentage",
                                    $k + 1
                                )
                            ) ?: []
                        );
                    } else {
                        $this->assertSame(
                            $line->getTax()->getTaxPercentage(),
                            (float)(($xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:Tax/doc:TaxPercentage",
                                    $k + 1
                                )
                            ) ?: [])[0])
                        );
                    }

                    if ($line->getTax()->getTotalTaxAmount() === null) {
                        $this->assertEmpty(
                            $xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:Tax/doc:TotalTaxAmount",
                                    $k + 1
                                )
                            ) ?: []
                        );
                    } else {
                        $this->assertSame(
                            $line->getTax()->getTotalTaxAmount(),
                            (float)(($xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:Tax/doc:TotalTaxAmount",
                                    $k + 1
                                )
                            ) ?: [])[0])
                        );
                    }


                    if ($line->getTaxExemptionCode() === null) {
                        $this->assertEmpty(
                            $xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:TaxExemptionCode",
                                    $k + 1
                                )
                            ) ?: []
                        );
                    } else {
                        $this->assertSame(
                            $line->getTaxExemptionCode(),
                            (string)(($xml->xpath(
                                \sprintf(
                                    "//doc:LineSummary[%s]/doc:TaxExemptionCode",
                                    $k + 1
                                )
                            ) ?: [])[0])
                        );
                    }
                }
            } // EndLines

            $this->assertSame(
                $data->getDocumentTotals()->getTaxPayable(),
                (float)(($xml->xpath("//doc:TaxPayable") ?: [])[0])
            );

            $this->assertSame(
                $data->getDocumentTotals()->getNetTotal(),
                (float)(($xml->xpath("//doc:NetTotal") ?: [])[0])
            );

            $this->assertSame(
                $data->getDocumentTotals()->getGrossTotal(),
                (float)(($xml->xpath("//doc:GrossTotal") ?: [])[0])
            );

            if ($work->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $work->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $work->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
