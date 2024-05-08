<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
    public function post(){
        return $this->belongsTo(Post::class);
    }

    protected $fillable =['status'];
    
 

}
