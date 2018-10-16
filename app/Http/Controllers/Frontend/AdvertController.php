<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Advert as AdvertResource;
use App\Models\Advert;

class AdvertController extends Controller
{
    /**
     * 列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $adverts = Advert::recent('adverts');

        return response()->json(AdvertResource::collection($adverts));
    }
}
