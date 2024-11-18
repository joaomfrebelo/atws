<?php

namespace Rebelo\ATWs\Series;

/**
 * Status of Self-billing Agreements
 *
 * @method static SelfBillingSettlementStatus A()
 * @method static SelfBillingSettlementStatus F()
 *
 * @since 2.0.2
 */
enum SelfBillingSettlementStatus: string
{

    /**
     * Status active
     * @since 2.0.2
     */
    case A = "A";

    /**
     * Status finalized. The self billing settlement had terminated
     * @since 2.0.2
     */
    case F = "F";

}
