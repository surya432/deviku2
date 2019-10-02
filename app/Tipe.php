<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    //
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url','cmp_id'
    ];
    public function post()
    {
        return $this->hasMany("\App\Post");
    }
}
