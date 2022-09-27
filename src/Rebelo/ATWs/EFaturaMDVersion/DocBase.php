<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

namespace Rebelo\ATWs\EFaturaMDVersion;

/**
 *
 * @author João Rebelo
 * @since  2.0.0
 */
trait DocBase
{


    /**
     * Issuer TIN<br>
     * Portuguese Tax Identification Number (without any country prefix).
     * @return string
     * @since 2.0.0
     */
    public function getTaxRegistrationNumber(): string
    {
        return $this->taxRegistrationNumber;
    }

    /**
     * The communication must specify which establishment it relates to, if applicable.
     * Otherwise, it must be filled in with the “Global” specification.
     * If it comes from an accounting program, or from an integrated accounting and invoicing program,
     * this field must be filled in with the specification “Sede”.
     * @return string
     * @since 2.0.0
     */
    public function getTaxEntity(): string
    {
        return $this->taxEntity;
    }

    /**
     * Certificate number assigned to the program by the AT,
     * in accordance with Ordinance No. 363/2010, of 23 June.
     * If not applicable, it must be filled in with “0” (zero).
     * @return int
     * @since 2.0.0
     */
    public function getSoftwareCertificateNumber(): int
    {
        return $this->softwareCertificateNumber;
    }

    /**
     * @return \Rebelo\ATWs\EFaturaMDVersion\RecordChannel|null
     * @since 2.0.0
     */
    public function getRecordChannel(): ?RecordChannel
    {
        return $this->recordChannel;
    }
}
