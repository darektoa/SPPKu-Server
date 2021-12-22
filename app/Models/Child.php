<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded  = ['id'];


    public function payer() {
        return $this->belongsTo(Payer::class);
    }


    public function student() {
        return $this->belongsTo(Student::class);
    }
}
