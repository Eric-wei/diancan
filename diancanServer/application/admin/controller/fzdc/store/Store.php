<?php

namespace app\admin\controller\fzdc\store;

use app\admin\controller\fzdc\Base;
use app\admin\library\Auth;
use app\admin\model\AuthGroupAccess;
use app\admin\model\fzdc\goods\Type;
use app\admin\model\fzdc\Userdetail;
use app\common\controller\Backend;
use think\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Model;
use app\admin\model\Admin;
use app\admin\model\fzdc\store\StoreAdmin;



/**
 * 门店管理
 *
 * @icon fa fa-circle-o
 */
class Store extends Base
{

    protected $dataLimitField = 'id';
    protected $dataLimitFieldAutoFill = false;

    /**
     * Store模型对象
     * @var \app\admin\model\fzdc\store\Store
     */
    protected $model = null;
    protected $searchFields = 'title';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\store\Store;
        $this->view->assign("stateList", $this->model->getStateList());
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
        $this->relationSearch = false;
        $type_id = $this->request->param('type_id/a');
        $wheres = [];
        if ($type_id) {
            $type = Type::get($type_id);
            if ($type){
                if (!$type->switch){
                    $wheres['id'] = ['in',explode(',',$type->store_ids)];
                }
            }
        }
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
//            if ($this->request->request('keyField')) {
//                return $this->selectpage();
//            }
            if ($this->request->request('keyValue')){
                $list = $this->model->where('id', $this->request->request('keyValue'))->select();
                return json(['total' => count($list), 'rows' => $list]);

            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->where($wheres)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {

                $row->visible(['id', 'title', 'alias_title', 'address', 'image', 'start_times', "authentication", "cleck_goods_counts", 'end_times', 'subscribe_switch', 'delivery_switch', 'trade_switch', 'balance', 'takeaway_switch', 'createtime', 'updatetime', 'weigh', 'state', 'ps_price', 'db_price']);

            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
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
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params['lon']) || empty($params['lat'])) {
            unset($params['lon'], $params['lat']);
        }
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                $row->validateFailException()->validate($validate);
            }
            $result = $row->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException | PDOException | \Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

    /**
     * 门店授权
     */
    public function admin()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {
        }
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            $list = (new Admin())
                ->field('a.id,a.username,a.nickname,sa.store_ids,GROUP_CONCAT(s.title) store_names')
                ->alias('a')
                ->join('fzdc_store_admin sa', 'a.id=sa.admin_id', 'LEFT')
                ->join('fzdc_store s', "FIND_IN_SET(s.id,sa.store_ids)", 'LEFT')
                ->order('id desc')
                ->group('a.id')
                ->select();

            $superAdminIds = AuthGroupAccess::where('group_id', 1)->column('uid');

            foreach ($list as $index => $item) {
                $item->superadmin = in_array($item['id'], $superAdminIds);
            }
            $result = array("total" => count($list), "rows" => $list);

            return json($result);
        }
        $config = get_addon_config('fzdc');
        $this->view->assign("isStoreAllocate", $config['isStoreAllocate']);
        return $this->view->fetch();
    }

    //更新授权
    public function update_auth()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {
        }
        $admin_id = $this->request->param('admin_id');
        if (!$admin_id) {
            $this->error('参数错误');
        }
        $row = (new StoreAdmin())->where('admin_id', $admin_id)->find();

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            $store_ids = $this->request->param('store_ids');
            $act = $this->request->param('act');
            if ($act == 'remove' && $row) {
                $row->store_ids = '';
                $row->save();
                $this->success('移除成功');
            }
            if (!$store_ids) {
                $this->error('参数错误');
            }
            if ($row) {
                $row->store_ids = $store_ids;
                $row->save();
            } else {
                (new StoreAdmin())->save([
                    'admin_id'  => $admin_id,
                    'store_ids' => $store_ids
                ]);
            }
            $this->success('保存成功！');
        }
        $this->assign('store_ids', !empty($row['store_ids']) ? $row['store_ids'] : '');
        return $this->view->fetch();
    }



}
