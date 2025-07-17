<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('progress', function (Blueprint $table) {
        $table->id();
        $table->unsignedTinyInteger('day'); // Hari ke-berapa
        $table->text('refleksi')->nullable(); // Isi refleksi
        $table->boolean('selesai')->default(false); // Status checklist
        $table->json('jadwal_checked')->nullable(); // Simpan array checkbox jadwal
        $table->json('mapel_checked')->nullable(); // Simpan array checkbox mapel
        $table->timestamps();
    });
}

};
