<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSystemRole extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function system()
    {
        return $this->belongsTo(System::class, "system_id", "id");
    }

    public function role()
    {
        return $this->belongsTo(Role::class, "role_id", "id");
    }
}
