<?php

namespace app\admin\model\fzdc\store;

use think\Model;


class StoreCollect extends Model
{
    // 表名
    protected $name = 'fzdc_store_collect';

    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\store\Store', 'store_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
