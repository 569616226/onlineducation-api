<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class VideoUrl extends BaseModel
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */

    public $fillable = [
        'url',
        'size',
        'video_id',
        'duration',
    ];


    public function videos()
    {
        return $this->belongsTo( Video::class );
    }
}
