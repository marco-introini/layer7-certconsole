<?php

namespace App\DataTransferObjects;

readonly final class KeyPair
{
    public function __construct(
        public string $privateKey,
        public string $certificate
    ) {
    }
}
