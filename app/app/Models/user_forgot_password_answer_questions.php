<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_forgot_password_answer_questions extends Model
{
    use HasFactory;
    protected $table= 'user_forgot_password_answer_questions';
    protected $fillable = [ 'question_number_one', 'question_number_two','question_number_three','answer_number_one','answer_number_two','answer_number_three','user_id',]; 
}
