<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Nav
 * @package App\Models
 */
class Nav extends BaseModel
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
        'pictrue',
        'ordered',
        'order_type',
        'is_hide',
        'type',
        'nav_train_ids',
        'nav_lesson_ids',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $casts = [
        'nav_train_ids'  => 'array',
        'nav_lesson_ids' => 'array',
        'is_hide'        => 'boolen'
    ];

    /**
     * @var array
     */
    public $fillable = [
        'name',
        'pictrue',
        'ordered',
        'is_hide',
        'type',
        'nav_lesson_ids',
        'nav_train_ids',
        'order_type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trains()
    {
        return $this->hasMany(Train::class);
    }

    /*显示栏目*/
    public function scopeNotHideNavs($query)
    {
        return $query->where('is_hide', 0)->oldest('ordered')->get();
    }

    /**
     *
     * 获取栏目推荐课程
     * @return array
     */
    public function getNavTopLessonsAttribute()
    {

        $nav_lessons = change_lesson_other_data($this->lessons);

        if ($nav_lessons) {
            $lessons_is_nav = $nav_lessons->filter(function ($item) {
                return in_array($item->id, $this->nav_lesson_ids ?? []) && $item->status == 3;
            })->all();

        } else {

            $lessons_is_nav = [];

        }

        return $lessons_is_nav;
    }

    /**
     *
     * 获取栏目推荐课程
     * @return array
     */
    public function getNavTopTrainsAttribute()
    {
        $nav_trains = $this->trains;

        if ($nav_trains) {
            $trains_is_nav = $nav_trains->filter(function ($item) {
                return in_array($item->id, $this->nav_train_ids ?? []) && in_array($item->status, [1, 0]);
            })->all();

        } else {

            $trains_is_nav = [];

        }

        foreach ($nav_trains as $filter_train) {
            $filter_train['is_train'] = true;
        }

        return $trains_is_nav;
    }
}
