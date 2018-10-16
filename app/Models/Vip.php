<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Vip extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'status',
        'expiration',
        'price',
        'describle',
        'up',
        'down',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'up',
        'down',
    ];

    public $fillable = [
        'name',
        'status',
        'expiration',
        'price',
        'count',
        'describle',
        'up',
        'down',
    ];


    public function guests()
    {
        return $this->hasMany( Guest::class );
    }
}
