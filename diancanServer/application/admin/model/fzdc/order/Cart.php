<?php

namespace app\admin\model\fzdc\order;

use think\Model;


class Cart extends Model
{
    // 表名
    protected $name = 'fzdc_cart';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    protected $resultSetType = 'collection';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function goods()
    {
        return $this->belongsTo('app\admin\model\fzdc\Goods', 'goods_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function getImageAttr($value, $data)
    {
        return cdnurl($value, true);
    }


}
