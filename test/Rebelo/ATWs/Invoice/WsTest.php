<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Invoice;

use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\TCredentials;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class WsTest extends TestCase
{
    use TCredentials;

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Ws::class);
        $this->assertTrue(true);
    }


    /**
     * @return \Rebelo\ATWs\Invoice\Invoice[]
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function invoiceDataProvider(): array
    {
        $initNumber = (new Date())->getTimestamp();

        $data = [];

        $invoiceNational = new Invoice(
            static::$taxRegistrationNumber,
            "FT A/" . ++$initNumber, //guaranty a unique number for serial
            new Date(),
            "FT",
            "N",
            "99999",
            static::$taxRegistrationNumber,
            [
                new Line([], 9.99, null, "IVA", "PT", 23.0, null),
            ],
            new DocumentTotals(1.00, 2.99, 3.99)
        );

        $data[] = $invoiceNational;

        //////////////////////////

        $invoiceInternational = new Invoice(
            static::$taxRegistrationNumber,
            "FT A/" . ++$initNumber, //guaranty a unique number for serial
            new Date(),
            "FT",
            "N",
            null,
            new InternationalCustomerTaxID("999999999", "ES"),
            [
                new Line(null, 9.99, null, "IVA", "PT", 0.0, "M09"),
            ],
            new DocumentTotals(1.00, 2.99, 3.99)
        );

        $data[] = $invoiceInternational;

        ////////////////////////////////////////

        $invoiceMultipleLines = new Invoice(
            static::$taxRegistrationNumber,
            "FT A/" . ++$initNumber, //guaranty a unique number for serial
            new Date(),
            "FT",
            "N",
            null,
            static::$taxRegistrationNumber,
            [
                new Line([], 9.99, null, "IVA", "PT", 23.0, null),
                new Line(
                    [new OrderReference("OR OR/1999", new Date())],
                    99.99,
                    null,
                    "IVA", "PT",
                    0.0,
                    "M09"
                ),
            ],
            new DocumentTotals(1.00, 109.98, 103.98)
        );

        $data[] = $invoiceMultipleLines;

        ///////////////////////////////

        $creditNote = new Invoice(
            static::$taxRegistrationNumber,
            "NC A/" . ++$initNumber, //guaranty a unique number for serial
            new Date(),
            "NC",
            "N",
            "11111",
            static::$taxRegistrationNumber,
            [
                new Line(
                    [new OrderReference("FR FR/4999", new Date())],
                    9.99,
                    null,
                    "IVA",
                    "PT",
                    23.0,
                    null
                ),
            ],
            new DocumentTotals(1.00, 2.99, 3.99)
        );

        $data[] = $creditNote;

        //////////////////////////

        return $data;
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function testSubmission(): void
    {
        foreach ($this->invoiceDataProvider() as $k => $invoice) {
            $ws = new Ws(
                static::$credentials["username"],
                static::$credentials["password"],
                ATWS_TEST_CERTIFICATE,
                ATWS_TEST_CERTIFICATE_PASSPHRASE,
                true
            );

            $response = $ws->submit($invoice);
            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame(
                0,
                $response->getCode(),
                \sprintf("Invoice key '%s', Invoice No: '%s'", $k, $invoice->getInvoiceNo())
            );
            $this->assertNotEmpty($response->getMessage());
        }
    }

}
