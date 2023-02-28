<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTask extends Model
{
    use HasFactory;
    protected $table='main_tasks';
    protected $guard= [];
}
