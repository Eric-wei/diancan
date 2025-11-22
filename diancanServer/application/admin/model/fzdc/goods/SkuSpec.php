<?php

namespace app\admin\model\fzdc\goods;

use think\Model;

/**
 * SkuSpec商品插件
 */
class SkuSpec extends Model
{
    // 表名
    protected $name = 'fzdc_goods_sku_spec';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    protected $resultSetType = 'collection';

    // 追加属性
    protected $append = [

    ];

    public function skuItems()
    {
        return $this->hasMany('SkuItem', 'spec_id');
    }
}
