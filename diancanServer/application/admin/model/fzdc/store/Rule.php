<?php

namespace app\admin\model\fzdc\store;

use think\Model;


class Rule extends Model
{


    // 表名
    protected $name = 'fzdc_yy_rule';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function label()
    {
        return $this->belongsTo('app\admin\model\fzdc\store\Label', 'label_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
