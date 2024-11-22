<?php

namespace App\Models;

use App\Enumerations\CertificateType;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => CertificateType::class,
        'valid_to' => 'datetime',
        'valid_from' => 'datetime',
    ];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public static function fromPemCertificate(
        Gateway $gateway,
        CertificateType $type,
        string $pemData,
    ): ?self {
        try {
            $certificate = SslCertificate::createFromString($pemData);
        }
        catch (Exception $e) {
            Log::error("Error decoding certificate: ".$e->getMessage());
            return null;
        }

        return Certificate::updateOrCreate([
          'common_name' => $certificate->getDomain(),
        ],
            [
            'gateway_id' => $gateway->id,
            'type' => $type,
            'common_name' => $certificate->getDomain(),
            'valid_from' => $certificate->validFromDate(),
            'valid_to' => $certificate->expirationDate(),
            'certificate' => $pemData,
        ]);
    }
}
