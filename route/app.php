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
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');

Route::post("/upload","upload/upload");


Route::group("/user",function(){

    Route::post("/register","/user/register");

    Route::post("/login","/user/login");

    Route::post('/edit',"user/edit");

    Route::get("/page","user/page");

    Route::get("/get","user/getById");

    Route::put("/disabled/:id","user/disabled");        //禁用

    Route::put("/enabled/:id","user/enabled");          //启用

});

Route::group("/bill",function(){

    Route::post("/add","bill/add");

    Route::get("/page","bill/page");

    Route::get("/get/:u_id","bill/getByUid");

    Route::post("/verify/:id","bill/verify");
});

Route::feed("/feed",function(){

    Route::post("/add","feed/add");

    Route::get("/page","feed/page");

    Route::delete("/delete/:id","feed/deleteById");

});

Route::group("/miner",function(){

    Route::post("/add","miner/add");

    Route::post("/edit","miner/edit");

    Route::get("/page","miner/page");

    Route::get("/getall","miner/getAll");

    Route::delete("/delete/:id","miner/deleteById");

    Route::post("/disabled/:id","miner/disabled");

});

Route::group("/order",function(){

    Route::post("/add","order/add");

    Route::get("/page","order/page");

    Route::get("/get","order/getByUid");

    Route::delete("/delete","order/deleteById");

});
