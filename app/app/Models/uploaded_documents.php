<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class uploaded_documents extends Model
{
    use HasFactory;
    protected $table = 'uploaded_documents';
    protected $guarded = [];


    public function task_trackers()
    {
        return $this->hasMany(TaskTracker::class);
    }
}
