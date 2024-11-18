<?php

namespace Rebelo\ATWs\Series;

/**
 * Status of Self-billing Agreements

 * @since 2.0.2
 */
enum SelfBillingEntityCode : string
{

    /**
     * Portuguese supplier
     * @since 2.0.2
     */
    case FN = "FN";

    /**
     * Foreign supplier
     * @since 2.0.2
     */
    case FE = "FE";
    /**
     * Foreign Acquirer
     * @since 2.0.2
     */
    case CE = "CE";

}
