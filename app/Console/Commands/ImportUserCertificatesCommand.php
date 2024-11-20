<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7RestmanConnector;
use App\Http\Integrations\Layer7\Requests\GatewayUsers;
use App\Http\Integrations\Layer7\Requests\UserCertificates;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Console\Command;
use Log;
use Spatie\SslCertificate\SslCertificate;

class ImportUserCertificatesCommand extends Command
{
    protected $signature = 'import:user-certificates';

    protected $description = 'Get certificates of all users for the gateway and import them into the database.';

    public function handle(): void
    {
        $gateway = Gateway::first();
        $connector = new Layer7RestmanConnector($gateway);
        $response = $connector->send(new GatewayUsers($gateway));

        if ($response->status() !== 200) {
            // error
            Log::error('Error getting user from Layer7: ' . $response->status(). " - ". $response->body());
        }

        $reader = $response->xmlReader();
        //$raw = $response->body();

        $keys = $reader->value('l7:Id')->get();
        foreach ($keys as $key) {
            $this->info("Getting certificates for user $key");
            $certResponse = $connector->send(new UserCertificates($gateway, $key));

            if ($certResponse->status() !== 200) {
                // error
                Log::error("Error getting certificate for user $key from Layer7: " . $certResponse->status(). " - ". $certResponse->body());
            }

            $readerCert = $certResponse->xmlReader();

            $keysCert = $readerCert->value('l7:Encoded')->get();

            foreach ($keysCert as $keyCert) {
                $pemData = SslCertificate::der2pem(base64_decode($keyCert));
                Certificate::fromPemCertificate($gateway, CertificateType::USER_CERTIFICATE, $pemData);
            }
        }

    }
}
