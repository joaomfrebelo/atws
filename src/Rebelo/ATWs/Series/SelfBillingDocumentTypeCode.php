<?php

/**
 * MIT License
 *
 * Copyright (c) 2021 João M F Rebelo
 */
declare(strict_types=1);


namespace Rebelo\ATWs\Series;

use Rebelo\Enum\AEnum;

/**
 *
 * The Document Type Code
 *
 * @method static SelfBillingDocumentTypeCode FT()
 * @method static SelfBillingDocumentTypeCode FS()
 * @method static SelfBillingDocumentTypeCode FR()
 * @method static SelfBillingDocumentTypeCode ND()
 * @method static SelfBillingDocumentTypeCode NC()
 * @since 2.0.2
 */
class SelfBillingDocumentTypeCode extends AEnum
{

    /**
     * @since 1.0.0
     */
    const FT = "FT";

    /**
     * @since 1.0.0
     */
    const FS = "FS";

    /**
     * @since 1.0.0
     */
    const FR = "FR";

    /**
     * @since 1.0.0
     */
    const ND = "ND";

    /**
     * @since 1.0.0
     */
    const NC = "NC";

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
     * Get the Document Class Code for this Document Type Code
     * @return \Rebelo\ATWs\Series\DocumentClassCode
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getDocumentClassCode(): DocumentClassCode
    {
        return DocumentClassCode::mapDocTypeToClassDoc($this->get());
    }

}
