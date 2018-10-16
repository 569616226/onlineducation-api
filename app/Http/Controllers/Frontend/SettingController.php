<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{

    /**
     * @param $id
     */
    public function index($id )
    {
        return Setting::getCache( $id, 'settings' );
    }
}
