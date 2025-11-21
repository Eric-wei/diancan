<?php

namespace app\admin\model\fzdc\store;

use think\Model;


class User extends Model
{


    // 表名
    protected $name = 'fzdc_store_user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'order_type_text',
        'status_text'
    ];


    public function getOrderTypeList()
    {
        return ['1' => __('店长'), '2' => __('员工')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
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


    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'store_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function us()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
