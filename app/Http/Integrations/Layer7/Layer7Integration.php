<?php

namespace App\Http\Integrations\Layer7;

use App\Collections\PemCertificateCollection;
use App\DataTransferObjects\PemCertificate;
use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Requests\PrivateKeys;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

class Layer7Integration
{
    private Layer7RestmanConnector $connector;

    public function __construct(
        public Gateway $gateway,
    )
    {
        $this->connector = new Layer7RestmanConnector($this->gateway);
    }

    public function retrievePrivateKeys(): ?PemCertificateCollection
    {
        $response = $this->connector->send(new PrivateKeys());

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting private keys from Layer7: ' . $response->status(). " - ". $response->body());
            return null;
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $collection = new PemCertificateCollection();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            $pemData = SslCertificate::der2pem(base64_decode($key));
            $collection->add(new PemCertificate($pemData));
        }

        return $collection;
    }

}
