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
        Schema::create('user_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("system_id")->constrained("systems")->onUpdate("cascade")->onDelete("cascade");
            $table->integer("status");
            $table->foreignId("created_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_systems');
    }
};
