<?php

namespace App\Console\Commands;

use App\Models\Gateway;
use Illuminate\Console\Command;
use function Laravel\Prompts\info;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;

class CheckCertificateCommand extends Command
{
    protected $signature = 'certificate:check';

    protected $description = 'Check Expiration Date for every certificate in the database.';

    public function handle(): void
    {
        foreach (Gateway::all() as $gateway) {
            info($gateway->name);
            foreach ($gateway->certificates as $certificate) {
                if (!$certificate->is_valid) {
                    error($certificate->type->getLabel().': '.$certificate->common_name.' is expired (expired on '.$certificate->valid_to.')');
                    continue;
                }
                if ($certificate->isAboutToExpire()) {
                    warning($certificate->type->getLabel().': '.$certificate->common_name.' is about to expire. ( it will expire on '.$certificate->valid_to.')');
                }
            }
        }
    }
}
