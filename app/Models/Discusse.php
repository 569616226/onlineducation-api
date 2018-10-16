<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Discusse extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;//模型创建动作

    protected $keepRevisionOf = [
        'is_better',
        'deleted_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'guest_id', 'lesson_id', 'is_better','pid'
    ];

    public function guest()
    {
        return $this->belongsTo( Guest::class );
    }

    public function lesson()
    {
        return $this->belongsTo( Lesson::class );
    }

    /*获取老师信息*/
	public function getTeacherMsgAttribute()
	{
		return $this->pid == 0 ? Discusse::where('pid',$this->id)->get()->first() : null;
	}

}
