<?php

namespace Rebelo\ATWs\Series;

use Rebelo\Enum\AEnum;

/**
 * Status of Self-billing Agreements
 *
 * @method static SelfBillingEntityCode FN()
 * @method static SelfBillingEntityCode FE()
 * @method static SelfBillingEntityCode CE()
 *
 * @since 2.0.2
 */
class SelfBillingEntityCode extends AEnum
{

    /**
     * Portuguese supplier
     * @since 2.0.2
     */
    const FN = "FN";

    /**
     * Foreign supplier
     * @since 2.0.2
     */
    const FE = "FE";
    /**
     * Foreign Acquirer
     * @since 2.0.2
     */
    const CE = "CE";

    /**
     * @param string $value
     *
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
     * @since 2.0.2
     */
    public function get(): string
    {
        return (string)parent::get();
    }
}
