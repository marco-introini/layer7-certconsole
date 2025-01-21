<?php

namespace App\Models;

use App\Enumerations\CertificateType;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

/**
 * @property-read bool $is_valid
 */
class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['formatted_certificate', 'is_valid'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Gateway, $this>
     */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function isValid(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->valid_to >= Carbon::now()
        );
    }

    public function isAboutToExpire(): bool
    {
        return $this->valid_to < Carbon::now()->addDays(config('cert.alert_days_before_expiration'));
    }

    public function formattedCertificate(): Attribute
    {
        return Attribute::make(
            get: fn() => nl2br($this->certificate)
        );
    }

    public static function fromPemCertificate(
        Gateway $gateway,
        CertificateType $type,
        string $pemData,
    ): ?self {
        try {
            $certificate = SslCertificate::createFromString($pemData);
        } catch (Exception $e) {
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
    protected function casts(): array
    {
        return [
            'type' => CertificateType::class,
            'valid_to' => 'datetime',
            'valid_from' => 'datetime',
        ];
    }
}
