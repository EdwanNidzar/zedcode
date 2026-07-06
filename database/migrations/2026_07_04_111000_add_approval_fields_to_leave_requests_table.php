<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('keterangan_lainnya')->nullable();
            $table->date('tanggal_cuti_sebelumnya')->nullable();
            $table->integer('jumlah_hari')->default(0);
            $table->date('tanggal_kembali')->nullable();
            $table->string('no_telp_darurat')->nullable();
            
            // Pengganti
            $table->foreignId('pengganti_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Approval Statuses
            $table->enum('status_pengganti', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_atasan', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_hrd', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_manager', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_gm', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Drop old simple status
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['pengganti_user_id']);
            $table->dropColumn([
                'keterangan_lainnya', 'tanggal_cuti_sebelumnya', 'jumlah_hari', 'tanggal_kembali',
                'no_telp_darurat', 'pengganti_user_id',
                'status_pengganti', 'status_atasan', 'status_hrd', 'status_manager', 'status_gm'
            ]);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }
};
