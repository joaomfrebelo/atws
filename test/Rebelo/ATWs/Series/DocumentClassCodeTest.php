<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPStan\Testing\TestCase;

/**
 * DocumentClassCode Test
 */
class DocumentClassCodeTest extends TestCase
{

    /**
     * @return array
     * @throws \Rebelo\Enum\EnumException
     */
    public function providerMapData(): array
    {
        $stack = [];
        foreach (["FT", "FS", "FR", "ND", "NC"] as $type) {
            $stack[] = [$type, new DocumentClassCode("SI")];
        }

        foreach (["GR", "GT", "GA", "GC", "GD"] as $type) {
            $stack[] = [$type, new DocumentClassCode("MG")];
        }

        foreach (["CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA"] as $type) {
            $stack[] = [$type, new DocumentClassCode("WD")];
        }

        foreach (["RC", "RG"] as $type) {
            $stack[] = [$type, new DocumentClassCode("PY")];
        }

        // Instance

        foreach (["FT", "FS", "FR", "ND", "NC"] as $type) {
            $stack[] = [new DocumentTypeCode($type), new DocumentClassCode("SI")];
        }

        foreach (["GR", "GT", "GA", "GC", "GD"] as $type) {
            $stack[] = [new DocumentTypeCode($type), new DocumentClassCode("MG")];
        }

        foreach (["CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA"] as $type) {
            $stack[] = [new DocumentTypeCode($type), new DocumentClassCode("WD")];
        }

        foreach (["RC", "RG"] as $type) {
            $stack[] = [new DocumentTypeCode($type), new DocumentClassCode("PY")];
        }

        return $stack;
    }

    /**
     * @test
     * @dataProvider providerMapData
     * @param string|\Rebelo\ATWs\Series\DocumentTypeCode $documentType
     * @param \Rebelo\ATWs\Series\DocumentClassCode       $expectedClass
     * @return void
     * @throws \Rebelo\ATWs\ATWsException
     */
    public function testMapString(string|DocumentTypeCode $documentType, DocumentClassCode $expectedClass): void
    {
        $this->assertSame(
            $expectedClass->get(),
            DocumentClassCode::mapDocTypeToClassDoc($documentType)->get()
        );
    }

}
