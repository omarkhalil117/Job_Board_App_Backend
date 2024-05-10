<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employer extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['company_name', 'logo'];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
