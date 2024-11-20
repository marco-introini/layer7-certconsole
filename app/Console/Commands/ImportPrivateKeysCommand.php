<?php

namespace App\Console\Commands;

use App\DataTransferObjects\X509CertificateDTO;
use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7RestmanConnector;
use App\Http\Integrations\Layer7\Requests\PrivateKeys;
use App\Models\Certificate;
use App\Models\Gateway;
use App\Services\CertificateUtilityService;
use Exception;
use Illuminate\Console\Command;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
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
            // errore
        }

        $reader = $response->xmlReader();
        $raw = $response->body();

        $keys = $reader->value('l7:Encoded')->get();
        foreach ($keys as $key) {
            try {
                $certResource = @openssl_x509_read(SslCertificate::der2pem(base64_decode($key)));
                if ($certResource === false) {
                    echo("Certificate is not a valid DER format certificate.");
                    continue;
                }
                $cert = SslCertificate::createFromString(SslCertificate::der2pem(base64_decode($key)));
                Certificate::fromSslCertificate($cert, $gateway, CertificateType::PRIVATE_KEY);
            }
            catch (Exception $e) {
                echo $e->getMessage();
                continue;
            }
        }


    }
}
