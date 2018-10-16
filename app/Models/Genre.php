<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Genre
 * @package App\Models
 */
class Genre extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    /**
     *
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * @var bool
     */
    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    /**
     * @var array
     */
    protected $keepRevisionOf = array(
        'name',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * @var array
     */
    public $fillable = [
        'name',
        'order',

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lessons()
    {
        return $this->belongsToMany( Lesson::class );
    }

    public function trains()
    {
        return $this->belongsToMany( Train::class );
    }

}
