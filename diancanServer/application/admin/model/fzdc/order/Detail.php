<?php

namespace app\admin\model\fzdc\order;

use app\admin\model\User;
use think\Model;


class Detail extends Model
{
    // 表名
    protected $name = 'fzdc_order_detail';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';
    protected $resultSetType = 'collection';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    public function getImageAttr($value, $data)
    {
        return cdnurl($value, true);
    }

    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'score_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function goods()
    {
        return $this->belongsTo('app\admin\model\fzdc\Goods', 'goods_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function orders()
    {
        return $this->belongsTo('app\admin\model\fzdc\order\Order', 'order_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
