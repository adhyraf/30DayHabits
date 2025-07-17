<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('progresses', function (Blueprint $table) {
        $table->id();
        $table->integer('day'); // Hari ke-
        $table->date('tanggal'); // Tanggal
        $table->text('refleksi')->nullable(); // Isi refleksi
        $table->json('jadwal')->nullable(); // Checklist jadwal
        $table->json('mapel')->nullable(); // Checklist mapel
        $table->boolean('selesai')->default(false); // Sudah ditandai selesai
        $table->timestamps();
    });
}

};
