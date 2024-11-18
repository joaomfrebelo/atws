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
 * @since 2.0.2
 */
enum SelfBillingDocumentTypeCode :  string
{

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
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getDocumentClassCode(): DocumentClassCode
    {
        return DocumentClassCode::mapDocTypeToClassDoc($this->value);
    }

}
