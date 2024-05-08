<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    function skills()
    {
        return $this->belongsToMany(Skill::class, 'post_skill');
    }

    function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }
}
