<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class forgot_password_questions extends Model
{
    use HasFactory;
    protected $table = 'forgotpasswordquestions';
    protected $guard = [];
}
