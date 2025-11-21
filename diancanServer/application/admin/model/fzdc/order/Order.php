<?php

namespace app\admin\model\fzdc\order;

use think\Model;


class Order extends Model
{


    // 表名
    protected $name = 'fzdc_order';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';
    protected $resultSetType = 'collection';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'order_type_text'
    ];


    public function getOrderTypeList()
    {
        return ['1' => __('Order_type 1'), '2' => __('Order_type 2'), '3' => __('堂食'), '4' => __('预约')];
    }


    public function getStatusList()
    {
        return ['1' => __('Status 1'), '6' => __('备餐'), '7' => __('出餐'), '8' => __('待取单'), '2' => __('Status 2'), '3' => __('Status 3'), '4' => __('Status 4'), '5' => __('Status 5'), '9' => __('已退款'), '10' => __('预约中')];
    }

    public function getCouponStateList()
    {
        return ['0' => __('Coupon_state 0'), '1' => __('Coupon_state 1')];
    }


    public function getOrderTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['order_type']) ? $data['order_type'] : '');
        $list = $this->getOrderTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCouponStateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['coupon_state']) ? $data['coupon_state'] : '');
        $list = $this->getCouponStateList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getSubscribeTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['subscribe_time']) ? $data['subscribe_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getServiceTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['service_time']) ? $data['service_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setSubscribeTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setServiceTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'store_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function goods()
    {
        return $this->belongsTo('app\admin\model\fzdc\Goods', 'goods_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function coupon()
    {
        return $this->belongsTo('app\admin\model\fzdc\Coupon', 'coupon_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
