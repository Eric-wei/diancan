<?php

namespace app\admin\model\fzdc\store;

use think\Model;

class Template extends Model
{

    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzdc_store_template';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


}
