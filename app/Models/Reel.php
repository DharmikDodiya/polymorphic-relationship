<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;

    protected $fillable =[
        'reel_name'
    ];

    public function tags(){
        return $this->morphToMany(Tag::class,'taggable');
    }
}
