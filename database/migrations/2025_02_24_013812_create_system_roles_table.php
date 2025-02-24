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


        Schema::create('system_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained("roles")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("system_id")->constrained("systems")->onUpdate("cascade")->onDelete("cascade");
            $table->integer("status");
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
        Schema::dropIfExists('system_roles');
    }
};
