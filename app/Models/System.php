<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    public function rolesByUser()
    {
        return $this->belongsToMany(Role::class, "user_system_roles", "system_id", "role_id")
            ->withPivot("user_id", "id");
    }
}
