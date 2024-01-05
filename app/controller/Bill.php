<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\util\Res;
use app\model\Bill as BillModel;

class Bill extends BaseController
{
    private $result;

    private function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();
        $bill = new BillModel([
            "u_id" => $post["u_id"],
            "add_time" => date("Y-m-d H:i:s"),
            "operate" => $post["operate"],
            "num" => $post["num"],
        ]);

        $res = $bill->save();
        if ($res) {
            return $this->result->success("添加账单成功", $bill);
        }
        return $this->result->error("添加账单失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $keyword = $request->param("pageSize");

        $list = BillModel::where("u_id", $keyword)->whereOr("operate", $keyword)
            ->paginate([
                "page" => $page,
                "list_rows" => $pageSize
            ]);
        return $this->result->success("获取数据成功", $list);
    }

    public function getByUid($u_id)
    {
        $list = BillModel::where('u_id', $u_id)->select();
        return $this->result->success("获取数据成功", $list);
    }
}