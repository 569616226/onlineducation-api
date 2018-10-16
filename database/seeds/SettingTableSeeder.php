<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'index_type' => 1,
                'index_count' => 4,
                'vip_send_seting' => 5,
                'sign_start_time' => 5,
                'top_lesson_ids' => '[]',
                'top_train_ids' => '[]',
                'wechat_sub' => '&lt;div&gt;终于等到你!&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;在这里,我们会定期向你推送时下最热门的供应链资讯及课程。&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;免费课程：&lt;a href=\&quot;https://mobile.edu.elinkport.com/#/details/1\&quot;&gt;新政频出，企业应如何降低物流成本&lt;/a&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;免费课程：&lt;a href=\&quot;http://mobile.edu.elinkport.com/#/details/4\&quot;&gt;海关税收企业&ldquo;自报自缴&rdquo;&lt;/a&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;免费课程：&lt;a href=\&quot;http://mobile.edu.elinkport.com/#/details/14\&quot;&gt;海关总署关于规范转关运输业务的公告&lt;/a&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;更多精彩课程请点击下方\&quot;全部课程\&quot;按钮????&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
