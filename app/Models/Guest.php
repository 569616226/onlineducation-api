<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Guest
 * @package App\Models
 */
class Guest extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, Notifiable, RevisionableTrait, HasMultiAuthApiTokens, HasRoles;

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
        'nickname',
        'gender',
        'picture',
        'position',
        'phone',
        'referee',
        'company',
        'offer',
        'deleted_at'
    );

    /**
     * @var string
     */
    protected $guard_name = 'api'; // or whatever guard you want to use

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nickname',
        'gender',
        'is_subscribe',
        'openid',
        'picture',
        'vip_id',
        'position',
        'referee',
        'company',
        'offer',
        'phone'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /*标签*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    /*设置标签*/
    /**
     * @param array $label_ids
     */
    public function setLabel(array $label_ids)
    {
        $this->labels()->sync($label_ids);
    }

    /*评论*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discusses()
    {
        return $this->hasMany(Discusse::class);
    }

    /*订单*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*订单*/
    /**
     * @return $this
     */
    public function lesson_orders()
    {
        return $this->hasMany(Order::class)->where('type', 1);
    }

    /*订单*/
    /**
     * @return $this
     */
    public function vip_orders()
    {
        return $this->hasMany(Order::class)->where('type', 2);
    }

    /*消息*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    /*vip*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vip()
    {
        return $this->belongsTo(Vip::class);
    }

    //所有课程

    /**
     * @return $this
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->using(GuestLesson::class)
            ->withPivot(config('other.guest_lesson_pivot'));
    }

    //点赞课程

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function like_lessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->wherePivot('is_like', true)
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    //收藏课程

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collect_lessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->wherePivot('is_collect', true)
            ->orderBy('collect_date', 'desc')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    //购买课程

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pay_lessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->wherePivot('is_pay', true)
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    //学习课程

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function learned_lessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->wherePivot('sections', '!=', '')
            ->orderBy('add_date', 'desc')
            ->withPivot(config('other.guest_lesson_pivot'))
            ->using(GuestLesson::class);
    }

    /*oauth password 认证采用用户名认证*/
    /**
     * @param $request
     * @return mixed
     */
    public function findForPassport($request)
    {
        return $this->where('name', $request)->first();
    }

    /**
     * @return mixed
     */
    public function getGuestGenderAttribute()
    {
        return config('other.guest_gender_array')[$this->gender];
    }

    /**
     * @return bool
     */
    public function getGuestIsVipAttribute()
    {
        return $this->vip_id && !$this->guest_is_expire;
    }

    /**
     * @return bool
     */
    public function getGuestIsExpireAttribute()
    {
        return $this->end && $this->end->timestamp < time() ? true : false;
    }

    /**
     * @return null
     */
    public function getGuestVipNameAttribute()
    {
        return $this->vip_id ? $this->vip->name : null;
    }

   /**
     * @return null
     */
    public function getGuestVipEndDateAttribute()
    {

        $order = $this->vip_id ? $this->vip_orders()->latest()->firstOrFail() : null;
        return $order ? $order->end->toDateString() : null;
    }

}
