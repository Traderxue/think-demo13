<?php
namespace app\controller;

use think\Request;
use app\model\User as UserModel;
use app\BaseController;
use app\util\Res;
use app\controller\Upload;

class User extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function register(Request $request)
    {
        $post = $request->post();

        $u = UserModel::where("username", $post["username"])->find();
        if ($u != null) {
            return $this->result->error("用户已存在");
        }

        $user = new UserModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT),
            "email" => $post["email"],
            "add_time" => date("Y-m-d H:i:s"),
            "ip" => $request->ip()
        ]);
        $res = $user->save();
        if ($res) {
            return $this->result->success("注册成功", $post);
        }
        return $this->result->error("注册失败");
    }

    public function login(Request $request)
    {
        $post = $request->post();
        $user = UserModel::where("username", $post["username"])->where("disabled", 0)->find();
        if (!$user) {
            return $this->result->error("用户不存在或被冻结");
        }
        if (password_verify($post["password"], $user->password)) {
            return $this->result->success("登录成功", $user);
        }
        return $this->result->error("登录失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $keyword = $request->param("keyword");

        $list = UserModel::where("username", "like", "%{$keyword}%")->whereOr("nickname", "like", "%{$keyword}%")
            ->paginate([
                "page" => $page,
                "list_rows" => $pageSize
            ]);

        return $this->result->success("获取数据成功", $list);
    }

    public function edit(Request $request)
    {
        $post = $request->post();
        $user = UserModel::where("id", $post["id"])->find();

        $upload = new Upload();
        $url = $upload->index();

        $res = $user->save([
            "email" => $post["email"],
            "nickname" => $post["nickname"],
            "avatar" => $url
        ]);

        if (!$res) {
            return $this->result->error("编辑信息失败");
        }
        return $this->result->success("编辑信息成功", $user);
    }

    public function getById($id)
    {
        $user = UserModel::where("id", $id)->find();
        return $this->result->success("获取数据成功", $user);
    }

    public function disabled($id){
        $user = UserModel::where("id",$id)->find();
        $res = $user->save(["disabled"=>1]);
        if($res){
            return $this->result->success("禁用成功",$user);    
        }        
        return $this->result->error("禁用失败");
    }

    public function enabled($id){
        $user = UserModel::where("id",$id)->find();
        $res = $user->save(["disabled"=>0]);
        if($res){
            return $this->result->success("启用成功",$res);
        }
        return $this->result->error("启用失败");
    }
}