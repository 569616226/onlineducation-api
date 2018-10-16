<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Educational extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'content',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];


    public $fillable = [ 'name', 'content' ];

    public function lessons()
    {
        return $this->hasOne( Lesson::class );
    }

    public function getEducationalContentAttribute()
    {
        return html_entity_decode( stripslashes( $this->content ) );
    }

}
