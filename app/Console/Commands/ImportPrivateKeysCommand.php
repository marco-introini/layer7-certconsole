<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7RestmanConnector;
use App\Http\Integrations\Layer7\Requests\PrivateKeys;
use App\Models\Certificate;
use App\Models\Gateway;
use Exception;
use Illuminate\Console\Command;
use Log;
use Spatie\SslCertificate\SslCertificate;

class ImportPrivateKeysCommand extends Command
{
    protected $signature = 'import:private-keys';

    protected $description = 'Get all private keys for the gateway and import them into the database.';

    public function handle(): void
    {
        $gateway = Gateway::first();
        $connector = new Layer7RestmanConnector($gateway);
        $response = $connector->send(new PrivateKeys());

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting private keys from Layer7: ' . $response->status(). " - ". $response->body());
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            $pemData = SslCertificate::der2pem(base64_decode($key));
            Certificate::fromPemCertificate($gateway, CertificateType::PRIVATE_KEY, $pemData);
        }

    }
}
