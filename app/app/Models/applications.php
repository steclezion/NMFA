<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applications extends Model
{
    use HasFactory;
    protected $table = 'applications';
    protected  $guarded = [];

    public function getFacingsAttribute()
    {
      return explode(',', $this->facings);
    }



    public function certified_application(){

        return $this->hasOne(certified_application::class);
    }

    public function application(){

        return $this->belongsTo(dossier::class);
    }

    public function medicinal_product(){

        return $this->belongsTo(medicinal_products::class, 'medical_product_id', 'id');
    }
}
