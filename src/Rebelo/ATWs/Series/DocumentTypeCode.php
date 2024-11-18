<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

/**
 *
 * The Document Type Code
 *
 * @since 1.0.0
 */
enum DocumentTypeCode: string
{

    /**
     * @since 1.0.0
     */
    case GR = "GR";

    /**
     * @since 1.0.0
     */
    case GT = "GT";

    /**
     * @since 1.0.0
     */
    case GA = "GA";

    /**
     * @since 1.0.0
     */
    case GC = "GC";

    /**
     * @since 1.0.0
     */
    case GD = "GD";

    /**
     * @since 1.0.0
     */
    case CM = "CM";

    /**
     * @since 1.0.0
     */
    case CC = "CC";

    /**
     * @since 1.0.0
     */
    case FC = "FC";

    /**
     * @since 1.0.0
     */
    case FO = "FO";

    /**
     * @since 1.0.0
     */
    case NE = "NE";

    /**
     * @since 1.0.0
     */
    case OU = "OU";

    /**
     * @since 1.0.0
     */
    case OR = "OR";

    /**
     * @since 1.0.0
     */
    case PF = "PF";

    /**
     * @since 1.0.0
     */
    case RP = "RP";

    /**
     * @since 1.0.0
     */
    case RE = "RE";

    /**
     * @since 1.0.0
     */
    case CS = "CS";

    /**
     * @since 1.0.0
     */
    case LD = "LD";

    /**
     * @since 1.0.0
     */
    case RA = "RA";

    /**
     * @since 1.0.0
     */
    case RC = "RC";

    /**
     * @since 1.0.0
     */
    case RG = "RG";

    /**
     * @since 1.0.0
     */
    case FT = "FT";

    /**
     * @since 1.0.0
     */
    case FS = "FS";

    /**
     * @since 1.0.0
     */
    case FR = "FR";

    /**
     * @since 1.0.0
     */
    case ND = "ND";

    /**
     * @since 1.0.0
     */
    case NC = "NC";

    /**
     * Get the Document Class Code for this Document Type Code
     *
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getDocumentClassCode(): DocumentClassCode
    {
        return DocumentClassCode::mapDocTypeToClassDoc($this->value);
    }
}
