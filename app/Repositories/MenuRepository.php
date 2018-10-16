<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class MenuRepository extends Repository
{

    public function getMenus()
    {

        try {

            $menus = Menu::recent('menus');
            $filter_menus = $menus->filter(function ($item) {
                return !$item->parent_id;
            })->all();

            foreach ($filter_menus as $filter_menu) {

                $child_menus = $menus->filter(function ($item) use($filter_menu) {
                    return $item->parent_id == $filter_menu->id;
                })->all();

                $filter_menu->child = collect(array_values($child_menus));
            }

            return $filter_menus;

        } catch (\Exception $exception) {
            report($exception);
        }

    }

}