<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\ATWs\ATWsException;

/**
 * Document class code
 * @since 1.0.0
 */
enum DocumentClassCode: string
{

    /**
     * Invoices and amending documents
     * @since 1.0.0
     */
    case SI = "SI";

    /**
     * Transport Documents
     * @since 1.0.0
     */
    case MG = "MG";

    /**
     * Conference Documents
     * @since 1.0.0
     */
    case WD = "WD";

    /**
     * Payments (receipts)
     * @since 1.0.0
     */
    case PY = "PY";

    /**
     * Get document class for the document type
     * @param string|\Rebelo\ATWs\Series\DocumentTypeCode $documentType
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public static function mapDocTypeToClassDoc(string|DocumentTypeCode $documentType): self
    {
        $test = \is_string($documentType) ? $documentType : $documentType->value;

        return match ($test) {
            "FT", "FS", "FR", "ND", "NC" => DocumentClassCode::SI,
            "GR", "GT", "GA", "GC", "GD" => DocumentClassCode::MG,
            "CM", "CC", "FC", "FO", "NE", "OU", "OR", "PF", "RP", "RE", "CS", "LD", "RA" => DocumentClassCode::WD,
            "RC", "RG" => DocumentClassCode::PY,
            default => throw new ATWsException("No class document map for document type " . $test)
        };
    }
}
