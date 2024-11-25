<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7Integration;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Console\Command;

class ImportUserCertificatesCommand extends Command
{
    protected $signature = 'import:user-certificates';

    protected $description = 'Get certificates of all users for the gateway and import them into the database.';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $layer7 = new Layer7Integration($gateway);
            $collection = $layer7->getUserCertificates();

            foreach ($collection as $pemCertificate) {
                Certificate::fromPemCertificate($gateway, CertificateType::USER_CERTIFICATE, $pemCertificate->pemData);
            }
        }
    }
}
