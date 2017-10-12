<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Teacher
 * @package App\Models
 * @mixin \Eloquent
 */
class Teacher extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $table = 'teachers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname',
        'photo_url',
        'name',
        'office',
        'title',
        'introduction'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thyroidClassPhase()
    {
        return $this->hasMany(ThyroidClassPhase::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thyroidClassCourses()
    {
        return $this->hasMany(ThyroidClassCourse::class);
    }
}
