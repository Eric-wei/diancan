<?php

namespace app\admin\model\fzdc\notice;

use think\Model;


class Notice extends Model
{


    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzdc_notice';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


}
