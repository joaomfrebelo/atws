<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\Series;


/**
 * Processing Medium Codes
 */
enum ProcessingMediumCodes: string
{

    /**
     * Billing Computer Program
     * @since 1.0.0
     */
    case PI = "PI";

    /**
     * Tax Authority portal
     * @since 1.0.0
     */
    case PF = "PF";

    /**
     * Other electronic mediums
     * @since 1.0.0
     */
    case OM = "OM";

}
