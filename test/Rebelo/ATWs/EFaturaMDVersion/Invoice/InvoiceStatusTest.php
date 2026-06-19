<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 * Class InvoiceTest
 *
 * @author João Rebelo
 */
class InvoiceStatusTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(InvoiceStatus::class))->testReflection(InvoiceStatus::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testInstance(): void
    {
        foreach (['N', 'A', 'F', 'S'] as $status) {

            $statusDate = new Date();

            $invoiceStatus = new InvoiceStatus(
                $status, $statusDate
            );

            $this->assertSame($status, $invoiceStatus->getInvoiceStatus());
            $this->assertSame($statusDate, $invoiceStatus->getInvoiceStatusDate());
        }
    }

    /**
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWrongStatus(): void
    {
        $this->expectException(ATWsException::class);
        $this->expectExceptionMessage("InvoiceStatus must be one of 'N', 'A', 'F', 'S'");
        new InvoiceStatus("K", new Date());
    }

}
