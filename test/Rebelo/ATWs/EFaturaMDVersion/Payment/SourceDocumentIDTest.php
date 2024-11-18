<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Payment;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;
use Rebelo\Date\Date;

/**
 *
 * @author João Rebelo
 */
class SourceDocumentIDTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(SourceDocumentID::class);
        $this->assertTrue(true);
    }

    /**
     * @return void
     * @since 2.0.0
     */
    public function testInstance(): void
    {
        $originatingON = "FT A/999";
        $invoiceDate   = new Date();

        $sourceId = new SourceDocumentID(
            $originatingON, $invoiceDate
        );

        $this->assertSame($originatingON, $sourceId->getOriginatingON());
        $this->assertSame($invoiceDate, $sourceId->getInvoiceDate());
    }

}
