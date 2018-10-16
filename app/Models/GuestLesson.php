<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GuestLesson extends Pivot
{
    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录动作*/
    protected $keepRevisionOf = array(
        'guest_id',
        'lesson_id',
        'is_like',
        'is_collect',
        'is_pay',
        'last_section',
        'sections',
        'add_date',
        'collect_date',
    );

    protected $dates = [
        'add_date',
        'collect_date'
    ];

    protected $casts = [
        'sections'   => 'array',
        'is_like'    => 'boolen',
        'is_collect' => 'boolen',
        'is_pay'     => 'boolen',
    ];

    public $fillable = [
        'guest_id',
        'lesson_id',
        'is_like',
        'is_collect',
        'is_pay',
        'last_section',
        'sections',
        'add_date',
        'collect_date',
    ];

}
