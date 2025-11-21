<?php

namespace app\admin\controller\fzdc\store;

use addons\fzdc\library\Wechat;
use app\admin\controller\fzdc\Base;
use app\admin\library\Auth;
use app\admin\model\fzdc\order\Order;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 门店桌号管理
 *
 * @icon fa fa-circle-o
 */
class Sear extends Base
{

    /**
     * Sear模型对象
     * @var \app\admin\model\fzdc\store\Sear
     */
    protected $model = null;
    protected $dataLimitField = 'store_id';
    protected $dataLimitFieldAutoFill = false;
    protected $searchFields = 'id,title,store.title';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\store\Sear;
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
                ->with(['store','seart'])
				->where($newWhere)
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {

                $row->getRelation('store')->visible(['title']);
                $row->getRelation('seart')->visible(['title']);
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
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        if ($params['use_status'] == 1){
            //判断有没有员工点餐的订单
            $order = Order::where("FIND_IN_SET('{$ids}', seat_id)")->where(['dc_type' => 2, 'status' => 1])->find();
            if ($order){
                Order::where("FIND_IN_SET('{$ids}', seat_id)")->where("dc_type",2)->update(['status' => 3]);
            }

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
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

    /*
     * 生成二维码
     */
    public function generate($ids)
    {
        $type = $this->request->request('type');
        $sear = \app\admin\model\fzdc\store\Sear::get($ids);
        $wechat = new Wechat('wxMiniProgram');

        if ($type == 1){
            //先付后餐
            if (!$sear->image){
                $row = [
                    "release"=>$this->appimg($wechat,$sear,'release',$type),
                    "trial"=>$this->appimg($wechat,$sear,'trial',$type),
                    "develop"=>$this->appimg($wechat,$sear,'develop',$type),
                ];

                $sear->image = json_encode($row,true);
                $sear->save();
            }else{
                $row = json_decode($sear->image,true);
            }
        }else{
            //先餐后付
            if (!$sear->image2){
                $row = [
                    "release"=>$this->appimg($wechat,$sear,'release',$type),
                    "trial"=>$this->appimg($wechat,$sear,'trial',$type),
                    "develop"=>$this->appimg($wechat,$sear,'develop',$type),
                ];

                $sear->image2 = json_encode($row,true);
                $sear->save();
            }else{
                $row = json_decode($sear->image2,true);
            }
        }



        $this->assign('row', $row);
        $this->assign('id', $ids);
        return $this->fetch();
    }

    private function appimg($wechat,$sear,$env_version,$type){
//        var_dump($type);die;
        try {
            $content = $wechat->getApp()->app_code->getUnlimit($sear['id'].'_'. $type, [
                'page' => 'pages/order/order',
                'env_version'=>$env_version,
                'check_path'=>false,
            ]);
            // 生成小程序码到本地
            $fileName = uniqid(). '.png';
            $savePath = ROOT_PATH . 'public/uploads/' . 'fzdc/';
            if (!file_exists($savePath)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($savePath, 0777, true);
            }
            //判断方法是否存在
            if (!method_exists($content, 'getBody')) {
                throw new \Exception('获取小程序码失败');
            }
            // 保存到文件
            file_put_contents($savePath . $fileName, $content->getBody());

            return "/uploads/fzdc/" . $fileName;
        }catch (\Exception $e){
//            var_dump($e->getMessage());die;
            return "";
        }

    }

    public function download($ids)
    {
        // 首先确保$type的合法性
        $type = $this->request->request('type');

        // 对$ids进行必要的验证，以防止恶意输入
        if (!is_array($ids)) {
            // 处理非数组输入的情况，如抛出异常或返回错误信息
        }

        if($ids !='all'){
            $sear = \app\admin\model\fzdc\store\Sear::whereIn("id", $ids)->select();
        }else{
            $sear =  \app\admin\model\fzdc\store\Sear::select();
        }
        // 根据业务需求，可能需要对$ids中的每个元素进行进一步的验证和过滤

        $arr = [];
        if ($type == 1) {
            foreach ($sear as $row) {
                $arr[$row['id']] = json_decode($row['image'], true);
            }
        } else {
            foreach ($sear as $row) {
                $arr[$row['id']] = json_decode($row['image2'], true);
            }
        }

        // 检查Zip文件夹是否存在，不存在则创建
        $zipFolder = ROOT_PATH . 'public/uploads/' . 'fzdc/';
        if (!file_exists($zipFolder)) {
            mkdir($zipFolder, 0777, true);
        }

        // 下载成zip
        $zipname = 'fzdc_' . date('YmdHis'). '_' . rand(1000, 9999) . '.zip';
        $zipPath = $zipFolder . $zipname;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            // 处理zip文件打开失败的情况，如抛出异常或返回错误信息
        }
        foreach ($arr as $k => $row) {
            if ($row){
                foreach ($row as $key => $value) {
                    if ($value){
                        $filePath = ROOT_PATH . 'public' . $value;
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, $k. '_'. $key . '_' . basename($value));
                        } else {
                            continue;
                        }
                    }

                }
            }

        }
        $zip->close(); // 关闭Zip文件
        // 检查Zip文件是否创建成功，成功则输出到浏览器
        if (file_exists($zipPath)) {
            // 输出Zip文件到浏览器

            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=". $zipname);
            header("Content-Length: ". filesize($zipPath));
            readfile($zipPath);
        } else {
            // 处理Zip文件创建失败的情况，如记录日志或返回错误信息
            $this->error('下载失败');
        }
    }




}
