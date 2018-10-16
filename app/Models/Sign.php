<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Sign
 * @package App\Models
 */
class Sign extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

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
        'inser_type',
        'referee',
        'status',
        'tel',
        'company',
        'deleted_at',
        'offer',
        'train_id',
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @var array
     */
    public $fillable = [
        'name',
        'inser_type',
        'referee',
        'status',
        'tel',
        'company',
        'deleted_at',
        'offer',
        'train_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    /**
     * @return mixed
     */
    public function getSignStatusAttribute()
    {
        $sign_status_array = config('other.sign_status_array');


        return $sign_status_array[$this->status];

    }

    /**
     * @return mixed
     */
    public function getSignInsetTypeAttribute()
    {
        $sign_inset_type_array = config('other.sign_inset_type_array');

        return $sign_inset_type_array[$this->inser_type];

    }
}
