<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\ATWsException;
use Rebelo\Enum\AEnum;

/**
 * Document class code
 *
 *
 * @method static DocumentClassCode SI()
 * @method static DocumentClassCode MG()
 * @method static DocumentClassCode WD()
 * @method static DocumentClassCode PY()
 * @since 1.0.0
 */
class DocumentClassCode extends AEnum
{

    /**
     * Invoices and amending documents
     * @since 1.0.0
     */
    const SI = "SI";

    /**
     * Transport Documents
     * @since 1.0.0
     */
    const MG = "MG";

    /**
     * Conference Documents
     * @since 1.0.0
     */
    const WD = "WD";

    /**
     * Payments (receipts)
     * @since 1.0.0
     */
    const PY = "PY";

    /**
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the string value
     * @return string
     * @since 1.0.0
     */
    public function get(): string
    {
        return (string)parent::get();
    }

    /**
     * Get document class for the document type
     * @param string|\Rebelo\ATWs\Series\DocumentTypeCode $documentType
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public static function mapDocTypeToClassDoc(string|DocumentTypeCode $documentType): self
    {
        $test = \is_string($documentType) ? $documentType : $documentType->get();

        return match ($test) {
            "FT", "FS", "FR", "ND", "NC" => new DocumentClassCode("SI"),
            "GR", "GT", "GA", "GC", "GD" => new DocumentClassCode("MG"),
            "CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA" => new DocumentClassCode("WD"),
            "RC", "RG" => new DocumentClassCode("PY"),
            default => throw new ATWsException("No class document map for document type " . $test)
        };
    }
}
