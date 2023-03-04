<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    public function certification(){

        return $this->hasOne(certification::class);
        //todo check hasOne
        // can we have other certification with the same decision_id --> if so hasMany
    }
}
