<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Feed as FeedModel;
use app\util\Res;

class Feed extends BaseController{
    protected $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){
        $post = $request->post();
        
        $feed = new FeedModel([
            "u_id"=>$post["u_id"],
            "title"=>$post["title"],
            "content"=>$post["content"]
        ]);
    }

    function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $keyword = $request->param("keyword");

        $list = FeedModel::where("title","like","%{$keyword}%")->whereOr("content","like","%{$keyword}%")
            ->paginate([
                "page"=>$page,
                "list_rows"=>$pageSize
            ]);

        return $this->result->success("获取数据成功",$list);
    }

    function deleteById($id){
        $res = FeedModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }
}