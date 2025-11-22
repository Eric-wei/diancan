<?php

namespace app\admin\model\fzdc\store;

use think\Model;

class StoreAdmin extends Model
{

    // 表名
    protected $name = 'fzdc_store_admin';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [];

    protected static function init()
    {


    }


}
