<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $hidden = [
    'cmp_id'
    ];
    protected $fillable = [
        'name', 'url', 'category_id','cmp_id', 'createdBy', 'sources'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function links()
    {
        return $this->hasMany(Content::class);
    }
}
