<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Video
 * @package App\Models
 */
class Video extends BaseModel
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
        'status',
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
    public $fillable = ['name', 'status', 'fileId', 'created_at', 'edk_key'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function section()
    {
        return $this->hasOne(Section::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function video_urls()
    {
        return $this->hasMany(VideoUrl::class);
    }

    /**
     * @return mixed
     */
    public function getVideoStatusAttribute()
    {
        $video_status_array = config('other.video_status_array');

        return $video_status_array[$this->status];
    }

    /**
     * @return mixed
     */
    public function getVideoUrlAttribute()
    {

        $video_other_data = get_video_other_data($this->status, $this->video_urls);

        return $video_other_data['url'];
    }

    /**
     * @return mixed
     */
    public function getVideoSizeAttribute()
    {

        $video_other_data = get_video_other_data($this->status, $this->video_urls);

        return $video_other_data['size'];
    }

    /**
     * @return mixed
     */
    public function getVideoDurationAttribute()
    {

        $video_other_data = get_video_other_data($this->status, $this->video_urls);
        if(!$video_other_data['duration']){
            /*格式化时长*/
            $hour = 0;
            $minute = 0;
            $second = 0;
        }else{
            /*格式化时长*/
            $hour = floor($video_other_data['duration'] / 3600);
            $minute = floor(($video_other_data['duration'] - $hour * 3600) / 60);
            $second = $video_other_data['duration'] - ($hour * 3600 + $minute * 60);
        }


        return $hour . '时' . $minute . '分' . $second . '秒';
    }
}
