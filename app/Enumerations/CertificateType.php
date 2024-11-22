<?php

namespace App\Enumerations;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CertificateType: string implements HasLabel, HasColor
{
    case TRUSTED_CERT = 'Trusted Certificate';
    case PRIVATE_KEY = 'Private Key';
    case USER_CERTIFICATE = 'User Certificate';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TRUSTED_CERT => 'success',
            self::USER_CERTIFICATE => 'warning',
            self::PRIVATE_KEY => 'danger',
        };
    }
}
