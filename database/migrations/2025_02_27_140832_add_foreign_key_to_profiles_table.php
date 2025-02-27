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
        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignId("created_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
        });
    }
};
