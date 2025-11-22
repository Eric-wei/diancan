<?php

namespace app\admin\model\fzdc\goods;

use think\Model;

/**
 * SkuItem商品插件
 */
class SkuItem extends Model
{
    // 表名
    protected $name = 'fzdc_goods_sku_item';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    protected $resultSetType = 'collection';

    // 追加属性
    protected $append = [

    ];

}
