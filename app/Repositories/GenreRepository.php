<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Genre as GenreResources;
use App\Http\Resources\GenreCollection;
use App\Models\Educational;
use App\Models\Genre;
use Illuminate\Support\Facades\Cache;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class GenreRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    protected $genre_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'genres';
        $this->genre_names = Genre::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $genres = Genre::recent($this->cache_name);

            return response()->json(new GenreCollection($genres));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function get_mobile_list()
    {
        try {

            $genres = Genre::recent($this->cache_name);

            return response()->json(new GenreCollection($genres));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->genre_names);

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data)
    {
        try {

            $genre = Genre::storeCache($data, $this->cache_name);

            if ($genre) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {

            $genre = Genre::getCache($id, $this->cache_name, ['lessons']);
            $genre_names =  array_values(array_diff($this->genre_names,[$genre->name]));

            return response()->json([
                'genre'       =>  new GenreResources($genre),
                'genre_names' => $genre_names
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data, $id)
    {
        try {

            $genre = Genre::updateCache($id, $data, $this->cache_name);

            if ($genre) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }


        } catch (\Exception $e) {

            report($e);
        }

    }


    /**
     * @param Educational $educational
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            $genre = Genre::getCache($id, $this->cache_name, ['lessons']);

            if (!$genre->lessons->isEmpty()) {
                return response()->json(['status' => false, 'message' => '不能删除有课程的标签'], 201);
            } else {

                $is_del = Genre::deleteCache($genre->id, $this->cache_name, 'lessons');
                if ($is_del) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            }

        } catch (\Exception $e) {

            report($e);
        }
    }

}