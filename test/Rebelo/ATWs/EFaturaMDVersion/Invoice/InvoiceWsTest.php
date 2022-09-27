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
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\Line;
use Rebelo\ATWs\EFaturaMDVersion\OrderReference;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Response;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\EFaturaMDVersion\WithholdingTax;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class Invoice Ws Test
 *
 * @author João Rebelo
 */
class InvoiceWsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(InvoiceWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\Invoice[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function invoiceDataProvider(): array
    {
        $data = [];

        foreach (['FT', 'FR', 'FS', 'NC', 'ND', 'RP', 'RE', 'CS', 'LD', 'RA'] as $k => $invoiceType) {

            $taxRegistrationNumber     = static::$taxRegistrationNumber;
            $taxEntity                 = "Global";
            $softwareCertificateNumber = 9999;
            $invoiceNo                 = \sprintf("%s %s/%s", $invoiceType, \rand(1, 999999999), \rand(1, 999999999));
            $atcud                     = "0";
            $invoiceDate               = new Date();

            $invoiceStatus = 'N';

            $invoiceStatusDate = new Date();
            $customerTaxID     = "999999990";

            $customerTaxIdCountryCodes = ["PT", "KR", "Desconhecido"];
            $customerTaxIdCountry      = $customerTaxIdCountryCodes[($k > (\count($customerTaxIdCountryCodes) - 1)) ? \count($customerTaxIdCountryCodes) - 1 : $k];
            $hash                      = "ABCD";
            $eacCode                   = "99999";
            $systemEntryDate           = new Date();
            $documentTotals            = new DocumentTotals(1.00, 2.99, 3.99);
            $withholdingTax            = [
                new WithholdingTax("IRS", 999.99),
            ];

            $header = new InvoiceHeader(
                $invoiceNo,
                $atcud,
                $invoiceDate,
                $invoiceType,
                false,
                $customerTaxID,
                $customerTaxIdCountry
            );

            $status = new InvoiceStatus($invoiceStatus, $invoiceStatusDate);

            $lines   = [];
            $lines[] = new Line(
                [
                    new OrderReference("FT 9/99999", new Date()),
                    new OrderReference("FT 99/99999", new Date()),
                ],
                new Date(),
                [
                    "FT A/9", "FT B/9",
                ],
                $invoiceType === "NC" ? "D" : "C",
                null,
                1000.00,
                new Tax(
                    "IVA", "PT", "NOR", 23.00, null
                ),
                null
            );

            $lines[] = new Line(
                null,
                new Date(),
                null,
                $invoiceType === "NC" ? "D" : "C",
                999.00,
                null,
                new Tax(
                    "IS", "PT-MA", "OUT", null, 99.9
                ),
                null
            );

            $lines[] = new Line(
                null,
                new Date(),
                ["FT B/9"],
                $invoiceType === "NC" ? "D" : "C",
                null,
                9.9,
                new Tax(
                    "IVA", "PT-AC", "ISE", 0.0, null
                ),
                "M01"
            );

            $invoiceData = new InvoiceData(
                $header,
                $status,
                $hash,
                false,
                false,
                $eacCode,
                $systemEntryDate,
                $lines,
                $documentTotals,
                $invoiceType === "FT" ? $withholdingTax : null
            );

            $data[] = new Invoice(
                $taxRegistrationNumber,
                $taxEntity,
                $softwareCertificateNumber,
                $invoiceData,
                $k % 2 === 0 ? new RecordChannel("System TUX", "9.9.9") : null
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
        $invoiceWs = new InvoiceWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $changeStatusWs = new ChangeInvoiceStatusWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );

        $deleteWs = new DeleteInvoiceWs(
            static::$credentials["username"],
            static::$credentials["password"],
            ATWS_TEST_CERTIFICATE,
            ATWS_TEST_CERTIFICATE_PASSPHRASE,
            true
        );


        $this->assertSame("RegisterInvoice", $invoiceWs->getWsAction());

        $documentList = [];

        foreach ($this->invoiceDataProvider() as $k => $invoice) {

            $registerResponse = $invoiceWs->submit($invoice);
            $this->assertInstanceOf(Response::class, $registerResponse);
            $this->assertSame(0, $registerResponse->getCode());
            $this->assertNotEmpty($registerResponse->getMessage());

            $changeResponse = $changeStatusWs->submit(
                new ChangeInvoiceStatus(
                    $invoice->getTaxRegistrationNumber(),
                    $invoice->getInvoiceData()->getInvoiceHeader(),
                    new InvoiceStatus("A", new Date()),
                    $invoice->getRecordChannel()
                )
            );

            $this->assertInstanceOf(Response::class, $changeResponse);
            $this->assertSame(0, $changeResponse->getCode());
            $this->assertNotEmpty($changeResponse->getMessage());

            if (($k % 2) === 0) {

                $deleteResponse = $deleteWs->submit(
                    new DeleteInvoice(
                        $invoice->getTaxRegistrationNumber(),
                        [$invoice->getInvoiceData()->getInvoiceHeader()],
                        null,
                        "Test framework",
                        $invoice->getRecordChannel()
                    )
                );

                $this->assertInstanceOf(Response::class, $deleteResponse);
                $this->assertSame(0, $deleteResponse->getCode());
                $this->assertNotEmpty($deleteResponse->getMessage());

            } else {
                $documentList[] = $invoice->getInvoiceData()->getInvoiceHeader();
            }
        }

        $deleteListResponse = $deleteWs->submit(
            new DeleteInvoice(
                self::$taxRegistrationNumber,
                $documentList,
                null,
                "Test framework",
                null
            )
        );

        $this->assertInstanceOf(Response::class, $deleteListResponse);
        $this->assertSame(0, $deleteListResponse->getCode());
        $this->assertNotEmpty($deleteListResponse->getMessage());
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
        foreach ($this->invoiceDataProvider() as $invoice) {

            $ws = new InvoiceWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("invoice");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $invoice);

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
                $invoice->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $invoice->getTaxEntity(),
                (string)(($xml->xpath("//doc:TaxEntity") ?: [])[0])
            );

            $this->assertSame(
                $invoice->getSoftwareCertificateNumber(),
                (int)(($xml->xpath("//doc:SoftwareCertificateNumber") ?: [])[0])
            );

            $data   = $invoice->getInvoiceData();
            $header = $data->getInvoiceHeader();

            $this->assertSame(
                $header->getInvoiceNo(),
                (string)(($xml->xpath("//doc:InvoiceNo") ?: [])[0])
            );

            $this->assertSame(
                $header->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $header->getInvoiceDate()->format(Date::SQL_DATE),
                (string)(($xml->xpath("//doc:InvoiceDate") ?: [])[0])
            );

            $this->assertSame(
                $header->getInvoiceType(),
                (string)(($xml->xpath("//doc:InvoiceType") ?: [])[0])
            );

            $this->assertSame(
                $header->getSelfBillingIndicator(),
                ((int)(($xml->xpath("//doc:SelfBillingIndicator") ?: [])[0])) === 1
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
                $data->getDocumentStatus()->getInvoiceStatus(),
                (string)(($xml->xpath("//doc:InvoiceStatus") ?: [])[0])
            );

            $this->assertSame(
                $data->getDocumentStatus()->getInvoiceStatusDate()->format(Date::DATE_T_TIME),
                (string)(($xml->xpath("//doc:InvoiceStatusDate") ?: [])[0])
            );

            $this->assertSame(
                $data->getHashCharacters(),
                (string)(($xml->xpath("//doc:HashCharacters") ?: [])[0])
            );

            $this->assertSame(
                $data->getCashVATSchemeIndicator(),
                ((int)(($xml->xpath("//doc:CashVATSchemeIndicator") ?: [])[0])) === 1
            );

            $this->assertSame(
                $data->getPaperLessIndicator(),
                ((int)(($xml->xpath("//doc:PaperLessIndicator") ?: [])[0])) === 1
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

                    $this->assertSame(
                        $sourceID->getOriginatingON(),
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:OrderReferences[%s]/doc:OriginatingON",
                                $k + 1, $n + 1
                            )
                        ) ?: [])[0])
                    );

                    $this->assertSame(
                        $sourceID->getOrderDate()->format(Date::SQL_DATE),
                        (string)(($xml->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:OrderReferences[%s]/doc:OrderDate",
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

            $xpathWithholdingTaxStack = $xml->xpath("//doc:WithholdingTax") ?: [];

            $this->assertSameSize(
                $data->getWithholdingTax() ?: [],
                $xpathWithholdingTaxStack
            );

            foreach ($data->getWithholdingTax() ?: [] as $k => $withholdingTax) {

                $this->assertSame(
                    $withholdingTax->getWithholdingTaxAmount(),
                    (float)(($xml->xpath(
                        \sprintf("//doc:WithholdingTax[%s]/doc:WithholdingTaxAmount", $k + 1)
                    ) ?: [])
                    [0])
                );

                $this->assertSame(
                    $withholdingTax->getWithholdingTaxType(),
                    (string)(($xml->xpath(
                        \sprintf("//doc:WithholdingTax[%s]/doc:WithholdingTaxType", $k + 1)
                    ) ?: [])
                    [0])
                );
            }

            if ($invoice->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $invoice->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $invoice->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }
        }
    }

}
