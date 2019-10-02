<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'url', 'cmp_id'
    ];
    public function post()
    {
        return $this->hasMany("\App\Post");
    }
   
    protected $hidden = [
        'cmp_id'
    ];
}
