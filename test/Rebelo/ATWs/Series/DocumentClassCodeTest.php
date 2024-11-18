<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;

use PHPUnit\Framework\TestCase;

/**
 * DocumentClassCode Test
 */
class DocumentClassCodeTest extends TestCase
{

    /**
     * @return array
     */
    public function providerMapData(): array
    {
        $stack = [];
        foreach (["FT", "FS", "FR", "ND", "NC"] as $type) {
            $stack[] = [$type, DocumentClassCode::SI];
        }

        foreach (["GR", "GT", "GA", "GC", "GD"] as $type) {
            $stack[] = [$type, DocumentClassCode::MG];
        }

        foreach (["CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA"] as $type) {
            $stack[] = [$type, DocumentClassCode::WD];
        }

        foreach (["RC", "RG"] as $type) {
            $stack[] = [$type, DocumentClassCode::PY];
        }

        // Instance

        foreach (["FT", "FS", "FR", "ND", "NC"] as $type) {
            $stack[] = [DocumentTypeCode::from($type), DocumentClassCode::from("SI")];
        }

        foreach (["GR", "GT", "GA", "GC", "GD"] as $type) {
            $stack[] = [DocumentTypeCode::from($type), DocumentClassCode::from("MG")];
        }

        foreach (["CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA"] as $type) {
            $stack[] = [DocumentTypeCode::from($type), DocumentClassCode::from("WD")];
        }

        foreach (["RC", "RG"] as $type) {
            $stack[] = [DocumentTypeCode::from($type), DocumentClassCode::from("PY")];
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
        $this->assertSame($expectedClass, DocumentClassCode::mapDocTypeToClassDoc($documentType));
    }

}
