<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];
    
    public $timestamps  = false;


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }
}
