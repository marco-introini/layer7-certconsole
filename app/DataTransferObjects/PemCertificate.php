<?php

namespace App\DataTransferObjects;

final readonly class PemCertificate
{
    public function __construct(
        public string $pemData,
    ) {
    }
}
