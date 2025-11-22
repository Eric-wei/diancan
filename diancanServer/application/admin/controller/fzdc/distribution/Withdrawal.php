<?php

namespace app\admin\controller\fzdc\distribution;

use addons\fzdc\library\WxWithdrawal;
use app\admin\model\fzdc\Userdetail;
use app\common\controller\Backend;

/**
 * 提现管理管理
 *
 * @icon fa fa-circle-o
 */
class Withdrawal extends Backend
{

    /**
     * Withdrawal模型对象
     * @var \app\admin\model\fzdc\distribution\Withdrawal
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzdc\distribution\Withdrawal;
        $this->view->assign("statusList", $this->model->getStatusList());
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
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
                ->with(['user'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {

                $row->getRelation('user')->visible(['username']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /*
     * 提现审核
     */
    public function process($ids){
        if ($this->request->isPost()){
            $status = $this->request->post("row.status");
            //如果是微信提现 调用微信提现接口
            $info = $this->model->get($ids);
            //查询openid
            $detail = Userdetail::get(["user_id"=>$info['user_id']]);
            if ($status == 2){
                //生成提现订单号
                $draw_no = "TX".fzdc_orderNo();
                $obj = new WxWithdrawal();
                $res = $obj->wx_withdrawal($detail['openid'],$draw_no,$info['amount']);
                if ((isset($res['batch_status']) && $res['batch_status'] == 'ACCEPTED')){
                    \think\Log::info("提现返回数据:".json_encode($res));
                    $this->model->update([
                        "status"   => 3,
                        "order_sn"  =>$draw_no,
//                        'batch_id' =>$res['batch_id']
                    ],["id"=>$ids]);
                    //扣除用户余额
                    $user_detail = Userdetail::get(["user_id"=>$info['user_id']]);
                    $user_detail->setDec('proxy_amound',$info['amount']);
                    $user_detail->freeze_amound = 0;
                    $user_detail->save();

                    return ["code"=>1,"msg"=>"审核成功"];
                }elseif ($res['code'] == "NOT_ENOUGH"){
                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];
                } else{
                    $this->model->update([
//                        "status"=>$this->request->post("row.status"),
                        "refuse"  =>$this->request->post("row.desc"),
                    ],["id"=>$ids]);

                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];

                }


            }else{
                $this->model->update([
                    "status"=>2,
                    "refuse"  =>$this->request->post("row.desc"),
                ],["id"=>$ids]);
                //解冻冻结佣金
                Userdetail::update(["freeze_amound"=>0,"proxy_amound"=>bcadd($detail['proxy_amound'],$detail['freeze_amound'],2)],["user_id"=>$info['user_id']]);

            }
            return ["code"=>1,"msg"=>"审核成功"];
        }
        return $this->view->fetch();
    }

}
