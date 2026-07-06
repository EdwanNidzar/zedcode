<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_chain_configs', function (Blueprint $table) {
            $table->id();
            // Level jabatan si pemohon (2=Employee, 3=SPV, 4=HR/Manager, dst.)
            $table->unsignedTinyInteger('requester_level');
            // Nama role Spatie yang harus approve (e.g. 'SPV', 'HR / Manager')
            $table->string('approver_role');
            // Level role approver (untuk sorting/display)
            $table->unsignedTinyInteger('approver_level');
            // Urutan dalam rantai (1 = pertama diproses)
            $table->unsignedTinyInteger('step_order');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['requester_level', 'approver_role']);
            $table->index(['requester_level', 'is_active', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_chain_configs');
    }
};
