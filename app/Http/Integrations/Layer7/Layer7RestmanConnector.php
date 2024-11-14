<?php

namespace App\Http\Integrations\Layer7;

use App\Models\Gateway;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;

class Layer7RestmanConnector extends Connector
{
    public function __construct(
        private Gateway $gateway
    ) {
    }


    public function resolveBaseUrl(): string
    {
        return $this->gateway->host.'/restman/1.0/';
    }

    protected function defaultHeaders(): array
    {
        return [];
    }

    protected function defaultConfig(): array
    {
        return [
            'verify' => false,
        ];
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new BasicAuthenticator($this->gateway->admin_user, $this->gateway->admin_password);
    }

}
