<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'q_question';
    //
    public function options()
    {
        return $this->hasMany('App\Models\QuestionOption','q_id');
    }
}
