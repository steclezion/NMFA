<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Assignment_unassignment extends Model
{
    use HasFactory;
    use HasFactory;
    protected $guarded = [];
    
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    public function permissions() {

        return $this->belongsToMany(Permission::class,'roles_permissions');
            
     }

     

    public function applications()
    {
        return $this->hasMany(applications::class);
    }


    public function company_suppliers()
    {
        return $this->hasMany(company_suppliers::class);
    }



    public function  manufacturers()
    {
        return $this->hasMany(manufacturers::class);
    }




    public function medicinal_products()

    {
        return $this->hasMany(medicinal_products::class);

    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

}
