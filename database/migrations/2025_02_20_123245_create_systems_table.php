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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("url");
            $table->string("description")->nullable();
            $table->integer("status");
            $table->foreignId("created_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("user_system_roles")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->timestamp('delete_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
