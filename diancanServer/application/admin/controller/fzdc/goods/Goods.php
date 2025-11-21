<?php

namespace app\admin\controller\fzdc\goods;

use app\admin\controller\fzdc\Base;
use app\admin\library\Auth;
use app\admin\model\fzdc\Store;
use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 商品管理
 *
 * @icon fa fa-circle-o
 */
class Goods extends Base
{

    protected $dataLimitField = 'store_ids';
    protected $dataLimitFieldAutoFill = false;
    /**
     * Goods模型对象
     * @var \app\admin\model\fzdc\goods\Goods
     */
    protected $model = null;
    protected $searchFields = 'title';
    /**
     * @var \app\admin\model\fzdc\goods\SkuSpec
     */
    private $specModel;
    /**
     * @var \app\admin\model\fzdc\goods\SkuItem
     */
    private $itemModel;
    /**
     * @var \app\admin\model\fzdc\goods\SkuProduct
     */
    private $productModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\goods\Goods;
        $this->specModel = new \app\admin\model\fzdc\goods\SkuSpec;
        $this->itemModel = new \app\admin\model\fzdc\goods\SkuItem;
        $this->productModel = new \app\admin\model\fzdc\goods\SkuProduct;
        $this->view->assign("stateList", $this->model->getStateList());
        $this->view->assign("shipmentList", $this->model->getShipmentList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    protected function getDataLimitAdminIds()
    {
        $auth = Auth::instance();
        if ($this->addonsConfig['isStoreAllocate'] == 1 && !$auth->isSuperAdmin()) {
            return $this->authStoreIds;
        }
    }


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->with(['types', 'store'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id', 'type_id','type', 'title', 'tags', 'switch', 'store_ids', 'image', 'sku_json', 'createtime', 'updatetime', 'state','weigh']);
                $row->visible(['types']);
                $row->getRelation('types')->visible(['name']);
                $row->visible(['store']);
                $row->getRelation('store')->visible(['title']);
//                $tags = get_addon_config("fzdc");
//                $tags = explode(",", $tags['tags']);
//                $ro = explode(",", $row->tags);
//                $s = "";
//                foreach ($tags as $K => $v) {
//                    foreach ($ro as $val) {
//                        if ($val === ''){
//                            continue;
//                        }
//                        if ($val == $K) {
//                            $s .= $v . ",";
//                        }
//                    }
//                }
//
//                $row->tags = rtrim($s, ",");
            }

            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            $this->view->assign("tags", Config::get("site.tags"));
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
//        var_dump($params);die;

        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
//		if ($params['skus']=='[]' || $params['groups']=='[]' || $params['items']=='[]'){
//            $this->error(__('规格不能为空'));
//        }
//        if ($params['type']==1){
//            if ($params['price']<=0){
//                $this->error(__('单规格价格必须大于0'));
//            }
//        }
        //获取分类的门店id
        $type = \app\admin\model\fzdc\goods\Type::get($params['type_id']);
        if ($type['switch']){
            //获取所有门店id
            $params['store_ids'] = implode(',', Store::column('id'));
        }else{
            $params['store_ids'] = $type['store_ids'];
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            if ($params['items']!= '[]'){
                $this->createSpecItem($this->model->id, $params); //新增规格商品
            }
            Db::commit();
        } catch (ValidateException | PDOException | Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }

    /**
     * 编辑
     *
     * @param $ids
     * @return string
     * @throws DbException
     * @throws \think\Exception
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
//				if ($params['skus']=='[]' || $params['groups']=='[]' || $params['items']=='[]'){
//                    $this->error(__('规格不能为空'));
//                }
                if ($params['type']==1){
//                    if ($params['price']<=0){
//                        $this->error(__('单规格价格必须大于0'));
//                    }
                    //判断规格是否大于一
                    $groups = json_decode($params['groups'], true);
                    if (count($groups)>1){
                        $this->error("单规格商品不能有多规格");
                    }

                }
                //获取分类的门店id
                $type = \app\admin\model\fzdc\goods\Type::get($params['type_id']);
                if ($type['switch']){
                    //获取所有门店id
                    $params['store_ids'] = implode(',', Store::column('id'));
                }else{
                    $params['store_ids'] = $type['store_ids'];
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);


                    // 删除原本的规格参数
                    $this->specModel->where('goods_id', $row->id)->delete();
                    $this->itemModel->where('goods_id', $row->id)->delete();
                    $this->productModel->where('goods_id', $row->id)->delete();

                    if ($params['items']!= '[]'){
                        $this->createSpecItem($row->id, $params); //新增规格商品
                    }
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->getVueInitData($ids);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 真实删除
     *
     * @param $ids
     * @return void
     */
    public function destroy($ids = null)
    {
        if (false === $this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ?: $this->request->post('ids');
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        Db::startTrans();
        try {
            $list = $this->model->onlyTrashed()->select();
            foreach ($list as $item) {
                $count += $item->delete(true);
            }
            $this->model->where($pk, 'in', $ids);
            // 删除原本的规格参数
            $this->specModel->where('goods_id', 'in', $ids)->delete();
            $this->itemModel->where('goods_id', 'in', $ids)->delete();
            $this->productModel->where('goods_id', 'in', $ids)->delete();
            Db::commit();
        } catch (PDOException | Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success();
        }
        $this->error(__('No rows were deleted'));
    }


    /**
     * 商品规格写入
     *
     * @param [type] $goodsId
     * @param [type] $row
     * @return void
     */
    private function createSpecItem($goodsId, $row)
    {
        try {
            $groups = json_decode($row['groups'], true);
            $skus = json_decode($row['skus'], true);
            $items = json_decode($row['items'], true);

            // 写入spec
            $spec = [];
            foreach ($groups as $k => $v) {
                if (!count($items[$k])) {
                    continue;
                }
                $spec[] = ['name' => $v, 'goods_id' => $goodsId];
            }
            $res = $this->specModel->saveAll($spec);

            // 写入item
            $item = [];
            foreach ($res as $k => $v) {
                foreach ($items[$k] as $kk => $vv) {
                    $item[] = [
                        'name' => $vv,
                        'spec_id' => $v['id'],
                        'goods_id' => $goodsId // 仅为了方便删除
                    ];
                }
            }
            $res = $this->itemModel->saveAll($item);

            // 写入规格商品
            $product = [];
            foreach ($skus as $k => $v) {
                $v['key'] = $this->getKey($res, $v['attr']);
                $v['goods_id'] = $goodsId;
                unset($v['attr']);
                $product[] = $v;
            }
            $this->productModel->saveAll($product);
        } catch (Exception $e) {
            throw new Exception('添加出错'); //让外部回滚
        }
    }

    /**
     * 为规格商品匹配key值
     *
     * @param [type] $ids
     * @param [type] $attr
     * @return void
     */
    private function getKey($ids, $attr)
    {
        $key = '';
        foreach ($attr as $v) {
            foreach ($ids as $k => $vv) {
                if ($vv->name === $v) {
                    $key .= '_' . $vv->id;
                }
            }
        }
        return ltrim($key, '_');
    }

    /**
     * 获取vue参数
     *
     * @param [type] $ids
     * @return void
     */
    private function getVueInitData($ids)
    {
        // 规格
        $groups = [];
        $items = [];
        $specs = $this->specModel->with('skuItems')->where('goods_id', $ids)->select();
        $arr = collection($specs)->toArray();
        $itemKey = []; //商品使用
        foreach ($specs as $k => $v) {
            $groups[] = $v['name'];
            $i = [];
            foreach ($v['sku_items'] as $kk => $vv) {
                $i[] = $vv['name'];
                $itemKey[$vv['id']] = $vv['name'];
            }
            $items[] = $i;
        }

        // 商品
        $products = $this->productModel->where('goods_id', $ids)->select();
        $products = collection($products)->toArray();
        foreach ($products as $k => $v) {
            $keyArr = explode('_', $v['key']);
            $attr = [];
            foreach ($keyArr as $kk => $vv) {
                if (!empty($vv)){
                    $attr[] = $itemKey[$vv];
                }
            }
            unset($products[$k]['key'], $products[$k]['id']);
            $products[$k]['attr'] = $attr;
        }
        // 渲染到js
        $this->assignconfig("skus", ['groups' => $groups, 'items' => $items, 'products' => $products]);
    }

}
