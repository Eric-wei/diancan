<?php

namespace app\admin\controller\fzdc\store;

use app\admin\controller\fzdc\Base;
use app\admin\library\Auth;
use app\common\controller\Backend;
use think\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 提现账户管理
 *
 * @icon fa fa-circle-o
 */
class Bank extends Base
{

    /**
     * Bank模型对象
     * @var \app\admin\model\fzdc\store\Bank
     */
    protected $model = null;
    protected $dataLimitField = 'store_id';
    protected $dataLimitFieldAutoFill = false;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\store\Bank;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    protected function getDataLimitAdminIds()
    {
        $auth = Auth::instance();
        if ($this->addonsConfig['isStoreAllocate'] == 1 && !$auth->isSuperAdmin()) {
            return $this->authStoreIds;
        }
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


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
			$store_id =$this->request->param("store_id",0);
			$newWhere = [];
            if ($store_id){
                $newWhere['store_id'] = $store_id;
            }
            $list = $this->model
                ->with(['store', 'user'])
                ->where($where)
				->where($newWhere)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {

                $row->getRelation('store')->visible(['title']);
                $row->getRelation('user')->visible(['username']);
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
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);

        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        //判断该门店跟用户是否关联
        $res = \app\admin\model\fzdc\store\User::get(["store_id" => $params['store_id'], "user_id" => $params['user_id']]);
        if (!$res) {
            $this->error("用户跟门店未关联");
        }
        if ($res['order_type'] != 1) {
            $this->error("用户权限不够");
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
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        //判断该门店跟用户是否关联
        $res = \app\admin\model\fzdc\store\User::get(["store_id" => $params['store_id'], "user_id" => $params['user_id']]);
        if (!$res) {
            $this->error("用户跟门店未关联");
        }
        if ($res['order_type'] != 1) {
            $this->error("用户权限不够");
        }
		if ($params['type']==1){
            $params['zfb_code'] = '';
            $params['wx_code'] = '';
            $params['zfb_img'] = '';
            $params['wx_img'] = '';
        }elseif($params['type']==2){
            $params['zfb_code'] = '';
            $params['zfb_img'] = '';
            $params['bank_code'] = '';
            $params['bank_name'] = '';
            $params['bank_type'] = '';
            $params['bank_icon'] = '';
        }else{
            $params['wx_code'] = '';
            $params['wx_img'] = '';
            $params['bank_code'] = '';
            $params['bank_name'] = '';
            $params['bank_type'] = '';
            $params['bank_icon'] = '';
        }
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
        } catch (ValidateException | PDOException | Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

}
