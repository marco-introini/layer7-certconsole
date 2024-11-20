<?php

namespace App\Models;

use App\Enumerations\CertificateType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SslCertificate\SslCertificate;

class Certificate extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => CertificateType::class,
        'valid_to' => 'datetime',
        'valid_from' => 'datetime',
    ];

    public static function fromSslCertificate(
        SslCertificate $certificate,
        Gateway $gateway,
        CertificateType $type,
    ): self
    {
        $cert = Certificate::create([
            'gateway_id' => $gateway->id,
            'type' => $type,
            'common_name' => $certificate->getDomain(),
            'valid_from' => $certificate->validFromDate(),
            'valid_to' => $certificate->expirationDate(),

        ]);
    }
}
