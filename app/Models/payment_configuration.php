<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_configuration extends Model
{
    use HasFactory;
    protected $table = 'payment_configuration';
    protected $guarded = [];
}
