<?php

use App\Enumerations\CertificateType;
use App\Models\Gateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Gateway::class);
            $table->string('type')
                ->default(CertificateType::TRUSTED_CERT);
            $table->string('common_name');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
            $table->text('certificate');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
