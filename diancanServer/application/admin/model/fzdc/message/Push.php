<?php

namespace app\admin\model\fzdc\message;

use think\Model;


class Push extends Model
{


    // 表名
    protected $name = 'fzdc_message_push';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function typeList()
    {
        return ['1' => __('支付成功通知'), '2' => __('积分变更通知'), '3' => __('订单状态通知'),'4'=>'用户加菜通知'];
    }


}
