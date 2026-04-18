<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
        $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
        $table->text('remarks')->nullable();
        $table->date('application_date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
