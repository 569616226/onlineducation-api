<?php

/*
 * 项目图片
 *
 * */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{


    public static $rules = [ 'img' => 'required|mimes:png,gif,jpeg,jpg' ];
    public static $messages = [ 'img.mimes' => '图片必须为png,gif,jpeg,jpg格式', 'img.required' => '请选择图片' ];

}
