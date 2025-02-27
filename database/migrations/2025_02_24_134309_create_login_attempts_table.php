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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_system_role_id")->constrained("user_system_roles")->onUpdate("cascade")->onDelete("cascade");
            $table->string("fail_password");
            $table->string("ip_adress");
            $table->string("user_agent")->nullable();
            $table->integer("status");
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
