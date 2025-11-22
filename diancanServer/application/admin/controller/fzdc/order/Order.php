<?php

namespace app\admin\controller\fzdc\order;

use addons\epay\library\Service;
use app\admin\controller\fzdc\Base;
use app\admin\library\Auth;
use app\admin\model\fzdc\goods\SkuProduct;
use app\admin\model\fzdc\message\Push;
use app\admin\model\fzdc\order\Detail;
use app\admin\model\fzdc\prin\Prin;
use app\admin\model\fzdc\store\Refund;
use app\admin\model\fzdc\store\Revenue;
use app\admin\model\fzdc\store\Sear;
use app\admin\model\fzdc\store\User;
use app\admin\model\fzdc\user\Address;
use app\admin\model\fzdc\Userdetail;
use app\common\controller\Backend;
use app\admin\model\fzdc\store\Store;
use think\Queue;
use Yansongda\Pay\Pay;
use think\Db;
use think\Log;
use app\admin\model\fzdc\coupon\Coupon;
use app\admin\model\fzdc\coupon\Receive;

/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Order extends Base
{

    /**
     * Order模型对象
     * @var \app\admin\model\fzdc\order\Order
     */
    protected $model = null;
    protected $modelstore = null;
    protected $modelcoupon = null;
    protected $modeladdress = null;
    protected $searchFields = 'order_no';
    protected $dataLimitField = 'store_id';
    protected $dataLimitFieldAutoFill = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\order\Order;
        $this->modelstore = new \app\admin\model\fzdc\store\Store;
        $this->modelcoupon = new \app\admin\model\fzdc\coupon\Coupon;
        $this->modeladdress = new \app\admin\model\fzdc\user\Address;
        $this->view->assign("orderTypeList", $this->model->getOrderTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("couponStateList", $this->model->getCouponStateList());
    }

    protected function getDataLimitAdminIds()
    {
        $auth = Auth::instance();
        if ($this->addonsConfig['isStoreAllocate'] == 1 && !$auth->isSuperAdmin()) {
            return $this->authStoreIds;
        }
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $store_id =$this->request->param("store_id",0);
            $seat_id =$this->request->param("seat_id",0);
            $newWhere = [];
            $newwhere2 = '1=1';
            if ($store_id){
                $newWhere['store_id'] = $store_id;
            }
            if ($seat_id){
                $newwhere2 = "FIND_IN_SET('".$seat_id."',seat_id)";
            }
            $list = $this->model
                ->with(['user', 'coupon', 'store'])
                ->where($where)
                ->where($newwhere2)
                ->where($newWhere)
                ->order($sort, $order)
                ->paginate($limit)
                ->each(function ($item) {

                });

            foreach ($list as $row) {
                $row->visible(['id', 'order_no', 'order_type', 'user_id', 'score_id', 'goods_id', 'status', 'order_amount_total','p_type', 'type', 'pay_type', 'pay_time', 'createtime', 'updatetime']);
                $row->visible(['user']);
                $row->getRelation('user')->visible(['username']);
                $row->visible(['store']);
                $row->getRelation('store')->visible(['title']);
                $row->visible(['coupon']);
                $row->getRelation('coupon')->visible(['title']);
                if ($row->dc_type == 2){
                    $row->user->username = "-";
                }
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    public function detail($ids)
    {
        $data = $this->model->where("id", $ids)->find();
        if ($data['dc_type']==1){
            $user = \app\admin\model\fzdc\user\User::get($data['user_id']);
            if ($user){
                $data['user_name'] = $user->username;
            }else{
                $data['user_name'] = "-";
            }
        }else{
            $data['user_name'] = "-";
        }
        if ($data['coupon_id']>0){
            $coupon = Coupon::get($data['coupon_id']);
            if ($coupon){
                $data['used_amount'] = $coupon->used_amount;
                $data['coupon_title'] = $coupon->title;
            }else{
                $data['used_amount'] = 0;
                $data['coupon_title'] = '-';
            }
        }
        if ($data['pay_type']==1){
            $data['pay_type'] = "微信支付";
        }elseif ($data['pay_type']==2){
            $data['pay_type'] = "钱包支付";
        }elseif ($data['pay_type']==3){
            $data['pay_type'] = "现金支付";
        }else{
            $data['pay_type'] = "暂无";
        }
        if ($data['order_type']==3||$data['order_type']==4){
            $seat_info = Sear::whereIn("id", explode(",", $data['seat_id']))->column('title');
            $data['seat_info'] = implode(",", $seat_info);
        }
        if ($data['order_type']==2){
            $addressInfo = json_decode($data['address_info'], true);
            if ($addressInfo){
                $data['addressInfo'] = $addressInfo;
            }else{
                $data['addressInfo']['title'] = "-";
                $data['addressInfo']['mobile'] = "-";
                $data['addressInfo']['address'] = "-";
                $data['addressInfo']['remark'] = "-";
            }
            $data['address_json'] = $data['addressInfo'];
        }
        //打扫完成按钮显示
        $is_xs = 0;
        if ($data['status']==3 && $data['order_type'] ==3){
            $seat = Sear::whereIn("id", $data['seat_id'])->find();
            if ($seat['use_status']==2){
                $is_xs = 1;
            }
        }
        $data['is_xs'] = $is_xs;
        $list = Detail::with(['store', 'goods','user'])
            ->where(['detail.order_id' => $ids])
            ->select();
        foreach ($list as $row) {
            if ($row->numbers==1){
                $row->numbers = "第一次购买";
            }else{
                $row->numbers = "第". $row->numbers. "次加菜";
            }
            $row->getRelation('store')->visible(['title']);
            $row->getRelation('goods')->visible(['title', 'image']);
        }
        $this->view->assign("detail", $list);
        $this->view->assign("data", $data);
        return $this->view->fetch();
    }

    public function change(){
        $params = $this->request->param();
        $order = $this->model->where(["id" => $params['order_id']])->find();
        if ($this->request->isAjax()) {
            $row = $this->request->post("row/a");
            //判断此座号是否在使用
            $seat_id = $row['seat_id'];
            $use_status = Sear::where("id", $seat_id)->value("use_status");
            if ($use_status == 2){
                $this->error("此座号已被使用");
            }
            if ($seat_id == $order->seat_id){
                $this->error("此座号未变更");
            }
            $this->model->where("id", $params['order_id'])->update(['seat_id' => $seat_id]);
            Sear::where("id", $seat_id)->update(["use_status" => 2]);
            //原来的座号释放
            $old_seat_id = $order->seat_id;
            if ($old_seat_id){
                Sear::where("id", $old_seat_id)->update(["use_status" => 1]);
            }
            $this->success("修改成功");
        }
        $this->view->assign("data", $order);
        return $this->view->fetch();
    }

    public function update_status()
    {
        $params = $this->request->param();
        $status = $params['status'];
        $order_id = $params['order_id'];
        $order = $this->model->where(["id" => $order_id])->find();
        if ($status == 666){
            Sear::whereIn("id", explode(",", $order->seat_id))->update(["use_status" => 1]);
            $this->success("操作成功");
        }
        if ($status == 7) {
            //接单
            $order->business_status = 2;
            $order->save();
            $store = Store::where(["id" => $order['store_id']])->find();

            $order_detail = Detail::where(["order_id" => $order['id']])->select();
            $cp = "";
            if (!$order_detail){
                $cp = $store['title'];
            }else{
                foreach ($order_detail as $k => $v) {
                    if ($k == count($order_detail)-1){
                        $cp .= $v['goods_name'];
                    }else{
                        $cp .= $v['goods_name'].",";
                    }
                }
            }

            //判断用户是否订阅消息推送
            $userInfo = Userdetail::get(["user_id" => $order->user_id]);
            Log::info("推送订单状态消息开始");
            if ($userInfo['subscribe'] == 1) {
                Log::info("进入订单状态推送1");
                $message = Push::where(["type" => 3])->find();
                if ($message) {
                    Log::info("进入订单状态推送2");
                    if (!empty($message['multiplejson'])) {
                        $json = json_decode($message['multiplejson'], true);
                        //参数名对应订阅消息详细内容参数 （以下为测试）
                        $data = [];
                        foreach ($json as $jv) {
                            switch ($jv['title']) {
                                case '2':
                                    $data['data'][$jv['keyword']]['value'] = $order->order_no;
                                    break;
                                case '9':
                                    $data['data'][$jv['keyword']]['value'] = $cp;
                                    break;
                                case '8':
                                    $data['data'][$jv['keyword']]['value'] = "已接单";
                                    break;
                                case '4':
                                    $data['data'][$jv['keyword']]['value'] = date("Y-m-d H:i:s", $order->pay_time);
                                    break;
                                case '5':
                                    $data['data'][$jv['keyword']]['value'] = $store['title'];
                                    break;

                            }
                        }
                        Log::info("订单状态推送数据：" . json_encode($data['data']));
                        $data['order_id'] = $order['id'];
                        $data['openid'] = $userInfo['openid'];
                        $data['masterplate'] = $message->masterplate;
                        Queue::later(2, 'addons\fzdc\job\SendMessage', $data, 'dcOrderQueue');
                    }

                }

            }
            $this->success("接单成功");
        }elseif ($status == 2){
            //确认送出
            if ($order['order_type'] == 3 || $order['order_type'] == 1){
                $order->status = 3;
            }elseif($order['order_type'] == 2){
                $order->status = 2;
            }else{
                $order->status = 7;
            }
            if ($order['p_type'] == 2){
                $order->status = 1;
            }

            Detail::where(['order_id' => $order['id']])->update(['is_sc' => 1]);

            $order->save();
            //订单放入队列 处理自动收货完成
            $data = ["orderNo" => $order['order_no'], "type" => 2];
            Queue::later(bcmul(get_addon_config('fzdc')['shtime'], 60), 'addons\fzdc\job\Overtime', $data, 'dcOrderQueue');
            $this->success("确认送出成功");
        }

        $order->status = $status;
        if ($status==3 && !$order->pay_type && $order->order_type != 4){
            $order->pay_type = 3;
        }
        $order->save();
        if ($order->seat_id){
            Sear::whereIn("id", explode(",", $order->seat_id))->update(["use_status" => 1]);
        }
        $this->success("操作成功");
    }


    /**
     * 详情
     */
//    public function detail($ids)
//    {
//        $data = $this->model->where("id", $ids)->find();
//        if (false === $this->request->isPost()) {
//            if ($data['order_type'] == 1) {
//                $data['order_type'] = "自取";
//            } elseif ($data['order_type'] == 2) {
//                $data['order_type'] = "外卖";
//                $data['addressInfo'] = $this->modeladdress->where("id", $data['address_id'])->find();
//
//            } elseif ($data['order_type'] == 3) {
//                $data['order_type'] = "堂食";
//            } elseif ($data['order_type'] == 4) {
//                $data['order_type'] = "预约";
//            }else{
//                $data['order_type'] = "充值";
//            }
//            $list = Detail::with(['store', 'goods'])
//                ->where(['detail.order_id' => $ids])
//                ->select();
//
//            if (!$list) {
//                $this->error(__('No Results were found'));
//            }
//
//            foreach ($list as $row) {
//                $row->getRelation('store')->visible(['title']);
//                $row->getRelation('goods')->visible(['title', 'image']);
//            }
//            $this->view->assign("data", $list);
//            $this->view->assign("row", $data);
//            return $this->view->fetch();
//        }
//        $params = $this->request->post('row/a');
//        //已取消状态判断
//        if ($params['status'] == 5 && in_array($data['status'], [4, 9])) {
//            $this->error("该状态不可更改");
//        }
//        //已退款状态判断
//        if ($params['status'] == 9 && in_array($data['status'], [1, 5])) {
//            $this->error("该状态不可更改");
//        }
//        if ($params['status'] == 5) {
//            //判断是否有优惠券
//            if ($data['coupon_id'] > 0) {
//                //更新优惠券为未使用，判断优惠券是否过期
//                $coupon = Coupon::get($data['coupon_id']);
//                if (time() > $coupon->end_time) {
//                    $res5 = Receive::update(['state' => -1], ["user_id" => $data['user_id'], "coupon_id" => $data['coupon_id']]);
//                } else {
//                    $res5 = Receive::update(['state' => 0], ["user_id" => $data['user_id'], "coupon_id" => $data['coupon_id']]);
//                }
//                Log::log("优惠券更新状态" . $res5);
//            }
//            //增加库存
//            if ($data['cart_ids']) {
//                $ids = Detail::where(['order_id' => $data['id']])->column('id');
//                foreach ($ids as $v) {
//                    $detail = Detail::where(["id" => $v])->find();
//                    $sku = SkuProduct::get(["key" => $detail['goods_item_id']]);
//                    $sku->setInc("stock", $detail['goods_count']);
//                }
//            }
//        } elseif ($params['status'] == 9) {
//            //退款
//            $res = \app\admin\model\fzdc\order\Order::get($ids);
//            Db::startTrans();
//            try {
//                //判断是否有优惠券
//                if ($res['coupon_id'] > 0) {
//                    //更新优惠券为未使用，判断优惠券是否过期
//                    $coupon = Coupon::get($res['coupon_id']);
//                    if (time() > $coupon->end_time) {
//                        $res5 = Receive::update(['state' => -1], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
//                    } else {
//                        $res5 = Receive::update(['state' => 0], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
//                    }
//                    Log::log("优惠券更新状态" . $res5);
//                }
//                //增加库存
//                if ($res['cart_ids']) {
//                    $ids = Detail::where(['order_id' => $res['id']])->column('id');
//                    foreach ($ids as $v) {
//                        $detail = Detail::where(["id" => $v])->find();
//                        $sku = SkuProduct::get(["key" => $detail['goods_item_id']]);
//                        $sku->setInc("stock", $detail['goods_count']);
//                    }
//                }
//                //判断是否钱包支付
//                if ($res['pay_type'] == 2) {
//                    Log::info("前台已直接退款");
//                } else {
//                    //审核通过
//                    //原路返回
//                    $order['transaction_id'] = $res['out_trade_no'];
//                    $order['out_refund_no'] = date('YmdHis') . substr(bcadd($ids, strrev(date('YmdHis')), 0), -8) . bcadd($ids, time(), 0);
//                    $order['total_fee'] = bcmul($res['order_amount_total'], 100, 0); // 原支付交易的订单总金额
//                    $order['refund_fee'] = bcmul($res['order_amount_total'], 100, 0); // 退款金额
//                    $order['notify_url'] = request()->domain() . "/addons/fzdc/order/refund_notifyx";
//                    $config = Service::getConfig();
//                    $pay = Pay::wechat($config);
//                    $result = $pay->refund($order);
//                    Log::info("退款结果：" . json_encode($result));
//                    if ($result['return_code'] == "SUCCESS") {
//                        Log::info("退款成功，退款单号：" . $order['out_refund_no']);
//                        if ($res->order_type == 4) {
//                            $res->status = 5;
//                        } else {
//                            $res->status = 9;
//                        }
//                        $res->out_refund_no = $order['out_refund_no'];
//                        $res->refund_id = $result['refund_id'];
//                        $res->save();
//                        //增加收入支出明细
//                        Revenue::create(["store_id" => $res['store_id'], "type" => 2, "amount" => $res['order_amount_total'], "user_id" => $res['user_id'], "createtime" => time(), "updatetime" => time()]);
//                        //增加退款流水
//                        Refund::where(['order_id' => $res['id']])->update(["refund_status" => 2, "updatetime" => time()]);
//                    } else {
//                        return json(["code" => 0, "msg" => "退款失败,请稍后重试"]);
//                    }
//
//                }
//                Db::commit();
//            } catch (\Exception $e) {
//                // 回滚事务
//                Db::rollback();
//                $this->error("退款失败：" . $e->getMessage());
//            }
//
//        }
//
//        \app\admin\model\fzdc\order\Order::where(["order_no" => $params['order_no']])->update(["status" => $params['status']]);
//        $this->success();
//    }

    /**
     * 退款
     */
    public function refund($ids)
    {
        if (false === $this->request->isPost()) {
            $data = $this->model->where("id", $ids)->find();

            $this->view->assign("data", $data);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        $res = \app\admin\model\fzdc\order\Order::get($ids);


        //判断是否钱包支付
        if ($res['pay_type'] == 2) {
            //退款到钱包
            //增加收入支出明细
            Revenue::create(["store_id" => $res['store_id'], "type" => 2, "amount" => $res['order_amount_total'], "user_id" => $res['user_id'], "createtime" => time(), "updatetime" => time()]);
            //增加退款流水
            $refund_insert_data['refund_status'] = 2;
            Refund::create($refund_insert_data);
            $store = Store::get($res['store_id']);
            $store->setDec("balance", $res['order_amount_total']);
            $store->save();
            $user = \app\admin\model\User::get($res['user_id']);
            $user->setInc("money", $res['order_amount_total']);
            $user->save();
            //判断是否有优惠券
            if ($res['coupon_id'] > 0) {
                //更新优惠券为未使用，判断优惠券是否过期
                $coupon = Coupon::get($res['coupon_id']);
                if (time() > $coupon->end_time) {
                    $res5 = Receive::update(['state' => -1], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
                } else {
                    $res5 = Receive::update(['state' => 0], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
                }
                Log::log("优惠券更新状态" . $res5);
            }
            //增加库存
            if ($res['cart_ids']) {
                $ids = Detail::where(['order_id' => $res['id']])->column('id');
                foreach ($ids as $v) {
                    $detail = Detail::where(["id" => $v])->find();
                    if ($detail['goods_item_id']){
                        $sku = SkuProduct::get(["key" => $detail['goods_item_id']]);
                        $sku->setInc("stock", $detail['goods_count']);
                    }

                }
            }
            if ($res->order_type == 4) {
                $res->status = 5;
            } else {
                $res->status = 9;
            }
            //判断是否赠送积分 如果赠送积分 则扣除积分
            if ($res['score']) {
                //取系统默认赠送积分数
                \app\common\model\User::score(-$res['score'], $res['user_id'], "退款扣除积分");
            }
            $res->apply = "";
            $res->save();
            return json(["code" => 1, "msg" => "审核成功"]);
        }


        if ($params['status'] == 1) {
            //审核通过
            //原路返回
            Db::startTrans();
            try {
                $order['transaction_id'] = $res['out_trade_no'];
                $order['out_refund_no'] = date('YmdHis') . substr(bcadd($ids, strrev(date('YmdHis')), 0), -8) . bcadd($ids, time(), 0);
                $order['total_fee'] = bcmul($res['order_amount_total'], 100, 0); // 原支付交易的订单总金额
                $order['refund_fee'] = bcmul($res['order_amount_total'], 100, 0); // 退款金额
                $order['notify_url'] = request()->domain() . "/addons/fzdc/order/refund_notifyx";
                $config = Service::getConfig();
                $pay = Pay::wechat($config);
                $result = $pay->refund($order);
                Log::info("退款结果：" . json_encode($result));
                if ($result['return_code'] == "SUCCESS") {
                    Log::info("退款成功，退款单号：" . $order['out_refund_no']);
                    if ($res->order_type == 4) {
                        $res->status = 5;
                    } else {
                        $res->status = 9;
                    }
                    $res->out_refund_no = $order['out_refund_no'];
                    $res->refund_id = $result['refund_id'];
                    $res->save();
                    $store = Store::get($res['store_id']);
                    $store->setDec("balance", $res['order_amount_total']);
                    $store->save();
                    //增加收入支出明细
                    Revenue::create(["store_id" => $res['store_id'], "type" => 2, "amount" => $res['order_amount_total'], "user_id" => $res['user_id'], "createtime" => time(), "updatetime" => time()]);
                    //增加退款流水
                    Refund::where(['order_id' => $res['id']])->update(["refund_status" => 2, "updatetime" => time()]);
                    if ($res['coupon_id'] > 0) {
                        //更新优惠券为未使用，判断优惠券是否过期
                        $coupon = Coupon::get($res['coupon_id']);
                        if (time() > $coupon->end_time) {
                            $res5 = Receive::update(['state' => -1], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
                        } else {
                            $res5 = Receive::update(['state' => 0], ["user_id" => $res['user_id'], "coupon_id" => $res['coupon_id']]);
                        }
                        Log::log("优惠券更新状态" . $res5);
                    }
                    //增加库存
                    if ($res['cart_ids']) {
                        $ids = Detail::where(['order_id' => $res['id']])->column('id');
                        foreach ($ids as $v) {
                            $detail = Detail::where(["id" => $v])->find();
                            if ($detail['goods_item_id']){
                                $sku = SkuProduct::get(["key" => $detail['goods_item_id']]);
                                $sku->setInc("stock", $detail['goods_count']);
                            }

                        }
                    }
                    //判断是否赠送积分 如果赠送积分 则扣除积分
                    if ($res['score']) {
                        //取系统默认赠送积分数
                        \app\common\model\User::score(-$res['score'], $res['user_id'], "退款扣除积分");
                    }
                } else {
                    return json(["code" => 0, "msg" => "退款失败,请稍后重试"]);
                }
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->error("退款失败：" . $e->getMessage() . ",行号：" . $e->getLine() . ",文件：" . $e->getFile());
            }
        } else {
            //审核拒绝 订单回滚到上一状态
            \app\admin\model\fzdc\order\Order::where(["id" => $ids])->update(["status" => $res['last_status']]);
            Refund::where(['order_id' => $res['id']])->update(["refund_status" => 3, "updatetime" => time()]);
        }
        return json(["code" => 1, "msg" => "审核成功"]);
    }

    /**
     * 打印小票
     *
     */
    public function print($ids)
    {
        $data = $this->model->where("id", $ids)->find();
        if ($data['type'] == 2){
            $this->error("充值订单不能打印小票");
        }
        $storeInfo = $this->modelstore->where("id", $data['store_id'])->find();


        if ($storeInfo['print_sn'] == '') {
            $this->error("商家没有配置打印机!");
        }
        //获取打印机配置
        $print = Prin::get(["id" => $storeInfo['print_sn'], "status" => "normal"]);
        if (!$print) {
            $this->error("未获取到打印机配置");
        }
        try {
            \addons\fzdc\library\printer::printing($storeInfo, $print->id, $ids,1);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }

        $this->success("打印成功");
    }

}
