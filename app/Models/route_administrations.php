<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class route_administrations extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','description'];
    protected $table = 'route_administrations';
}
