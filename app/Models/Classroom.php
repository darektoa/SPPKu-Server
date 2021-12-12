<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded  = ['id'];


    public function school() {
        return $this->belongsTo(School::class);
    }
}
