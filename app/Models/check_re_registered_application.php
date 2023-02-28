<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class check_re_registered_application extends Model
{
    use HasFactory;
   protected  $guard =  [];
   protected $table = 'check_re_registered_applications';
}
