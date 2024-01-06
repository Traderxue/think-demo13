<?php
namespace app\controller;

use think\Request;
use app\model\Miner as MinerModel;
use app\BaseController;
use app\util\Res;
use app\controller\Upload;

class Miner extends BaseController
{
    private $result;

    function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    function add(Request $requst)
    {
        $post = $requst->post();

        $upload = new Upload();

        $url = $upload->index();

        $miner = new MinerModel([
            "name" => $post["name"],
            "price" => $post["price"],
            "cycle" => $post["cycle"],
            "avator" => $url,
            "description" => $post["description"]
        ]);
        $res = $miner->save();
        if ($res) {
            return $this->result->success("添加成功", $res);
        }
        return $this->result->error("添加失败");
    }

    function edit(Request $request)
    {
        $post = $request->post();
        $miner = MinerModel::where("id", $post["id"])->find();

        $upload = new Upload();

        $url = $upload->index();

        $res = $miner->save([
            "name" => $post["name"],
            "price" => $post["price"],
            "cycle" => $post["cycle"],
            "avatar" => $url,
            "description" => $post["description"],
        ]);

        if ($res) {
            return $this->result->success("编辑成功", $miner);
        }
        return $this->result->error('编辑失败');
    }

    function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param("pageSize", 10);
        $keyword = $request->param("keyword");

        $list = MinerModel::where("name", "like", "%{$keyword}%")->whereOr("description", "like", "%{$keyword}%")
            ->paginate([
                "page" => $page,
                "list_rows" => $pageSize
            ]);
        return $this->result->success("获取数据成功", $list);
    }

    function getAll(Request $request)
    {
        $keyword = $request->param("kwyword");
        $list = MinerModel::where("name", "like", "%{$keyword}%")->whereOr("description", "like", "%{$keyword}%")->select();
        return $this->result->success("获取数据成功", $list);
    }

    function disabled($id)
    {
        $miner = MinerModel::where("id", $id)->find();
        $res = $miner->save(["disabled" => 1]);
        if ($res) {
            return $this->result->success('下架成功', $res);
        }
        return $this->result->error('下架失败');
    }

    function deleteById($id){
        $res = MinerModel::where("id",$id)->delete();

        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

}