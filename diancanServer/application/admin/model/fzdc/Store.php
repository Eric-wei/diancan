<?php

namespace app\admin\model\fzdc;

use think\Model;

class Store extends Model
{
    // 表名
    protected $name = 'fzdc_store';

    public function getImageAttr($value, $data)
    {
        return cdnurl($value, true);
    }

}
