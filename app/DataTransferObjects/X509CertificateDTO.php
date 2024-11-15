<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class X509CertificateDTO
{
    public function __construct(
        public string $commonName,
        public Carbon $validFrom,
        public Carbon $validTo,
        public string $issuer,
        public string $subject,
    ){}

    static function fromCertificate(string $certificateString): self
    {
        $certificate = openssl_x509_parse($certificateString);
        return new self(
            $certificate['subject']['CN'] ?? 'CN NOT FOUND',
            Carbon::createFromTimestamp($certificate['validFrom_time_t']),
            Carbon::createFromTimestamp($certificate['validTo_time_t']),
            $certificate['issuer']['name'],
            $certificate['subject']['name'],
        );
    }
}
