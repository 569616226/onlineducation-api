<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\MenuRepository;

class MenuController extends Controller
{
    private $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * 获取菜单
     * @return mixed
     */
    public function menus()
    {
        $menus = $this->repository->getMenus();

        return response()->json( [ 'menus' => $menus ] );

    }
}
