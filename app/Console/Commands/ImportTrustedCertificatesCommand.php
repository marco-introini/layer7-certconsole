<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7RestmanConnector;
use App\Http\Integrations\Layer7\Requests\TrustedCertificates;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Console\Command;
use Log;
use Spatie\SslCertificate\SslCertificate;

class ImportTrustedCertificatesCommand extends Command
{
    protected $signature = 'import:trusted-certs';

    protected $description = 'Get all trusted certificates for the gateway and import them into the database.';

    public function handle(): void
    {
        $gateway = Gateway::first();
        $connector = new Layer7RestmanConnector($gateway);
        $response = $connector->send(new TrustedCertificates());

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting trusted certificates from Layer7: ' . $response->status(). " - ". $response->body());
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            $pemData = SslCertificate::der2pem(base64_decode($key));
            Certificate::fromPemCertificate($gateway, CertificateType::TRUSTED_CERT, $pemData);
        }

    }
}
