<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Lesson
 * @package App\Models
 */
class Lesson extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    /**
     *
     */
    const LESSON_ORDER_TYPE = 1;
    /**
     *
     */
    const LESSON_FREE_TYPE = 1;
    /**
     *
     */
    const LESSON_PAY_TYPE = 2;
    /**
     *
     */
    const LESSON_VIP_TYPE = 3;
    /**
     *
     */
    const LESSON_TRAIN_TYPE = 4;

    /**
     * @var bool
     */
    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录动作*/
    /**
     * @var array
     */
    protected $keepRevisionOf = array(
        'name',
        'title',
        'type',
        'nav_id',
        'teacher_id',
        'educational_id',
        'status',
        'pictrue',
        'price',
        'learning',
        'for',
        'describle',
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
    public $fillable = [
        'name',
        'title',
        'type',
        'nav_id',
        'teacher_id',
        'educational_id',
        'status',
        'pictrue',
        'price',
        'out_like',
        'learning',
        'for',
        'describle',
        'out_play_times',
    ];

    /*标签*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    /*栏目*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nav()
    {
        return $this->belongsTo(Nav::class);
    }

    /*讲师*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /*教务模板*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function educational()
    {
        return $this->belongsTo(Educational::class);
    }

    /*课程章节*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discusses()
    {
        return $this->hasMany(Discusse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function better_discusses()
    {
        return $this->hasMany(Discusse::class)
            ->where('pid', 0)
            ->where('is_better', 1)
            ->latest();
    }

    /*购买者*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pay_guests()
    {
        return $this->belongsToMany(Guest::class)
            ->wherePivot('is_pay')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    //点赞课程用户

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function like_guests()
    {
        return $this->belongsToMany(Guest::class)
            ->wherePivot('is_like')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    //收藏课程用户

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collect_guests()
    {
        return $this->belongsToMany(Guest::class)
            ->wherePivot('is_collect')
            ->orderBy('collect_date', 'desc')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    /*课程的用户*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function guests()
    {
        return $this->belongsToMany(Guest::class)
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    /*课程学员*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Guest::class)
            ->wherePivot('sections', '!=', '')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->with('lessons')
            ->using(GuestLesson::class);
    }

    /*上架课程*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeUpLessons($query)
    {
        return $query->whereStatus(3)->get();
    }

    /*推荐上架课程*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeTopUpLessons($query)
    {
        $lessons = $query->whereStatus(3)->get();

        $top_lessons = $lessons->filter(function ($item) {
            return in_array($item->id, get_top_lesson_ids());
        })->all();

        return $top_lessons;
    }


    /*未推荐上架课程*/
    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotTopUpLessons($query)
    {
        $lessons = $query->whereStatus(3)->get();

        $top_lessons = $lessons->filter(function ($item) {
            return !in_array($item->id, get_top_lesson_ids());
        })->all();

        return $top_lessons;
    }

    /*课程类型*/
    /**
     * @return mixed
     */
    public function getLessonTypeAttribute()
    {

        $lesson_type_array = config('other.lesson_type_array');

        return $lesson_type_array[$this->type];
    }

    /*课程狀態*/
    /**
     * @return mixed
     */
    public function getLessonStatusAttribute()
    {

        $lesson_status_array = config('other.lesson_status_array');

        return $lesson_status_array[$this->status];
    }


    /*课程价格*/
    /**
     * @return mixed
     */
    public function getLessonPriceAttribute()
    {

        $lesson_type_array = config('other.lesson_type_array');

        $price = $this->type == 2 ? $this->price : $lesson_type_array[$this->type];

        return $price;
    }

    /*课程总销售额*/
    /**
     * @return float|int|mixed
     */
    public function getLessonTotalPricesAttribute()
    {

        $lesson_type_array = config('other.lesson_type_array');


        if ($this->type == 2) {
            $orders = \App\Models\Order::recent('orders');
            $filter_orders = $orders->filter(function ($order) {
                return $order->order_type_id == $this->id && $order->type == self::LESSON_ORDER_TYPE;
            })->count();

            $total_prices = $this->price * $filter_orders;
        } else {

            $total_prices = $lesson_type_array[$this->type];

        }

        return $total_prices;
    }

    /**
     * @return int
     */
    public function getLessonIsTopAttribute()
    {

        return in_array($this->id, get_top_lesson_ids() ?? []) ? 1 : 0;

    }


    /*课程是否显示*/
    /**
     * @return mixed
     */
    public function getLessonIsShowAttribute()
    {

        if(!guest_user()){
            return false;
        }

        if ($this->type == self::LESSON_FREE_TYPE) {
            return true;
        } elseif ($this->type == self::LESSON_VIP_TYPE) {
            return guest_user()->guest_is_vip;
        } elseif ($this->type == self::LESSON_PAY_TYPE) {
            $guest_lessons = GuestLesson::where('lesson_id', $this->id)
                ->where('guest_id', guest_user()->id)
                ->where('is_pay', 1)
                ->get();
            return !$guest_lessons->isEmpty();
        } elseif ($this->type == self::LESSON_TRAIN_TYPE) {
            return true;
        }

    }


    /*课程课程是否点赞*/
    /**
     * @return mixed
     */
    public function getLessonIsLikeAttribute()
    {
        if(!guest_user()){
            return false;
        }

        $guest_lessons = GuestLesson::where('lesson_id', $this->id)
            ->where('guest_id', guest_user()->id)
            ->where('is_like', 1)
            ->get();

        return !$guest_lessons->isEmpty();

    }

    /*课程是否收藏*/
    /**
     * @return mixed
     */
    public function getLessonIsCollectAttribute()
    {
        if(!guest_user()){
            return false;
        }

        $guest_lessons = GuestLesson::where('lesson_id', $this->id)
            ->where('guest_id', guest_user()->id)
            ->where('is_collect', 1)
            ->get();

        return !$guest_lessons->isEmpty();
    }


    /*课程是否收藏*/
    /**
     * @return mixed
     */
    public function getLessonBetterDiscussesAttribute()
    {

        $better_discusses = $this->discusses->filter(function ($item) {
            return $item->is_better;
        })->count();

        return $better_discusses;
    }

    /*课程总时长*/
    /**
     * @return string
     */
    public function getLessonDurationAttribute()
    {
        $sections = $this->sections;

        $lesson_duration = 0;
        foreach ($sections as $section) {

            if ($section->video && !$section->video->video_urls->isEmpty()) {
                $duration = optional($section->video->video_urls->first())->duration;
                $lesson_duration += $duration;
            }

        }

        /*格式化时长*/
        $hour = floor($lesson_duration / 3600);
        $minute = floor(($lesson_duration - $hour * 3600) / 60);
        $second = $lesson_duration - ($hour * 3600 + $minute * 60);

        return $hour . '时' . $minute . '分' . $second . '秒';
    }

    /*课程最后学习章节*/
    /**
     * @return null
     */
    public function getLessonLastSectionNameAttribute()
    {

        $guest_lessons = GuestLesson::where('lesson_id', $this->id)
            ->where('guest_id', guest_user()->id)
            ->whereNotNull('last_section')
            ->get();

        if ($guest_lessons->isEmpty()) {

            return null;

        } else {

            $last_section_id = $guest_lessons->first()->last_section;
            $sections = Section::withTrashed()->whereId($last_section_id)->get();

            if ($sections->isEmpty()) {
                return null;
            } else {
                return $sections->first()->name;
            }

        }

    }

    /**
     * @return int
     */
    public function getDiscusseCountsAttribute()
    {

        $discusse_counts = $this->discusses()
            ->where('pid', 0)
            ->get()
            ->count();

        return $discusse_counts;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|null
     */
    public function getLessonDiscussesAttribute()
    {
        $lesson_discusses = $this->discusses()
            ->where('pid', 0)
            ->latest()
            ->get();
        $discusses = $this->discusses->isEmpty() ? null : \App\Http\Resources\Mobile\Discusse::collection($lesson_discusses);

        return $discusses;
    }

    /**
     * @return mixed
     */
    public function getLessonLikesAttribute()
    {
        $lesson_likes = GuestLesson::where('lesson_id', $this->id)
            ->where('is_like', true)
            ->get()
            ->count();

        return $lesson_likes;
    }

    /**
     * @return array|null
     */
    public function getLessonLearningAttribute()
    {
        return $this->learning ? explode('--', $this->learning) : null;
    }

    /**
     * @return array|null
     */
    public function getLessonPlayTimesAttribute()
    {
        $sections = $this->sections;

        $lesson_play_times = 0;
        foreach ($sections as $section) {

            $lesson_play_times += $section->play_times;

        }

        return $lesson_play_times;
    }

    /**
     * @return mixed
     */
    public function getMobileLessonPlayTimesAttribute()
    {
       return $this->out_play_times > 0 ? $this->out_play_times : $this->lesson_play_times;
    }

    /**
     * @return mixed
     */
    public function getMobileLessonLikesAttribute()
    {
        return  $this->out_like > 0 ? $this->out_like : $this->lesson_likes;
    }

}
