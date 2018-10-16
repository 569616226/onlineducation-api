<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Section
 * @package App\Models
 */
class Section extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    /**
     *
     */
    const LESSON_FREE_TYPE = 1;

    /**
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * @var array
     */
    protected $keepRevisionOf = [
        'name',
        'lesson_id',
        'video_id',
        'is_free',
        'deleted_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lesson_id', 'video_id', 'is_free', 'play_times',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /*章节时长*/
    /**
     * @return string
     */
    public function getSectionDurationAttribute()
    {

        $duration = optional($this->video->video_urls->first())->duration;

        if (!$duration) {
            /*格式化时长*/
            $hour = 0;
            $minute = 0;
            $second = 0;
        } else {
            /*格式化时长*/
            $hour = floor($duration / 3600);
            $minute = floor(($duration - $hour * 3600) / 60);
            $second = $duration - ($hour * 3600 + $minute * 60);
        }

        return $hour . '时' . $minute . '分' . $second . '秒';
    }

    /**
     * @return bool
     */
    public function getSectionIsLearnedAttribute()
    {
        if (!guest_user()) {
            return false;
        }

        $learned_lesson_ids = guest_user()->learned_lessons->pluck('id')->toArray();

        return in_array($this->lesson->id, $learned_lesson_ids);
    }

    /**
     * @return bool
     */
    public function getSectionIsLastLearnedAttribute()
    {

        if (!guest_user()) {
            return false;
        }
        $learned_lessons = GuestLesson::where('lesson_id', $this->lesson->id)
            ->where('guest_id', guest_user()->id)
            ->where('last_section', $this->id)
            ->get();

        return !$learned_lessons->isEmpty();
    }

    /**
     * @return bool|mixed
     */
    public function getSectionIsFreeAttribute()
    {
        return $this->lesson->type == self::LESSON_FREE_TYPE ? true : $this->is_free;
    }

}
