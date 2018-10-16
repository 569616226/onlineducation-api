<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class BaseModel
 * @package App\Models
 */
class BaseModel extends Model
{


    /**
     * 获取集合数据，倒序
     * @param $query
     * @param $cache_key
     * @param array|null $relation
     * @return mixed
     */
    public function scopeRecent( $query, $cache_key, array $relation = null )
    {
        $key = $cache_key . '_recent';
        $cache = Cache::get( $key );

        if( $cache ){
            return $cache;
        } else{
            if($cache_key == 'guests'){
                if( $relation ){//如果有关联数据
                    $data = $query->with( $relation )->where('is_subscribe',1)->orderBy( 'created_at', 'desc' )->get();
                } else{
                    $data = $query->where('is_subscribe',1)->orderBy( 'created_at', 'desc' )->get();
                }
            }else{

                if( $relation ){//如果有关联数据
                    $data = $query->with( $relation )->orderBy( 'created_at', 'desc' )->get();
                } else{
                    $data = $query->orderBy( 'created_at', 'desc' )->get();
                }

            }

            Cache::tags( $cache_key )->put( $key, $data, 21600 );

            return $data;
        }
    }

    /**
     * @param $query
     * @param $cache_key
     * @param $id
     * @return mixed
     */
    public function scopeNames($query, $cache_key)
    {
        $key = $cache_key . '_names';
        $cache = Cache::get( $key );

        if( $cache ){
            return $cache;
        }else{

            $data = $query->get()->pluck('name')->toArray();

            Cache::tags( $cache_key )->put( $key, $data, 21600 );

            return $data;
        }
    }

    /**
     * 获取单个数据
     * @param $query
     * @param $id
     * @param $cache_key
     * @param array|null $relation
     * @return mixed
     */
    public function scopeGetCache( $query, $id, $cache_key, array $relation = null )
    {
        $key = $cache_key . '_' . $id;

        $cache = Cache::get( $key );

        if( $cache ){
            return $cache;
        } else{

            if( $relation ){//如果有关联数据
                $data = $query->with( $relation )->whereId( $id )->firstOrFail();
            } else{
                $data = $query->whereId( $id )->firstOrFail();
            }

            Cache::tags( $cache_key )->put( $key, $data, 21600 );

            return $data;
        }
    }

    /**
     * 更新数据，删除缓存
     * @param $query
     * @param $id
     * @param array $data
     * @param $cache_key
     */
    public function scopeUpdateCache( $query, $id, array $data, $cache_key )
    {

        $item = $query->whereId( $id )->firstOrFail();

        $item->update( $data );

        Cache::tags( $cache_key )->flush();
        Cache::tags( 'revisions' )->flush();

        return $item;
    }

    /**
     * 新建数据，删除缓存
     * @param $query
     * @param array $data
     * @param $cache_key
     */
    public function scopeStoreCache( $query, array $data, $cache_key )
    {
        $model = $query->create( $data );

        Cache::tags( $cache_key )->flush();
        Cache::tags( 'revisions' )->flush();

        return $model;
    }

    /**
     * 删除数据，删除缓存
     * @param $query
     * @param $id
     * @param $cache_key
     */
    public function scopeDeleteCache( $query, $id, $cache_key, $relation_type = false )
    {
        $item = $query->whereId( $id )->firstOrFail();

        if( $relation_type ){//$relation_type=true,为多对多关系
            $item->$relation_type()->detach();
        }

        $is_del = $item->delete();

        if($is_del){
            Cache::tags( $cache_key )->flush();
            Cache::tags( 'revisions' )->flush();
        }

        return $is_del;

    }

}
