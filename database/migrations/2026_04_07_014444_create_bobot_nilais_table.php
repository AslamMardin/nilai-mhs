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
       Schema::create('bobot_nilai', function (Blueprint $table) {
    $table->id();
    $table->float('kehadiran')->default(10);
    $table->float('akhlak')->default(10);
    $table->float('keaktifan')->default(15);
    $table->float('tugas')->default(15);
    $table->float('uts')->default(20);
    $table->float('uas')->default(30);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_nilais');
    }
};
