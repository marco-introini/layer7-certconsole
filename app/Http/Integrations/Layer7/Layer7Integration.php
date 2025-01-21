<?php

namespace App\Http\Integrations\Layer7;

use App\Collections\PemCertificateCollection;
use App\DataTransferObjects\PemCertificate;
use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Requests\GatewayUsers;
use App\Http\Integrations\Layer7\Requests\PrivateKeys;
use App\Http\Integrations\Layer7\Requests\TrustedCertificates;
use App\Http\Integrations\Layer7\Requests\UserCertificates;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

class Layer7Integration
{
    private readonly Layer7RestmanConnector $connector;

    public function __construct(
        public Gateway $gateway,
    )
    {
        $this->connector = new Layer7RestmanConnector($this->gateway);
    }

    public function getPrivateKeys(): PemCertificateCollection
    {
        $response = $this->connector->send(new PrivateKeys());
        $collection = new PemCertificateCollection();

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting private keys from Layer7: ' . $response->status(). " - ". $response->body());
            return $collection;
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            $pemData = SslCertificate::der2pem(base64_decode((string) $key));
            $collection->add(new PemCertificate($pemData));
        }

        return $collection;
    }

    public function getTrustedCertificates(): PemCertificateCollection
    {
        $response = $this->connector->send(new TrustedCertificates());
        $collection = new PemCertificateCollection();

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting trusted certificates from Layer7: ' . $response->status(). " - ". $response->body());
            return $collection;
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            $pemData = SslCertificate::der2pem(base64_decode((string) $key));
            $collection->add(new PemCertificate($pemData));
        }

        return $collection;
    }

    public function getUserCertificates(): PemCertificateCollection
    {
        $response = $this->connector->send(new GatewayUsers($this->gateway));
        $collection = new PemCertificateCollection();

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting user from Layer7: '.$response->status()." - ".$response->body());
            return $collection;
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Id')->get();
        foreach ($keys as $key) {
            //$this->info("Getting certificates for user $key");
            $certResponse = $this->connector->send(new UserCertificates($this->gateway, $key));

            if ($certResponse->status() !== 200) {
                // error
                Log::error("Error getting certificate for user $key from Layer7: ".$certResponse->status()." - ".$certResponse->body());
                return $collection;
            }

            $readerCert = $certResponse->xmlReader();

            $keysCert = $readerCert->value('l7:Encoded')->get();

            foreach ($keysCert as $keyCert) {
                $pemData = SslCertificate::der2pem(base64_decode((string) $keyCert));
                $collection->add(new PemCertificate($pemData));
            }
        }
        return $collection;
    }

}
