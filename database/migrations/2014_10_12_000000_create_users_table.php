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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string("phone_number")->nullable();
            $table->integer("status");
            $table->string('password');
            $table->foreignId("created_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
