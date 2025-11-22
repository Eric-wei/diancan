<?php

namespace app\admin\controller\fzdc;


use app\admin\model\fzdc\goods\Goods;
use app\admin\model\fzdc\goods\SkuProduct;
use app\admin\model\fzdc\order\Detail;
use app\admin\model\fzdc\order\Order;
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
class Dashboard extends Backend
{

    public function index()
    {

        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {
        }

        $totalShop = Store::count();
        // 店铺
        $this->view->assign("totalShop", $totalShop);
        // 商品
        $totalGoods = Goods::count();
        $this->view->assign("totalGoods", $totalGoods);
        // 用户
        $totalUser = User::count();
        $totalDayUser = User::whereTime("createtime", "d")->count();
        $this->view->assign("totalUser", $totalUser);
        $this->view->assign("totalDayUser", $totalDayUser);

        $MoneyPaySum = Order::where("status", 3)->sum('order_amount_total');
        $this->view->assign("MoneyPaySum", $MoneyPaySum);
        $MoneyLogDayPay = Order::whereTime("createtime", 'd')->where("status", 3)->sum('order_amount_total');
        $this->view->assign("MoneyLogDayPay", $MoneyLogDayPay);
        $MoneyLogDayRecharge = Order::whereTime("createtime", 'yesterday')->sum('order_amount_total');
        $this->view->assign("MoneyLogDayRecharge", $MoneyLogDayRecharge);

        $initiateWithdraw = SkuProduct::sum('stock');
        $this->view->assign("initiateWithdraw", $initiateWithdraw);


        // 订单
        $totalOrder = Order::count();
        $paidOrder = Order::where(["order_type" => 1])->count();
        $deliveredOrder = Order::where(["order_type" => 2])->count();
        $this->view->assign("totalOrder", $totalOrder);
        $this->view->assign("paidOrder", $paidOrder);
        $this->view->assign("deliveredOrder", $deliveredOrder);
        // SKU
        $totalSku = SkuProduct::count();
        $this->view->assign("totalSku", $totalSku);
        $this->view->assign("totalGoodsViews", 1);
        // 评论
        $this->view->assign("totalComment", 1);

        // 热销
        $goodsTopList = Detail::field("COUNT(*) as sales,goods_id")->group('goods_id')->limit(5)->select();
        if (!$goodsTopList->isEmpty()) {
            $goodsTopList = $goodsTopList->toArray();
            array_multisort(array_column($goodsTopList, 'sales'), SORT_DESC, $goodsTopList);
            foreach ($goodsTopList as $gk => &$gv) {
                $good = Goods::get($gv['goods_id']);
                if (!$good) {
                    unset($goodsTopList[$gk]);
                    continue;
                }
                $gv['title'] = $good['title'];
            }
        }


        $this->view->assign("goodsTopList", $goodsTopList);

        // 新申请店铺
        $shopAuthList = Store::field("title as shopname")->order("createtime", "desc")->limit(10)->select();
        $this->assign("shopAuthList", $shopAuthList);

        // 介入退款
        $this->assignconfig('servicesRefundList', []);


        //订单数和订单额统计
        list($orderSaleCategory, $orderSaleAmount, $orderSaleNums) = $this->statis();
        $this->assignconfig('orderSaleCategory', $orderSaleCategory);
        $this->assignconfig('orderSaleAmount', $orderSaleAmount);
        $this->assignconfig('orderSaleNums', $orderSaleNums);


        return $this->view->fetch();
    }



    /**
     * 获取订单销量销售额统计数据
     * @param string $date
     * @return array
     */
    public function statis($date = '')
    {
        $starttime = \fast\Date::unixtime();
        $endtime = \fast\Date::unixtime('day', 0, 'end');
        $format = '%H:00';
        // 1.0.3修复 自动获取表前缀
        $prefix = Config::get('database.prefix');
        $list = model('app\admin\model\fzdc\order\Order')
            ->alias([$prefix . 'fzdc_order' => 'order'])
            ->where('order.createtime', 'between time', [$starttime, $endtime])
            ->where('order.status', 3)
            ->field('order.createtime,order.order_amount_total as amount, order.status,COUNT(*) AS nums,MIN(order.createtime) AS min_paytime,MAX(order.createtime) AS max_paytime,DATE_FORMAT(FROM_UNIXTIME(order.createtime), "' . $format . '") AS paydata')
            ->group('paydata')
            ->select();
        $column = [];
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("H:00", $time);
            $time += 3600;
        }
        $orderSaleNums = $orderSaleAmount = array_fill_keys($column, 0);
        foreach ($list as $vo) {
            $orderSaleNums[$vo['paydata']] = $vo['nums'];
            $orderSaleAmount[$vo['paydata']] = round($vo['amount'], 2);
        }
        $orderSaleCategory = array_keys($orderSaleAmount);
        $orderSaleAmount = array_values($orderSaleAmount);
        $orderSaleNums = array_values($orderSaleNums);
        return [$orderSaleCategory, $orderSaleAmount, $orderSaleNums];
    }

}
