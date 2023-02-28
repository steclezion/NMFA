<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class medicinal_products extends Model
{
    use HasFactory;
    protected $table = 'medicinal_products';
    protected $guarded = ['description'];

    public function medicine(){

        return $this->belongsTo(medicines::class);
    }
}
