<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * tag relation on reel
     */
    public function reels(){
        return $this->morphedByMany(Reel::class,'taggable');
    }

    /**
     * tag relation on artical
     */

     public function articals(){
        return $this->morphedByMany(Artical::class,'taggable');
     }

}
