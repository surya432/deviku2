<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterMirror extends Model
{
    //
    protected $fillable = [
        'name', 'status'
    ];
    protected $hidden = [
        'cmp_id'
    ];
    public function keys()
    {
        return $this->hasMany('\App\mirrorkey');
    }
}
