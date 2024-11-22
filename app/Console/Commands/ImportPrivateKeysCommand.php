<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateType;
use App\Http\Integrations\Layer7\Layer7Integration;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Console\Command;

class ImportPrivateKeysCommand extends Command
{
    protected $signature = 'import:private-keys';

    protected $description = 'Get all private keys for the gateway and import them into the database.';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            $layer7 = new Layer7Integration($gateway);
            $collection = $layer7->retrievePrivateKeys();

            foreach ($collection as $pemCertificate) {
                Certificate::fromPemCertificate($gateway, CertificateType::PRIVATE_KEY, $pemCertificate->pemData);
            }
        }
    }
}
