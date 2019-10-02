<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaLink extends Model
{
    protected $dates = ['deleted_at'];
    protected $table = 'meta_links';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $hidden = [
        'content_id', 'deleted_at','updated_at','create_at'
    ];
    protected $fillable = [
        'kualitas', 'link', 'status', 'content_id',
    ];
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
