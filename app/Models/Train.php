<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Train
 * @package App\Models
 */
class Train extends BaseModel
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
        'pic',
        'title',
        'status',
        'start_at',
        'end_at',
        'deleted_at',
        'address',
        'discrible',
        'qr_code',
        'creator',
        'collect_guest_ids',
        'nav_id',
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'start_at',
        'end_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'collect_guest_ids' => 'array'
    ];

    /**
     * @var array
     */
    public $fillable = [
        'name',
        'pic',
        'title',
        'status',
        'start_at',
        'end_at',
        'deleted_at',
        'address',
        'discrible',
        'collect_guest_ids',
        'qr_code',
        'creator',
        'nav_id',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nav()
    {
        return $this->belongsTo(Nav::class);

    }


    public function signs()
    {
        return $this->hasMany(Sign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    /*未结束活动*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotStopTrains($query)
    {
        return $query->whereIn('status', [0, 1])->get();
    }

    /*未结束推荐活动*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeTopNotStopTrains($query)
    {
        $trains = $query->whereIn('status', [0, 1])->get();

        $top_trains = $trains->filter(function ($item) {
            return in_array($item->id, get_top_train_ids());
        })->all();

        return $top_trains;

    }

    /*未结束未推荐活动*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotTopNotStopTrains($query)
    {
        $trains = $query->whereIn('status', [0, 1])->get();

        $top_trains = $trains->filter(function ($item) {
            return !in_array($item->id, get_top_train_ids());
        })->all();

        return $top_trains;

    }

    /*未开始并且已经到开始时间活动*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotStartOverStartTimeTrains($query)
    {
        $trains = $query->whereStatus(0)->get();

        $filter_trains = $trains->filter(function ($item) {
            return $item->start_at->timestamp <= time();
        })->all();

        return $filter_trains;

    }

    /*进行中并且已经到结束时间活动*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeGoingOverEndTimeTrains($query)
    {
        $trains = $query->whereStatus(1)->get();

        $filter_trains = $trains->filter(function ($item) {
            return $item->end_at->timestamp <= time();
        })->all();

        return $filter_trains;

    }

    /**
     *
     * 活動状态
     * @return mixed
     */
    public function getTrainStatusAttribute()
    {

        $train_status_array = config('other.train_status_array');

        return $train_status_array[$this->status];
    }

    /**
     *
     * 活動状态
     * @return mixed
     */
    public function getTrainCollectAttribute()
    {
        if(!guest_user()){
            return false;
        }

       return in_array(guest_user()->id, $this->collect_guest_ids ?? []);

    }



}
