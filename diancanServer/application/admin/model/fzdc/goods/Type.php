<?php

namespace app\admin\model\fzdc\goods;

use think\Model;
use traits\model\SoftDelete;

class Type extends Model
{

    use SoftDelete;


    // 表名
    protected $name = 'fzdc_goods_type';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';
    protected $resultSetType = 'collection';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];


    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }


    public function getStateList()
    {
        return ['0' => __('State 0'), '1' => __('State 1')];
    }


    public function getStateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['state']) ? $data['state'] : '');
        $list = $this->getStateList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function store()
    {
        return $this->belongsTo('app\admin\model\fzdc\Store', 'store_ids', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
