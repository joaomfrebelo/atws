<?php

namespace Rebelo\ATWs\Series;

use Rebelo\Enum\AEnum;

/**
 * Status of Self-billing Agreements
 *
 * @method static SelfBillingSettlementStatus A()
 * @method static SelfBillingSettlementStatus F()
 *
 * @since 2.0.2
 */
class SelfBillingSettlementStatus extends AEnum
{

    /**
     * Status active
     * @since 2.0.2
     */
    const A = "A";

    /**
     * Status finalized. The self billing settlement had terminated
     * @since 2.0.2
     */
    const F = "F";

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
