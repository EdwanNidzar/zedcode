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
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained('leave_requests')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('order'); // Urutan approver (1=pertama, 2=kedua, dst)
            $table->string('role_label'); // Label jabatan: 'SPV', 'HRD', 'Manager', 'Direktur', 'CEO'
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('actioned_at')->nullable(); // Kapan disetujui/ditolak
            $table->text('catatan')->nullable(); // Catatan approver (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};
