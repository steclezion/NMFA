<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_manufacturers extends Model
{
    use HasFactory;
    protected  $table = 'api_manufacturers';
    protected $guarded = ['manu_api_response_tele'];
}
