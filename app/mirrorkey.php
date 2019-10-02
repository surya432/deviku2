<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mirrorkey extends Model
{
    //
    protected $fillable = [
        'keys', 'cmp_id', 'master_mirror_id'
    ];
    protected $hidden = [
        'cmp_id'
    ];
    public function provider()
    {
        return $this->belongsTo('\App\MasterMirror');
    }
}
