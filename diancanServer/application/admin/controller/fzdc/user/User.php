<?php

namespace app\admin\controller\fzdc\user;

use app\admin\model\fzdc\Userdetail;
use app\common\controller\Backend;
use app\common\library\Auth;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Log;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;
    protected $searchFields = 'id,username,nickname';

    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        $p_id = $this->request->param('p_id');

        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $newWhere = [];
            if ($p_id){
                $ids = Userdetail::where('parent_id', $p_id)->column('user_id');
                $list = $this->model
                    ->whereIn('id', $ids)
                    ->where($where)
                    ->where($newWhere)
                    ->order($sort, $order)
                    ->paginate($limit);
            }else{
                $list = $this->model
                    ->where($where)
                    ->where($newWhere)
                    ->order($sort, $order)
                    ->paginate($limit);
            }

            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
                $detail = Userdetail::get(['user_id' => $v['id']]);
                if ($detail){
                    if ($detail['parent_id']){
                        $user = $this->model->where('id', $detail['parent_id'])->find();
                        if ($user){
                            $v->p_name = $user['username'];
                        }else{
                            $v->p_name = "暂无";
                        }
                    }else{
                        $v->p_name = "暂无";
                    }
                    $v->money = $detail['proxy_amound'];

                }else{
                    $v->p_name = "暂无";
                    $v->money = 0;
                }
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
        }
        return parent::add();
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
        $detail = Userdetail::get(['user_id' => $ids]);
        if (!$detail){
            Userdetail::create([
                'user_id' => $ids,
                'createtime' => time(),
                'updatetime' => time(),
            ]);
            $detail = Userdetail::get(['user_id' => $ids]);
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $row->money = $detail['proxy_amound'];
            $row->parent_id = $detail['parent_id'];
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        if ($params['parent_id']){
            //判断是否可以设为上级
            if ($params['parent_id'] == $ids){
                $this->error("上级不能设为自己");
            }
            $parent = $this->model->get($params['parent_id']);
            if (!$parent){
                $this->error("上级用户不存在");
            }
            //查询自己有没有下级
            $child = Userdetail::where('parent_id', $ids)->column('user_id');
            if (in_array($params['parent_id'], $child)){
                $this->error("上级不能设为自己的下级");
            }
            Userdetail::where('user_id', $ids)->update(['parent_id' => $params['parent_id']]);
        }
        if ($params['money']){
            Userdetail::where('user_id', $ids)->update(['proxy_amound' => $params['money']]);
            Log::info("用户".$row['username']."原本佣金为".$detail['proxy_amound'].",修改佣金为".$params['money']);
        }
        unset($params['parent_id']);
        unset($params['money']);
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                $row->validateFailException()->validate($validate);
            }
            $result = $row->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        Auth::instance()->delete($row['id']);
        Userdetail::where('user_id', $ids)->delete();
        $this->success();
    }

}
