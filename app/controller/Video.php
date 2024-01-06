<?php
namespace app\controller;

use app\BaseController;
use app\model\Video as VideoModel;
use think\Request;
use app\util\Res;
use app\controller\Upload;

class Video extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();

        $upload = new Upload();

        $url = $upload->index();

        $video = new VideoModel([
            "video_url"=>$post["video"],
            "img_url"=>$url
        ]);

        $res = $video->save();
        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function deleteById($id){
        $res = VideoModel::where("id",$id)->delete();
        if($res){
            return $this->result->success('删除成功',$res);
        }
        return $this->result->error('删除数据失败');
    }

    public function getList(Request $request){
        $list = VideoModel::select();
        return $this->result->success('获取数据成功',$list);
    }
}