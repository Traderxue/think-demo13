<?php
namespace app\controller;

use think\Request;
use app\model\Notice as NoticeModel;
use app\util\Res;
use app\BaseController;

class Notcie extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();
        $notice = new NoticeModel([
            "add_time" =>date("Y-m-d H:i:s"),
            "title"=>$post["title"],
            "content"=>$post["content"],
            "author"=>$post["author"]
        ]);
        $res = $notice->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request){
        $post = $request->post();
        $notice = NoticeModel::where("id",$post["id"])->find();

        $res = $notice->save([
            "title"=>$post["title"],
            "content"=>$post["content"],
            "author"=>$post["author"]
        ]);

        if($res){
            return $this->result->success("编辑数据成功",$notice);
        }

        return $this->result->error("编辑数据失败");
    }

    public function deleteById($id){
        $res = NoticeModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("获取数据成功",$res);
        }
        return $this->result->error("获取数据失败");
    }

    public function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);
        $keyword = $request->param("keyword");

        $list = NoticeModel::where("title","like","%{$keyword}%")->whereOr("content","like","%{$keyword}%")
            ->paginate([
                "page"=>$page,
                "list_rows"=>$pageSize
            ]);
        return $this->result->success("获取数据成功",$list);
        
    }
}