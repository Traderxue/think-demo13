<?php
namespace app\controller;
use app\BaseController;
use think\facade\Request;

class Upload extends BaseController{
    public function upload(Request $request){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 上传到本地服务器
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $file);

        return Request::domain().'/storage/'.$savename;
    }
}