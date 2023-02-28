<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class dossier extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'dossiers';
    protected $guarded = [];
    
    /**
     * Dossier is the parent table and the following
     * tables are the 'child' tables (they have dossier_id as FK)
     */

    public function dossier_status_lookup()
    {
        return $this->hasOne(dossier_status_lookup::class, 'id', 'assignment_status');
    }

    public function dossier_assignment(){

        return $this->hasOne(dossier_assignment::class);
    }

    public function application(){

        return $this->hasOne(applications::class);
    }

}
