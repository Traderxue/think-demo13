<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Admin as AdminModel;
use app\util\Res;
use app\controller\Upload;

class Admin extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $admin = new AdminModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT)
        ]);

        $res = $admin->save();
        if ($res) {
            return $this->result->success("添加成功", $admin);
        }
        return $this->result->error("添加失败");
    }

    public function edit(Request $request)
    {
        $post = $request->post();
        $admin = AdminModel::where("id", $post["id"])->find();

        $upload = new Upload();

        $url = $upload->index();

        $res = $admin->save([
            "nickname" => $post["nickname"],
            "avator" => $url
        ]);

        if ($res) {
            return $this->result->success("编辑数据成功", $res);
        }
        return $this->result->error("编辑数据失败");
    }

    public function login(Request $request){
        $post = $request->post();
        $admin = AdminModel::where("username",$post["username"])->find();

        if($admin==null){
            return $this->result->error("用户不存在");
        }
        if(password_verify($post["password"],$admin->password)){
            return $this->result->success("登录成功",$admin);
        }
        return $this->result->error("登录失败");
    }

    public function deleteById($id)
    {
        $res = AdminModel::where("id", $id)->delete();
        if (!$res) {
            return $this->result->error("删除数据失败");
        }
        return $this->result->success('删除数据成功', $res);
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);

        $list = AdminModel::paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }


}