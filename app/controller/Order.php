<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Order as OrderModel;
use app\model\User as UserModel;
use app\util\Res;
use think\Facade\Db;

class Order extends BaseController
{
    private $result;


    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $user = UserModel::where('id', $post["u_id"])->find();

        Db::startTrain();
        try {

            $user->save([
                "balance" => $user->balance - $post["num"]
            ]);

            $order = new OrderModel([
                "u_id" => $post["u_id"],
                "miner_id" => $post["miner_id"],
                "add_time" => date("Y-m-d H:i:s"),
                "num" => $post["num"]
            ]);

            $res = $order->save();
            if ($res) {
                return $this->result->success("添加数据成功", $res);
            }
            Db::commit();
            return $this->result->error("添加数据失败");

            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return $this->result->error("添加数据失败");
        }
    }

    public function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);

        $list = OrderModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("获取数据成功",$list);
        
    }

    public function getByUid($u_id){
        $list = OrderModel::where('u_id',$u_id)->select();
        return $this->result->success('获取数据成功',$list);
    }

    public function deleteById($id){
        $res = OrderModel::where("id",$id)->delete();
        if($res){
            return $this->result->success('删除数据成功',$res);
        }
        return $this->result->error("删除数据失败");
    }

}
