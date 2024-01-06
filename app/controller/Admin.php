<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Admin as AdminModel;
use app\util\Res;
use app\controller\Upload;
use Firebase\JWT\JWT;

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
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" =>  time(),  // token 的生效时间
                "exp" => time() + 3600,  // token 的过期时间
                "data" => [
                    // 包含的用户信息等数据
                ]
            );
             // 使用密钥进行签名
            $token = JWT::encode($payload, $secretKey,'HS256');
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