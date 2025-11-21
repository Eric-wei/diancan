<?php

namespace app\admin\model\fzdc\store;

use think\Model;
use traits\model\SoftDelete;

class Sear extends Model
{

    use SoftDelete;

    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzdc_seat';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];


    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypeList()
    {
        return ['1' => __('单人桌'), '2' => __('双人桌'), '3' => __('三人桌'), '4' => __('四人桌'), '6' => __('六人桌'), '8' => __('八人桌'), '10' => __('十人桌'), '12' => __('十二人桌')];
    }

    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'store_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function seart()
    {
        return $this->belongsTo('app\admin\model\fzdc\store\Seart', 'type', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
