<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Advert extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $keepRevisionOf = [
        'name',
        'path',
        'url',
        'order',
        'deleted_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'type',
        'url',
        'order',
        'deleted_at'
    ];


}
