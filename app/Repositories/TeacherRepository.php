<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\TeacherCollection;
use App\Models\Teacher;
use Illuminate\Support\Facades\Cache;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class TeacherRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    /**
     * @var
     */
    protected $teacher_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'teachers';
        $this->teacher_names = Teacher::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $teachers = Teacher::recent($this->cache_name, ['lesson']);

            return response()->json(new TeacherCollection($teachers));

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

            return response()->json($this->teacher_names);
        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {

            $teacher = Teacher::storeCache($data, $this->cache_name);

            if ($teacher) {

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

            $teacher = Teacher::getCache($id, $this->cache_name, ['lesson']);
            $teacher_names =  array_values(array_diff($this->teacher_names,[$teacher->name]));

            return response()->json([
                'teacher'       => new \App\Http\Resources\Teacher($teacher),
                'teacher_names' => $teacher_names
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

            $teacher = Teacher::updateCache($id, $data, $this->cache_name);

            if ($teacher) {

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
    public function delete($id)
    {
        try {

            $teacher = Teacher::getCache($id, $this->cache_name, ['lesson']);


            if ( $teacher->lesson ) {

                return response()->json(['status' => false, 'message' => '不能删除有课程关联的讲师'], 201);

            } else {

                $is_del = Teacher::deleteCache($id, $this->cache_name);

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