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
    Schema::create('scholarships', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->string('provider');
        $table->integer('slots');
        $table->decimal('amount', 10, 2)->nullable();
        $table->date('deadline');
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
