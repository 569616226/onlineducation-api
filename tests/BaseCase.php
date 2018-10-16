<?php

namespace Tests;

use App\Models\Advert;
use App\Models\Discusse;
use App\Models\Educational;
use App\Models\Genre;
use App\Models\Guest;
use App\Models\Lesson;
use App\models\Menu;
use App\Models\Nav;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Sign;
use App\Models\Teacher;
use App\Models\Train;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoUrl;
use App\Models\Vip;
use App\Models\Order;
use App\Models\Message;
use Faker\Generator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\WithFaker;

abstract class BaseCase extends TestCase
{

    use WithFaker, CreatesApplication;

    protected $user;
    protected $frozen_user;
    protected $lesson;
    protected $lesson_4;
    protected $down_lesson;
    protected $del_lesson;
    protected $first_genre;
    protected $second_genre;
    protected $educational;
    protected $nav;
    protected $train_nav;
    protected $teacher;
    protected $video;
    protected $section;
    protected $vip;
    protected $setting;
    protected $guest;
    protected $frozen_guest;
    protected $vip_order;
    protected $lesson_order;
    protected $message;
    protected $label;
    protected $discusse;
    protected $role;
    protected $permission;
    protected $advert;
    protected $genre;
    protected $train;
    protected $sign;

    /**
     * Setup the test environment, before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->vip = factory(Vip::class)->create();

        $this->setting = factory(Setting::class)->create();

        $this->permission = factory(Permission::class)->create();

        $this->role = factory(Role::class)->create();

        $this->advert = factory(Advert::class)->create();

        $this->user = factory(User::class)->create([
            'name'     => 'test',
            'password' => bcrypt('test'),
            'gender'   => 1,
            'frozen'   => 0,
        ]);

        $this->frozen_user = factory(User::class)->create([
            'name'     => 'role',
            'password' => bcrypt('role'),
            'frozen'   => 1,

        ]);

        $this->guest = factory(Guest::class)->create([
            'nickname' => 'guest',
            'openid'   => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
            'vip_id'   => $this->vip->id
        ]);

        $this->frozen_guest = factory(Guest::class)->create([
            'nickname' => 'frozen_guest',
            'openid'   => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
        ]);

        $this->genre = factory(Genre::class)->create();

        $this->educational = factory(Educational::class)->create();

        $this->nav = factory(Nav::class)->create([
            'order_type' => 1,
            'type'       => 0,
            'ordered'    => 1,
            'is_hide'    => 0,
        ]);

        $this->teacher = factory(Teacher::class)->create();

        $this->lesson = factory(Lesson::class)->create([
            'type'           => 1,
            'nav_id'         => $this->nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'price'          => 1.00,
            'created_at'     => now(),
        ]);

        $this->lesson_4 = factory(Lesson::class)->create([
            'type'       => 4,
            'nav_id'     => $this->nav->id,
            'out_like'   => 0,
            'teacher_id' => $this->teacher->id,
            'status'     => 3,
            'created_at' => now(),
        ]);

        $this->nav->update(['nav_lesson_ids' => [$this->lesson->id]]);

        $this->down_lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 2,
            'deleted_at'     => null,
        ]);

        $this->del_lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'deleted_at'     => now(),
        ]);

        $this->video = factory(Video::class)->create([
            'fileId'  => '4564972818813772957',
            'name'    => '小溪-480p.mp4',
            'status'  => 2,
            'edk_key' => 'CiBs3r3EbiqHr678aL4/anThjNcJwAhfUW4988xjNwAbWxCO08TAChiaoOvUBCokMWUwYTU0MTMtOGE3YS00OGRjLTg0MTYtYjQ0OGUzMDc4MWMx',
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/c5939c06vodgzp1255334727/21cb8ffc4564972818813772957/vuizU3DKKioA.mp4',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $this->video->id,
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f210.m3u8',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $this->video->id,
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f220.m3u8',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $this->video->id,
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/79bb3eaevodtransgzp1255334727/21cb8ffc4564972818813772957/v.f230.m3u8',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $this->video->id,
        ]);

        $this->section = factory(Section::class)->create([
            'lesson_id' => $this->lesson->id,
            'video_id'  => $this->video->id,
        ]);

        $this->vip_order = factory(Order::class)->create([
            'guest_id'      => $this->guest->id,
            'name'          => $this->faker->name,
            'order_type_id' => $this->vip->id,
            'type'          => 2,
            'status'        => 2
        ]);

        $this->lesson_order = factory(Order::class)->create([
            'guest_id'      => $this->guest->id,
            'name'          => $this->faker->name,
            'order_type_id' => $this->lesson->id,
            'type'          => 1,
            'status'        => 2
        ]);

        $this->label = factory(\App\Models\Label::class)->create();
        $this->message = factory(Message::class)->create([
            'user_id' => $this->user->id,
            'title'   => $this->faker->name
        ]);

        $this->discusse = factory(Discusse::class)->create([
            'guest_id'  => $this->guest->id,
            'pid'       => 0,
            'is_better' => 1,
            'lesson_id' => $this->lesson->id,
            'content'   => $this->faker->name
        ]);

        $this->frozen_user->assignRole($this->role);
        $this->user->assignRole($this->role);
        $this->guest->assignRole($this->role);
        $this->frozen_guest->assignRole($this->role);

        /*模拟观看*/
        $this->guest->lessons()->attach([
            $this->lesson->id => [
                'sections'     => [$this->section->id],
                'last_section' => $this->section->id,
                'is_like'      => 1,
                'is_collect'   => 1,
                'is_pay'       => 1,
                'add_date'     => now(),
                'collect_date' => now(),
            ]
        ]);

        $this->train_nav = factory(Nav::class)->create([
            'type'    => 1,
            'is_hide' => 0,
        ]);

        $this->train = factory(Train::class)->create([
            'status'            => 0,
            'collect_guest_ids' => [],
            'start_at'          => now(),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        $this->train->genres()->attach($this->genre->id);

        $this->train_nav->update(['nav_train_ids' => [$this->train->id]]);

        $this->sign = factory(Sign::class)->create([
            'status'     => 0,
            'inser_type' => 0,
            'train_id'   => $this->train->id
        ]);

    }

    /**
     * Reset the test environment, after each test.
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {

        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // create instance of faker and make it available in all tests
        $this->faker = $app->make(Generator::class);

        return $app;
    }
}
