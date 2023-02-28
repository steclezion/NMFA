<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class certified_application extends Model
{
    use HasFactory;


    public function applications(){

        return $this->belongsTo(applications::class);
    }
}
