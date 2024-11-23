<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7Integration;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Console\Command;

class ImportTrustedCertificatesCommand extends Command
{
    protected $signature = 'import:trusted-certs';

    protected $description = 'Get all trusted certificates for the gateway and import them into the database.';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $layer7 = new Layer7Integration($gateway);
            $collection = $layer7->getTrustedCertificates();

            foreach ($collection as $pemCertificate) {
                Certificate::fromPemCertificate($gateway, CertificateType::TRUSTED_CERT, $pemCertificate->pemData);
            }
        }
    }
}
