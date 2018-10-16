<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Setting extends BaseModel
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $keepRevisionOf = [
        'index_type',
        'index_count',
        'vip_send_seting',
        'wechat_sub',
        'sign_start_time',
        'top_train_ids',
        'top_lesson_ids',
    ];

    protected $casts = [
        'top_lesson_ids' => 'array',
        'top_train_ids' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'index_type',
        'index_count',
        'vip_send_seting',
        'wechat_sub',
        'sign_start_time',
        'top_lesson_ids',
        'top_train_ids',
    ];

}
