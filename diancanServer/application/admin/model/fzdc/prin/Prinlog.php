<?php

namespace app\admin\model\fzdc\prin;

use think\Model;


class Prinlog extends Model
{

    

    

    // 表名
    protected $name = 'fzdc_print_log';
    
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
