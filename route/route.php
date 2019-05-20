<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule('/about','/index/Index/about');


Route::rule('/', '/index/Index/index'); // 首页访问路由------------------------
Route::rule('/about', '/index/Index/about'); // 首页访问路由-
Route::rule('/contact', '/index/Index/contact'); // 首页访问路由
Route::rule('/product', '/index/Index/product'); // 首页访问路由

