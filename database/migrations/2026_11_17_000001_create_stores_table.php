<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(500);
            $table->string('preview_picture')->nullable();
            $table->text('address')->nullable();
            $table->json('phones')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('opens_at')->nullable();
            $table->json('location')->nullable();
            $table->text('detail_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
