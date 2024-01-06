<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Swipe as SwipeModel;
use app\util\Res;
use app\controller\Upload;

class Swipe extends BaseController{
    protected $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){

        $upload = new Upload();

        $url = $upload->index();

        $swipe = new SwipeModel([
            "url"=>$url
        ]);
        $res = $swipe->save();
        if($res){
            return $this->result->success('添加成功',$url);
        }
        return $this->result->error("添加数据失败");
    }

    function deleteById($id){
        $res = SwipeModel::destroy($id);

        if($res){
            return $this->result->success("删除成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

    function getList(Request $request){
        $list =SwipeModel::select();
        return $this->result->success("获取数据成功",$list);
    }
    
}