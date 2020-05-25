<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $filliable = ['title','body','parent_id'];

    public function child(){
        return $this->hasOne('App\Post', 'parent_id');
    }

}
