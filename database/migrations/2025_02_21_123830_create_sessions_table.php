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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_system_id")->constrained("user_systems")->onDelete("cascade")->onUpdate("cascade");
            $table->string("ip_adress");
            $table->string("user_agent")->nullable();
            $table->text("auth_token")->nullable();
            $table->integer("status");
            $table->foreignId("created_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("users")->onUpdate("cascade")->onDelete("cascade");
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();
            $table->timestamp("logout_at")->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
