<?php

use App\Enums\VulnerabilitySeverityEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vulnerabilities', static function (Blueprint $table) {
            $table->id();
            $table->string('cve_id')->unique();
            $table->enum('severity', VulnerabilitySeverityEnum::cases());
            $table->text('description');
            $table->timestamp('published_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index('cve_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
