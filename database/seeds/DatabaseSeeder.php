<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $this->call(AdvertTableSeeder::class);
        $this->call(GerenTableSeeder::class);

        $this->call(NavTableSeeder::class);

        $this->call(RolesTableSeeder::class);
        $this->call(GuestSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->call(LabelSeeder::class);
        $this->call(GuestLabelSeeder::class);

        $this->call(MessageTableSeeder::class);

        $this->call(MenusSeeder::class);

        $this->call(SettingTableSeeder::class);
        $this->call(TeacherTableSeeder::class);

        $this->call(VipTableSeeder::class);
        $this->call(EducationalTableSeeder::class);

        $this->call(LessonTableSeeder::class);
        $this->call(VideoTableSeeder::class);
        $this->call(VideoUrlTableSeeder::class);
        $this->call(SectionTableSeeder::class);

        $this->call(DiscusseSeeder::class);
        $this->call(OrderTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);

        $this->call(GuestLessonTableSeeder::class);
        $this->call(GenreLessonTableSeeder::class);
        $this->call(GuestMessageTableSeeder::class);

        $this->call(TrainTableSeeder::class);
        $this->call(SignTableSeeder::class);
        $this->call(GenreTrainTableSeeder::class);

        $this->call(OauthClientTableSeeder::class);

    }
}
