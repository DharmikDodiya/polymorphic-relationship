<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable =[
        'photo_name'
    ];
    /**
     * get all photo comment
     */
    public function comments(){
        return $this->morphMany(Comment::class,'commentable');
    }
}
