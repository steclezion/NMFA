<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class issue_query extends Model
{
    use HasFactory;
    protected $table = "issue_queries";
    protected $guarded = [];
}
