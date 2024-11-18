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
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\ATWs\EFaturaMDVersion\RecordChannel;
use Rebelo\ATWs\EFaturaMDVersion\Tax;
use Rebelo\ATWs\EFaturaMDVersion\WithholdingTax;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;
use Rebelo\Date\Pattern;

/**
 * Payment Ws Test
 * @author João Rebelo
 */
class PaymentWsTest extends TestCase
{

    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(PaymentWs::class);
        $this->assertTrue(true);
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\Payment\Payment[]
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function paymentWsDataProvider(): array
    {
        $data = [];

        $data[] = new Payment(
            static::$taxRegistrationNumber,
            "Global",
            9999,
            new PaymentData(
                new PaymentHeader(
                    \sprintf("RC %s/%s", \rand(1, 99999), \rand(1, 99999)),
                    "0",
                    new Date(),
                    "RC",
                    "999999990",
                    "KR"
                ),
                new PaymentStatus("N", new Date()),
                new Date(),
                [
                    new Line(
                        [new SourceDocumentID("FT A/999", (new Date())->addDays(1))],
                        null,
                        "C",
                        9.99,
                        new Tax("IVA", "PT", "ISE", 0.0, null),
                        "M99"
                    ),
                ],
                new DocumentTotals(0.0, 0.0, 9.99),
                null
            ),
            null
        );

        $data[] = new Payment(
            static::$taxRegistrationNumber,
            "Global",
            9999,
            new PaymentData(
                new PaymentHeader(
                    \sprintf("RC %s/%s", \rand(1, 99999), \rand(1, 99999)),
                    "0",
                    new Date(),
                    "RC",
                    "999999990",
                    "Desconhecido"
                ),
                new PaymentStatus("N", new Date()),
                new Date(),
                [
                    new Line(
                        [new SourceDocumentID("FT A/999", new Date())],
                        0.99,
                        "D",
                        9.99,
                        new Tax("IVA", "KR", "NOR", 23.0, null),
                        null
                    ),
                ],
                new DocumentTotals(2.3, 9.99, 12.29),
                [
                    new WithholdingTax("IRS", 1.99),
                ]
            ),
            new RecordChannel("System TUX", "9.9.9")
        );

        $data[] = new Payment(
            static::$taxRegistrationNumber,
            "Global",
            9999,
            new PaymentData(
                new PaymentHeader(
                    \sprintf("RC %s/%s", \rand(1, 99999), \rand(1, 99999)),
                    "0",
                    new Date(),
                    "RC",
                    "999999990",
                    "Desconhecido"
                ),
                new PaymentStatus("N", new Date()),
                new Date(),
                [
                    new Line(
                        [new SourceDocumentID("FT A/999", new Date())],
                        0.99,
                        "D",
                        9.99,
                        new Tax("IVA", "KR", "NOR", 23.0, null),
                        null
                    ),
                    new Line(
                        [
                            new SourceDocumentID("FT B/999", new Date()),
                            new SourceDocumentID("FT C/999", new Date()),
                        ],
                        1.99,
                        "D",
                        9.99,
                        new Tax("IVA", "KR", "NOR", 23.0, null),
                        null
                    ),
                ],
                new DocumentTotals(2.3, 9.99, 12.29),
                [
                    new WithholdingTax("IRS", 1.99),
                    new WithholdingTax("IRC", 0.99),
                ]
            ),
            new RecordChannel("System TUX", "9.9.9")
        );

        return $data;
    }

