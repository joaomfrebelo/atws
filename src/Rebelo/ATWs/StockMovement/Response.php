<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use Rebelo\ATWs\AResponse;

/**
 * Response
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Response extends AResponse
{

    /**
     * Identification code assigned by the AT to the document,
     * pursuant to Decree-Law No. 198/2012, of 24 August.
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $aTDocCodeID = null;

    /**     *
     * Identification code assigned by the AT to the document,
     * pursuant to Decree-Law No. 198/2012, of 24 August.
     * @return string|null
     * @since 1.0.0
     */
    public function getATDocCodeID(): ?string
    {
        return $this->aTDocCodeID;
    }

    /**
     *
     * @param string $xml
     * @return static
     * @throws \Rebelo\ATWs\ATWsException
     * @since        1.0.0
     * @noinspection all
     */
    public static function factory(string $xml): static
    {
        /** @var \Rebelo\ATWs\StockMovement\Response $response */
        $response = parent::factory($xml);
        $aTDocCodeID = $response->simpleXMLElement->xpath("//ATDocCodeID");
        if (\count($aTDocCodeID) > 0) {
            $response->aTDocCodeID = (string)$aTDocCodeID[0];
        }
        return $response;//@phpstan-ignore-line
    }

}
