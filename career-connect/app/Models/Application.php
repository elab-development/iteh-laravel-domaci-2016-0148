<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    
    protected $fillable = ['student_id', 'opening_id'];
    
    // Relations

    public function opening()
    {
        return $this->belongsTo(Opening::class, 'opening_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
