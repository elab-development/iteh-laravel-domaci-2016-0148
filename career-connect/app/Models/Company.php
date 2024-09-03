<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function openings()
    {
        return $this->hasMany(Opening::class);
    }
}
