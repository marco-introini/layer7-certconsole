<?php

namespace App\Enumerations;

use Filament\Support\Contracts\HasLabel;

enum CertificateType: string implements HasLabel
{
    case TRUSTED_CERT = 'Trusted Certificate';
    case PRIVATE_KEY = 'Private Key';
    case USER_CERTIFICATE = 'User Certificate';


    public function getLabel(): ?string
    {
        return $this->value;
    }
}
