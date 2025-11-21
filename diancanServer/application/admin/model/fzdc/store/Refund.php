<?php

namespace app\admin\model\fzdc\store;

use think\Model;


class Refund extends Model
{


    // 表名
    protected $name = 'fzdc_refund';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'refund_status_text'
    ];


    public function getRefundStatusList()
    {
        return ['1' => __('Refund_status 1'), '2' => __('Refund_status 2')];
    }


    public function getRefundStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['refund_status']) ? $data['refund_status'] : '');
        $list = $this->getRefundStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'store_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
