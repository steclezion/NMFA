<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     

    /**

     * The attributes that are mass assignable.

     *	

     * @var array

     */
    
    use HasFactory;
    protected $table ='products';
 

    protected $fillable = [

        'name', 'detail'

    ];
}
