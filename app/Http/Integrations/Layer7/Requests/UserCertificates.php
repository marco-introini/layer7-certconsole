<?php

namespace App\Http\Integrations\Layer7\Requests;

use App\Models\Gateway;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class UserCertificates extends Request
{

    protected Method $method = Method::GET;

    public function __construct(
        public Gateway $gateway,
        public string $user,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'identityProviders/'.$this->gateway->identity_provider.'/users/'.$this->user.'/certificate';
    }
}
