<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateStudent extends Model
{
    protected $connection = 'mysql_vol';
    protected $table = 'private_student';
}
