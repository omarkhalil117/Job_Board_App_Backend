<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
    ];
    function skills()
    {
        return $this->belongsToMany(Skill::class, 'post_skill');
    }
  
    function applications(){
        return $this->hasMany(Application::class);

    function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }
}
