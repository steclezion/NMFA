<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acknowledgement_letter extends Model
{
    use HasFactory;
    protected $tables = "acknowledgement_letters";
    protected $guarded = [];
}
