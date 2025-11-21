<?php

namespace app\admin\model\fzdc\goods;

use think\Model;

/**
 * sku商品插件
 */
class SkuProduct extends Model
{
    // 表名
    protected $name = 'fzdc_goods_sku_product';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    protected $resultSetType = 'collection';

    // 追加属性
    protected $append = [

    ];
}
