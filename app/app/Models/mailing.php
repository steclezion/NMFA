<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mailing extends Model
{
    use HasFactory;
    protected $table = 'mailings';
    protected $guarded = [];
}
