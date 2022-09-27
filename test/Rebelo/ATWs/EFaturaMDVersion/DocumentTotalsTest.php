<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use PHPUnit\Framework\TestCase;
use Rebelo\Base;

/**
 * Class DocumentTotalsTest
 *
 * @author João Rebelo
 */
class DocumentTotalsTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function testInstance(): void
    {
        (new Base())->testReflection(DocumentTotals::class);

        $taxPayable = 1.99;
        $netTotal = 4.00;
        $grossTotal = 5.99;

        $docTotals = new DocumentTotals($taxPayable, $netTotal, $grossTotal);

        $this->assertSame($taxPayable, $docTotals->getTaxPayable());
        $this->assertSame($netTotal, $docTotals->getNetTotal());
        $this->assertSame($grossTotal, $docTotals->getGrossTotal());
    }

}
