<?php

namespace app\admin\model\fzdc\store;

use think\Model;


class Revenue extends Model
{


    // 表名
    protected $name = 'fzdc_revenue';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];


    public function getTypeList()
    {
        return ['1' => __('收入'), '2' => __('支出')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
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
