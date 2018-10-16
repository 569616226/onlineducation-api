<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;


use App\Http\Resources\RevisionCollection;
use App\Models\Advert;
use App\Models\Discusse;
use App\Models\Educational;
use App\Models\Genre;
use App\Models\Guest;
use App\Models\Label;
use App\Models\Lesson;
use App\Models\Message;
use App\Models\Nav;
use App\Models\Order;
use Spatie\Permission\Models\Permission;
use App\Models\Revision;
use Spatie\Permission\Models\Role;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Video;
use App\Models\Vip;
use Illuminate\Support\Facades\Cache;


/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class LogsRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;


    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'logs';
    }

    public function getLists()
    {
        try {

            $logs = Revision::recent('revisions');

            $filter_logs = $logs->take(2000)->all();

            return new RevisionCollection(collect($filter_logs));

        } catch (\Exception $e) {

            report($e);

        }
    }

}