    /**
     * @test
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateIntervalException
     * @throws \Rebelo\Date\DateParseException
     */
    public function testInstance(): void
    {
        foreach ($this->paymentWsDataProvider() as $payment) {

            $ws = new PaymentWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $this->assertSame("RegisterPayment", $ws->getWsAction());
            $response = $ws->submit($payment);
            $this->assertSame(-0, $response->getCode());
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
        foreach ($this->paymentWsDataProvider() as $payment) {

            $ws = new PaymentWs(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $ref = new \ReflectionClass($ws);

            $propRef = $ref->getProperty("payment");
            $propRef->setAccessible(true);
            $propRef->setValue($ws, $payment);

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
                $ws->getAuditFileVersion(),
                (string)(($xml->xpath("//doc:AuditFileVersion") ?: [])[0])
            );

            $this->assertSame(
                $payment->getTaxRegistrationNumber(),
                (string)(($xml->xpath("//doc:TaxRegistrationNumber") ?: [])[0])
            );

            $this->assertSame(
                $payment->getTaxEntity(),
                (string)(($xml->xpath("//doc:TaxEntity") ?: [])[0])
            );

            $this->assertSame(
                $payment->getSoftwareCertificateNumber(),
                (int)(($xml->xpath("//doc:SoftwareCertificateNumber") ?: [])[0])
            );

            $data   = $payment->getPaymentData();
            $header = $data->getPaymentHeader();

            $this->assertSame(
                $header->getPaymentRefNo(),
                (string)(($xml->xpath("//doc:PaymentRefNo") ?: [])[0])
            );

            $this->assertSame(
                $header->getAtcud(),
                (string)(($xml->xpath("//doc:ATCUD") ?: [])[0])
            );

            $this->assertSame(
                $header->getTransactionDate()->format(Pattern::SQL_DATE),
                (string)(($xml->xpath("//doc:TransactionDate") ?: [])[0])
            );

            $this->assertSame(
                $header->getPaymentType(),
                (string)(($xml->xpath("//doc:PaymentType") ?: [])[0])
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
                $data->getPaymentStatus()->getPaymentStatus(),
                (string)(($xml->xpath("//doc:PaymentStatus") ?: [])[0])
            );

            $this->assertSame(
                $data->getPaymentStatus()->getPaymentStatusDate()->format(Pattern::DATE_T_TIME),
                (string)(($xml->xpath("//doc:PaymentStatusDate") ?: [])[0])
            );

            $this->assertSame(
                $data->getSystemEntryDate()->format(Pattern::DATE_T_TIME),
                (string)(($xml->xpath("//doc:SystemEntryDate") ?: [])[0])
            );

            $xpathLineStack = $xml->xpath("//doc:LineSummary") ?: throw  new \Exception("Fail to parse xml doc lines");

            $this->assertSameSize($data->getLines(), $xpathLineStack);

            foreach ($data->getLines() as $k => $line) {

                $xpathLine = $xpathLineStack[$k];

                $xpathSourceIDStack = $xml->xpath(
                    \sprintf("//doc:LineSummary[%s]/doc:SourceDocumentID", $k + 1)
                ) ?: throw new \Exception("Error parse xml SourceDocumentID");

                $this->assertSameSize($line->getSourceDocumentID(), $xpathSourceIDStack);

                foreach ($line->getSourceDocumentID() as $n => $sourceID) {

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
                        $sourceID->getInvoiceDate()->format(Pattern::SQL_DATE),
                        (string)(($xpathSourceId->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:SourceDocumentID[%s]/doc:InvoiceDate",
                                $k + 1, $n + 1
                            )
                        ) ?: [])[0])
                    );
                }

                if ($line->getSettlementAmount() === null) {
                    $this->assertEmpty($xpathLine->xpath("//doc:SettlementAmount"));
                } else {
                    $this->assertSame(
                        $line->getSettlementAmount(),
                        (float)(($xpathLine->xpath(
                            \sprintf(
                                "//doc:LineSummary[%s]/doc:SettlementAmount",
                                $k + 1
                            )
                        ) ?: [])[0])
                    );
                }

                $this->assertSame(
                    $line->getDebitCreditIndicator(),
                    (string)(($xpathLine->xpath("//doc:DebitCreditIndicator") ?: [])[0])
                );

                $this->assertSame(
                    $line->getAmount(),
                    (float)(($xpathLine->xpath("//doc:Amount") ?: [])[0])
                );

                $xpathTax = $xpathLine->xpath(
                    \sprintf("//doc:LineSummary[%s]/doc:Tax", $k + 1)
                ) ?: [];

                if (\count($xpathTax) > 1) {
                    $this->fail("Only one Tax node is allowed by line");
                }

                if (\count($xpathTax) === 0) {
                    $this->assertNull($line->getTax());
                } else {

                    $this->assertSame(
                        $line->getTax()->getTaxType(),
                        (string)(($xpathLine->xpath("//doc:TaxType") ?: [])[0])
                    );

                    $this->assertSame(
                        $line->getTax()->getTaxCountryRegion(),
                        (string)(($xpathLine->xpath("//doc:TaxCountryRegion") ?: [])[0])
                    );

                    $this->assertSame(
                        $line->getTax()->getTaxCode(),
                        (string)(($xpathLine->xpath("//doc:TaxCode") ?: [])[0])
                    );

                    if ($line->getTax()->getTaxPercentage() === null) {
                        $this->assertEmpty($xpathLine->xpath("//doc:TaxPercentage"));
                    } else {
                        $this->assertSame(
                            $line->getTax()->getTaxPercentage(),
                            (float)(($xpathLine->xpath("//doc:TaxPercentage") ?: [])[0])
                        );
                    }

                    if ($line->getTax()->getTotalTaxAmount() === null) {
                        $this->assertEmpty($xpathLine->xpath("//doc:TotalTaxAmount"));
                    } else {
                        $this->assertSame(
                            $line->getTax()->getTotalTaxAmount(),
                            (float)(($xpathLine->xpath("//doc:TotalTaxAmount") ?: [])[0])
                        );
                    }
                }

                if ($line->getTaxExemptionCode() === null) {
                    $this->assertEmpty($xpathLine->xpath("//doc:TaxExemptionCode"));
                } else {
                    $this->assertSame(
                        $line->getTaxExemptionCode(),
                        (string)(($xpathLine->xpath("//doc:TaxExemptionCode") ?: [])[0])
                    );
                }
            } // End lines loop

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

            if ($payment->getRecordChannel() === null) {
                $this->assertEmpty($xml->xpath("//doc:Sistema"));
            } else {

                $this->assertSame(
                    $payment->getRecordChannel()->getSystem(),
                    (string)(($xml->xpath("//doc:Sistema") ?: [])[0])
                );

                $this->assertSame(
                    $payment->getRecordChannel()->getVersion(),
                    (string)(($xml->xpath("//doc:Versao") ?: [])[0])
                );
            }

        }
    }
}
