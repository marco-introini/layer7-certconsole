<?php

namespace App\DataTransferObjects;

readonly class KeyPair
{
    public function __construct(
        public string $privateKey,
        public string $certificate
    ) {
    }
}
