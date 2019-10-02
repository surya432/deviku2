<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cmp extends Model
{
    //
    protected $fillable = [
        'name','status'
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
