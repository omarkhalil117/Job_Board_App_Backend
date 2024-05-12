<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Candidate extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = ['resume','education','faculty','city','experience_level', 'linkedin','github'];
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class , 'candidate_skill');
    }

    public function applications(){
        return $this->hasMany(Application::class);
    }
}
