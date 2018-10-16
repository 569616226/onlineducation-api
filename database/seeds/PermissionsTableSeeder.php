<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'home', 'guard_name' => 'api', 'display_name' => '主页']);

        Permission::create(['name' => 'lesson', 'guard_name' => 'api', 'display_name' => '课程管理']);
        Permission::create(['name' => 'up_lesson', 'guard_name' => 'api', 'display_name' => '上架课程']);
        Permission::create(['name' => 'down_lesson', 'guard_name' => 'api', 'display_name' => '下架课程']);
        Permission::create(['name' => 'create_lesson', 'guard_name' => 'api', 'display_name' => '新建课程']);
        Permission::create(['name' => 'lesson_student', 'guard_name' => 'api', 'display_name' => '学员列表']);
        Permission::create(['name' => 'update_lesson', 'guard_name' => 'api', 'display_name' => '编辑课程']);
        Permission::create(['name' => 'del_lesson', 'guard_name' => 'api', 'display_name' => '删除课程']);
        Permission::create(['name' => 'set_out_likes', 'guard_name' => 'api', 'display_name' => '编辑广告点赞数据']);
        Permission::create(['name' => 'set_out_play_times', 'guard_name' => 'api', 'display_name' => '编辑广告播放数据']);
        Permission::create(['name' => 'create_section', 'guard_name' => 'api', 'display_name' => '新建章节']);
        Permission::create(['name' => 'update_section', 'guard_name' => 'api', 'display_name' => '编辑章节']);
        Permission::create(['name' => 'del_section', 'guard_name' => 'api', 'display_name' => '删除章节']);
        Permission::create(['name' => 'lesson_preview', 'guard_name' => 'api', 'display_name' => '预览课程']);

        Permission::create(['name' => 'video', 'guard_name' => 'api', 'display_name' => '视频库']);
        Permission::create(['name' => 'upload_video', 'guard_name' => 'api', 'display_name' => '上传视频']);
        Permission::create(['name' => 'update_video', 'guard_name' => 'api', 'display_name' => '编辑视频']);
        Permission::create(['name' => 'del_video', 'guard_name' => 'api', 'display_name' => '删除视频']);

        Permission::create(['name' => 'nav', 'guard_name' => 'api', 'display_name' => '栏目管理']);
        Permission::create(['name' => 'create_nav', 'guard_name' => 'api', 'display_name' => '创建栏目']);
        Permission::create(['name' => 'update_nav', 'guard_name' => 'api', 'display_name' => '编辑栏目']);
        Permission::create(['name' => 'del_nav', 'guard_name' => 'api', 'display_name' => '删除栏目']);
        Permission::create(['name' => 'change_nav_order', 'guard_name' => 'api', 'display_name' => '栏目排序']);

        Permission::create(['name' => 'genre', 'guard_name' => 'api', 'display_name' => '标签管理']);
        Permission::create(['name' => 'create_genre', 'guard_name' => 'api', 'display_name' => '创建标签']);
        Permission::create(['name' => 'update_genre', 'guard_name' => 'api', 'display_name' => '编辑标签']);
        Permission::create(['name' => 'del_genre', 'guard_name' => 'api', 'display_name' => '删除标签']);


        Permission::create(['name' => 'discusse', 'guard_name' => 'api', 'display_name' => '评论管理']);
        Permission::create(['name' => 'create_discusse', 'guard_name' => 'api', 'display_name' => '回复评论']);
        Permission::create(['name' => 'del_discusse', 'guard_name' => 'api', 'display_name' => '删除评论']);
        Permission::create(['name' => 'set_discusse_better', 'guard_name' => 'api', 'display_name' => '精选评论']);
        Permission::create(['name' => 'unset_discusse_better', 'guard_name' => 'api', 'display_name' => '取精评论']);

        Permission::create(['name' => 'message', 'guard_name' => 'api', 'display_name' => '消息推送管理']);
        Permission::create(['name' => 'system_message', 'guard_name' => 'api', 'display_name' => '系统消息管理']);
        Permission::create(['name' => 'send_message', 'guard_name' => 'api', 'display_name' => '发送消息']);

        Permission::create(['name' => 'advert', 'guard_name' => 'api', 'display_name' => '广告管理']);
        Permission::create(['name' => 'create_advert', 'guard_name' => 'api', 'display_name' => '新建广告']);
        Permission::create(['name' => 'update_advert', 'guard_name' => 'api', 'display_name' => '编辑广告']);
        Permission::create(['name' => 'del_advert', 'guard_name' => 'api', 'display_name' => '删除广告']);

        Permission::create(['name' => 'vip', 'guard_name' => 'api', 'display_name' => 'vip会员管理']);
        Permission::create(['name' => 'create_vip', 'guard_name' => 'api', 'display_name' => '新建vip']);
        Permission::create(['name' => 'update_vip', 'guard_name' => 'api', 'display_name' => '编辑vip']);
        Permission::create(['name' => 'del_vip', 'guard_name' => 'api', 'display_name' => '删除vip']);
        Permission::create(['name' => 'up_vip', 'guard_name' => 'api', 'display_name' => '上架vip']);
        Permission::create(['name' => 'down_vip', 'guard_name' => 'api', 'display_name' => '下架vip']);
        Permission::create(['name' => 'set_vip_up_time', 'guard_name' => 'api', 'display_name' => '设置vip上架时间']);
        Permission::create(['name' => 'set_vip_down_time', 'guard_name' => 'api', 'display_name' => '设置vip下架时间']);

        Permission::create(['name' => 'teacher', 'guard_name' => 'api', 'display_name' => '讲师管理']);
        Permission::create(['name' => 'create_teacher', 'guard_name' => 'api', 'display_name' => '新建讲师']);
        Permission::create(['name' => 'update_teacher', 'guard_name' => 'api', 'display_name' => '编辑讲师']);
        Permission::create(['name' => 'del_teacher', 'guard_name' => 'api', 'display_name' => '删除讲师']);

        Permission::create(['name' => 'set_index_type', 'guard_name' => 'api', 'display_name' => '主页信息管理']);
        Permission::create(['name' => 'set_wechat_sub', 'guard_name' => 'api', 'display_name' => '公众号管理']);
        Permission::create(['name' => 'set_vip_send_time', 'guard_name' => 'api', 'display_name' => '会员到期提醒时间设置']);


        Permission::create(['name' => 'train', 'guard_name' => 'api', 'display_name' => '会销管理']);
        Permission::create(['name' => 'create_train', 'guard_name' => 'api', 'display_name' => '新建会销']);
        Permission::create(['name' => 'update_train', 'guard_name' => 'api', 'display_name' => '编辑会销']);
        Permission::create(['name' => 'del_train', 'guard_name' => 'api', 'display_name' => '删除会销']);
        Permission::create(['name' => 'sign', 'guard_name' => 'api', 'display_name' => '签到管理']);
        Permission::create(['name' => 'update_sign', 'guard_name' => 'api', 'display_name' => '编辑签到记录']);
        Permission::create(['name' => 'del_sign', 'guard_name' => 'api', 'display_name' => '删除签到记录']);

        Permission::create(['name' => 'educational', 'guard_name' => 'api', 'display_name' => '教务设置']);
        Permission::create(['name' => 'create_educational', 'guard_name' => 'api', 'display_name' => '新建教务模板']);
        Permission::create(['name' => 'update_educational', 'guard_name' => 'api', 'display_name' => '编辑教务模板']);
        Permission::create(['name' => 'del_educational', 'guard_name' => 'api', 'display_name' => '删除教务模板']);

        Permission::create(['name' => 'guest', 'guard_name' => 'api', 'display_name' => '用户管理']);
        Permission::create(['name' => 'update_guest', 'guard_name' => 'api', 'display_name' => '编辑用户']);
        Permission::create(['name' => 'add_label', 'guard_name' => 'api', 'display_name' => '设置标签']);

        Permission::create(['name' => 'label', 'guard_name' => 'api', 'display_name' => '标签管理']);
        Permission::create(['name' => 'create_label', 'guard_name' => 'api', 'display_name' => '新建标签']);
        Permission::create(['name' => 'update_label', 'guard_name' => 'api', 'display_name' => '编辑标签']);
        Permission::create(['name' => 'del_label', 'guard_name' => 'api', 'display_name' => '删除标签']);

        Permission::create(['name' => 'order', 'guard_name' => 'api', 'display_name' => '订单管理']);
        Permission::create(['name' => 'get_order', 'guard_name' => 'api', 'display_name' => '订单详情']);
        Permission::create(['name' => 'del_order', 'guard_name' => 'api', 'display_name' => '删除订单']);

        Permission::create(['name' => 'permission', 'guard_name' => 'api', 'display_name' => '权限管理']);
        Permission::create(['name' => 'create_permission', 'guard_name' => 'api', 'display_name' => '新建权限']);
        Permission::create(['name' => 'update_permission', 'guard_name' => 'api', 'display_name' => '编辑权限']);
        Permission::create(['name' => 'del_permission', 'guard_name' => 'api', 'display_name' => '删除权限']);

        Permission::create(['name' => 'role', 'guard_name' => 'api', 'display_name' => '角色管理']);
        Permission::create(['name' => 'create_role', 'guard_name' => 'api', 'display_name' => '新建角色']);
        Permission::create(['name' => 'update_role', 'guard_name' => 'api', 'display_name' => '编辑角色']);
        Permission::create(['name' => 'del_role', 'guard_name' => 'api', 'display_name' => '删除角色']);

        Permission::create(['name' => 'user', 'guard_name' => 'api', 'display_name' => '账号管理']);
        Permission::create(['name' => 'create_user', 'guard_name' => 'api', 'display_name' => '新建账号']);
        Permission::create(['name' => 'update_user', 'guard_name' => 'api', 'display_name' => '编辑账号']);
        Permission::create(['name' => 'del_user', 'guard_name' => 'api', 'display_name' => '删除账号']);
        Permission::create(['name' => 'frozen_user', 'guard_name' => 'api', 'display_name' => '冻结账号']);
        Permission::create(['name' => 'refrozen_user', 'guard_name' => 'api', 'display_name' => '解冻账号']);

        Permission::create(['name' => 'set_sign_start_time', 'guard_name' => 'api', 'display_name' => '通用设置']);

        Permission::create(['name' => 'log', 'guard_name' => 'api', 'display_name' => '系统日志']);

        $super_role = \Spatie\Permission\Models\Role::findByName('super_admin', 'api');
        $super_role->syncPermissions(Permission::getPermissions());

    }
}
