<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('liputan_id')->constrained('liputans')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->text('judul');
            $table->longText('isi');
            $table->longText('old_isi')->nullable();
            $table->longText('gambar');
            $table->bigInteger('like')->default(0);
            $table->enum('volume', ['V1', 'V2']);
            $table->enum('status', ['publish', 'draft', 'ditolak', 'revisi'])->default('draft');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
