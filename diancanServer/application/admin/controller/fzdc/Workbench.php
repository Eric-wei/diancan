<?php

namespace app\admin\controller\fzdc;


use app\admin\model\fzdc\goods\Goods;
use app\admin\model\fzdc\goods\SkuProduct;
use app\admin\model\fzdc\order\Detail;
use app\admin\model\fzdc\order\Order;
use app\admin\model\fzdc\store\Sear;
use app\admin\model\fzdc\store\Store;
use app\admin\model\User;
use app\common\controller\Backend;
use think\Config;

/**
 * 控制台
 *
 * @icon   fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Workbench extends Backend
{

    public function index()
    {
        $store = Store::where(["state"=>1])->select();
        $seat = Sear::select();

        //获取默认一个店铺
        $default_store = Store::where(["state"=>1])->find();

        $this->view->assign("seat", $seat);
        $this->view->assign("store", $store);
        $this->view->assign("default_store", $default_store);
        return $this->view->fetch();
    }

    public function recentData(){
        $params = $this->request->param();
        //查询正在使用的桌号
//        $seat = Sear::where(["store_id"=>$params["shop_id"],"use_status"=>2])->column('id');
//        //数据库中存储的桌号是1,2,3..这种类型,我需要查询包含$sear数组的订单
//        $ids = [];
//        foreach ($seat as $v){
//            $ids = array_merge($ids,Order::where(["store_id"=>$params["shop_id"]])->whereIn("status",[2,3,6,7,8])->where("FIND_IN_SET('{$v}', seat_id)")->column('id'));
//        }
        $seat = Order::where(["store_id"=>$params["shop_id"]])->order("id desc")->limit(8)->select()->each(function($item){
            $item->goods_list = Detail::where(["order_id"=>$item->id])->select();
            if ($item->pay_time){
                $item->pay_time = date("Y-m-d",$item->pay_time);
            }else{
                $item->createtime = date("Y-m-d",$item->createtime);
                $item->pay_time = "";
            }
            if ($item->order_type==3){
                $item->enjoy_type = "meat";
                $item->order_type_texts = "堂食";
                $item->desk_number = implode(',',Sear::whereIn("id",$item["seat_id"])->column('title'));
            }elseif($item->order_type==4){
                $item->enjoy_type = "pack";
                $item->order_type_texts = "预约";
            }elseif ($item->order_type==1){
                $item->order_type_texts = "自取";
            }elseif ($item->order_type==2){
                $item->order_type_texts = "外卖";
            }
        });
        return json(["code"=>1,"data"=>["orderList"=>$seat],"msg"=>""]);
    }

    public function shopData(){
        $params = $this->request->param();
        if (isset($params['group']) && $params['group']!='all'){
            $seat = Sear::where(["store_id"=>$params["shop_id"],"use_status"=>$params['group']])->select()->each(function($item){
                if ($item->use_status==1){
                    $item->use_status_text = "空闲中";
                }elseif ($item->use_status==2){
                    $item->use_status_text = "使用中";
                }else{
                    $item->use_status_text = "打扫中";
                }
            });
        }else{
            $seat = Sear::where(["store_id"=>$params["shop_id"]])->select()->each(function($item){
                if ($item->use_status==1){
                    $item->use_status_text = "空闲中";
                }elseif ($item->use_status==2){
                    $item->use_status_text = "使用中";
                }else{
                    $item->use_status_text = "打扫中";
                }
            });
        }

        return json(["code"=>1,"data"=>['deskList'=>$seat],"msg"=>""]);
    }

    public function changeDeskStatus(){
        $params = $this->request->param();
        $seat = Sear::get($params['state']);
        $seat->use_status = 1;
        $seat->save();
        if ($seat['use_status'] == 1){
            //判断有没有员工点餐的订单
//            $order = Order::where("FIND_IN_SET('{$seat['id']}', seat_id)")->where(['dc_type' => 2, 'status' => 1])->find();
//            if ($order){
                Order::where("FIND_IN_SET('{$seat['id']}', seat_id)")->whereNotIn('status', [3,4,5,9,])->update(['status' => 3,'pay_type'=>3]);
//            }

        }
        return json(["code"=>1,"data"=>[],"msg"=>"修改成功"]);

    }

}
