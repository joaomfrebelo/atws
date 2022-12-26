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
 * @method static DocumentTypeCode FT()
 * @method static DocumentTypeCode FS()
 * @method static DocumentTypeCode FR()
 * @method static DocumentTypeCode ND()
 * @method static DocumentTypeCode NC()
 * @method static DocumentTypeCode GR()
 * @method static DocumentTypeCode GT()
 * @method static DocumentTypeCode GA()
 * @method static DocumentTypeCode GC()
 * @method static DocumentTypeCode GD()
 * @method static DocumentTypeCode CM()
 * @method static DocumentTypeCode CC()
 * @method static DocumentTypeCode FC()
 * @method static DocumentTypeCode FO()
 * @method static DocumentTypeCode NE()
 * @method static DocumentTypeCode OU()
 * @method static DocumentTypeCode OR()
 * @method static DocumentTypeCode PF()
 * @method static DocumentTypeCode RP()
 * @method static DocumentTypeCode RE()
 * @method static DocumentTypeCode CS()
 * @method static DocumentTypeCode LD()
 * @method static DocumentTypeCode RA()
 * @method static DocumentTypeCode RC()
 * @method static DocumentTypeCode RG()
 * @since 1.0.0
 */
class DocumentTypeCode extends SelfBillingDocumentTypeCode
{

    /**
     * @since 1.0.0
     */
    const GR = "GR";

    /**
     * @since 1.0.0
     */
    const GT = "GT";

    /**
     * @since 1.0.0
     */
    const GA = "GA";

    /**
     * @since 1.0.0
     */
    const GC = "GC";

    /**
     * @since 1.0.0
     */
    const GD = "GD";

    /**
     * @since 1.0.0
     */
    const CM = "CM";

    /**
     * @since 1.0.0
     */
    const CC = "CC";

    /**
     * @since 1.0.0
     */
    const FC = "FC";

    /**
     * @since 1.0.0
     */
    const FO = "FO";

    /**
     * @since 1.0.0
     */
    const NE = "NE";

    /**
     * @since 1.0.0
     */
    const OU = "OU";

    /**
     * @since 1.0.0
     */
    const OR = "OR";

    /**
     * @since 1.0.0
     */
    const PF = "PF";

    /**
     * @since 1.0.0
     */
    const RP = "RP";

    /**
     * @since 1.0.0
     */
    const RE = "RE";

    /**
     * @since 1.0.0
     */
    const CS = "CS";

    /**
     * @since 1.0.0
     */
    const LD = "LD";

    /**
     * @since 1.0.0
     */
    const RA = "RA";

    /**
     * @since 1.0.0
     */
    const RC = "RC";

    /**
     * @since 1.0.0
     */
    const RG = "RG";

    /**
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

}
