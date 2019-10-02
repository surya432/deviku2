<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $hidden = [
        'cmp_id', 'post_id', 'deleted_at', 'updated_at'
    ];
    protected $fillable = [
        'name', 'url', 'post_id', 'cmp_id', 'createdBy'
    ];
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function links()
    {
        return $this->hasMany(MetaLink::class);
    }
}
