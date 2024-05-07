<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class , 'candidate_skill');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class , 'post_skill');
    }
}
