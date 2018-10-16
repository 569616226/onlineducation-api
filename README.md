# 东华商学 V1.02

## 环境要求
- PHP >= 7.0
- MySQL >= 5.6
- Node.js


## 开始安装

从 [http://119.23.141.231:10080/edu/onlineducation-api](ssh://git@119.23.141.231:10022/edu/onlineducation-api.git) 克隆本项目

```shell
# from http://119.23.141.231:10080/edu/onlineducation-api clone this project.
git clone ssh://git@119.23.141.231:10022/edu/onlineducation-api.git
```
composer
```shell
cd onlineducation-api
composer install
配置
```php
# 修改.env里面的数据库信息
cp .env.example .env
```
生成密钥
```php
php artisan key:generate
```
创建数据库并填充测试数据
```php

php artisan migrate --seed

如果是重置数据库
php artisan migrate:refresh --seed
```

corn 定时任务设置
```
Add 'Liebig\Cron\CronServiceProvider' to your 'providers' array in the /path/to/laravel/app/config/app.php
php artisan migrate --package="liebig/cron"
php artisan config:publish liebig/cron

php artisan cron:keygen

add to liebigCron.php

// Cron application key for securing the integrated Cron run route - if the value is empty, the route is disabled
'cronKey' => '1PBgabAXdoLTy3JDyi0xRpTR2qNrkkQy'

添加配置到.env文件
PERSONAL_CLIENT_ID=
PERSONAL_CLIENT_SECRET=

PASSPORT_CLIENT_ID=
PASSPORT_CLIENT_SECRET=
```


