<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'job_title',
        'description',
        'responsibilities',
        'qualifications',
        'start_salary',
        'end_salary',
        'location',
        'work_type',
        'application_deadline',
        'status',
    ];
    function skills()
    {
        return $this->belongsToMany(Skill::class, 'post_skill');
    }
  
    function applications(){
        return $this->hasMany(Application::class);
    }
    function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }
}
