<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class application_receipt_of_registration extends Model
{
    protected $table = 'application_receipt_of_registrations';
    protected $guarded = ['old_app_id'];
    use HasFactory;
}
