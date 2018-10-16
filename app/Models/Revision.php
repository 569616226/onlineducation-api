<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Revision
 * @package App\Models
 */
class Revision extends BaseModel
{
    /**
     * @var array
     */
    public $fillable = [
        'revisionable_id', 
        'revisionable_type',
        'key',
        'user_id',
        'old_value',
        'new_value', 
        'guest' 
    ];

    /**
     * @return string
     */
    public function getLogUserNameAttribute()
    {
        $log_model_array = config('other.log_model_array');

        if ($this->user_id) {
            $users = User::withTrashed()->whereId($this->user_id)->get();

            if($users->isEmpty()){
                $user_name = '系统操作';
            }else{
                $user_name = $users->first()->nickname;
            }


        } elseif ($this->guest_id) {
            $guests = Guest::whereId($this->guest_id)->get();

            if($guests->isEmpty()){
                $user_name = '系统操作';
            }else{
                $user_name = $guests->first()->nickname;
            }

        } elseif ($this->key == 'created_at' && $log_model_array[$this->revisionable_type] == '用户管理') {
            $guests = Guest::whereId($this->revisionable_id)->get();
            if($guests->isEmpty()){
                $user_name = '系统操作';
            }else{
                $user_name = $guests->first()->nickname;
            }
        } else {
            $user_name = '系统操作';
        }

        return $user_name;
    }

    /**
     * @return mixed
     */
    public function getLogTypeAttribute()
    {
        $log_model_array = config('other.log_model_array');

        return $log_model_array[$this->revisionable_type];
    }

    /**
     * @return string
     */
    public function getLogContentAttribute()
    {
        $content = '数据记录：'.strip_tags(html_entity_decode(stripslashes($this->old_value))). ' =》 '
            .strip_tags(html_entity_decode(stripslashes($this->new_value)));
        return $content;
    }
      /**
     * @return string
     */
    public function getLogKeyAttribute()
    {
        $log_key_array = config('other.log_key_array');

        if(array_key_exists($this->key,$log_key_array)){
            if(is_array(($log_key_array[$this->key]))){
                $key_type = $log_key_array[$this->key][$this->new_value];
            }else{
                $key_type = $log_key_array[$this->key];
            }
        }else{
            $key_type = '编辑';
        }

        return $key_type;
    }

}


