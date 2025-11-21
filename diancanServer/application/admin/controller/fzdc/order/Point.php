<?php

namespace app\admin\controller\fzdc\order;

use app\common\controller\Backend;

/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Point extends Backend
{

    /**
     * Point模型对象
     * @var \app\admin\model\fzdc\order\Point
     */
    protected $model = null;
    protected $searchFields = 'order_no';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\order\Point;
        $this->view->assign("statusList", $this->model->getStatusList());
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

            $list = $this->model
                ->with(['user', 'pointgoods', 'address'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id', 'order_no', 'user_id', 'goods_id', 'coupon_id', 'status', 'goods_price', 'order_amount_total', 'createtime']);
                $row->visible(['user']);
                $row->getRelation('user')->visible(['username']);
                $row->visible(['pointgoods']);
                $row->getRelation('pointgoods')->visible(['title']);
                $row->visible(['address']);
                $row->getRelation('address')->visible(['title']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 详情
     */
    public function detail($ids)
    {
        $list = $this->model
            ->with(['user', "pointgoods", "address"])
            ->where(['point.id' => $ids])
            ->find();

        if (!$list) {
            $this->error(__('No Results were found'));
        }

        foreach ($list as $row) {
            $row->getRelation('user')->visible(['username']);
        }

        if ($this->request->isAjax()) {
            $this->success("Ajax请求成功", null, ['id' => $ids]);
        }
        $this->view->assign("row", $list->toArray());
        return $this->view->fetch();
    }

    /*
     * 发货
     */
    public function send($ids)
    {
        if ($this->request->isPost()) {
            $numbers = $this->request->post("row.numbers");
            $courier = $this->request->post("row.courier");
            if (empty($numbers) || empty($courier)) {
                $this->error("参数不能为空");
            }

            //更新发货信息
            \app\admin\model\fzdc\order\Point::update(["status" => 2, "numbers" => $numbers, "courier" => $courier, "delivery_time" => time()], ["id" => $ids]);
            //异步推送消息
//            $message = Message::get(1);
//            $order   = \app\admin\model\order\Order::get($ids);
//            $data['data'] = [
//                "$message->order_no"=>[
//                    'value'=>$order->order_no
//                ],
//                "$message->goods_name"=>[
//                    'value'=>$order->goods_name
//                ],
//                "$message->goods_count"=>[
//                    'value'=>$order->goods_count
//                ],
//                "$message->goods_prices"=>[
//                    'value'=>$order->goods_prices
//                ],
//                "$message->order_amount_total"=>[
//                    'value'=>$order->order_amount_total
//                ],
//                "$message->courier"=>[
//                    'value'=>$order->courier
//                ],
//                "$message->numbers"=>[
//                    'value'=>$order->numbers
//                ],
//            ];
//            $data['openid']      = User::get($order->user_id)->openid;
//            $data['masterplate'] = $message->masterplate;
//            Queue::later( 2, 'addons\venue\job\SendMessage', $data, 'sendQueue');
            $this->success("发货成功");
//            return json(["code"=>1,"msg"=>"发货成功"]);
        }
        //
        $data = $this->model
            ->where(['id' => $ids])
            ->field("courier,numbers")
            ->find();
        $this->view->assign("row", $data->toArray());
        return $this->view->fetch();
    }

    /*
     * 完成订单
     */
    public function complete($ids)
    {
        //修改订单状态
        $this->model->save(["status" => 3], ["id" => $ids]);
        return ["code" => 1, "msg" => "修改订单状态成功"];
    }


}
