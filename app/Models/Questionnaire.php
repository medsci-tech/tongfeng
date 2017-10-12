<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $table = 'q_naire';
    //
    protected $fillable = ['title','description','n_status'];
}
