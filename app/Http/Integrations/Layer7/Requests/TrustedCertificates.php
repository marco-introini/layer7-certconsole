<?php

namespace App\Http\Integrations\Layer7\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class TrustedCertificates extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/trustedCertificates';
    }
}
