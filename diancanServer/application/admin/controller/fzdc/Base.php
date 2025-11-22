<?php

namespace app\admin\controller\fzdc;

use app\common\controller\Backend;

/**
 * Ajax
 *
 * @icon fa fa-circle-o
 * @internal
 */
class Base extends Backend
{

    protected $authHouseIds = null;
    protected $authStoreIds = null;
    protected $addonsConfig = null;

    protected $dataLimit = 'auth';

    public function _initialize()
    {
        parent::_initialize();

        $this->addonsConfig = get_addon_config('fzdc');

        $this->dataLimit = $this->addonsConfig['authModel'];

        if ($this->addonsConfig['isStoreAllocate']==1) {

            $adminIds = parent::getDataLimitAdminIds();

            $storeAdminList = (new \app\admin\model\fzdc\store\StoreAdmin())
                ->field("sa.id,sa.store_ids,GROUP_CONCAT(s.id) seat_ids")
                ->alias('sa')
                ->join('fzdc_seat s', "FIND_IN_SET(s.store_id,sa.store_ids)", 'LEFT')
                ->where('admin_id', 'in', $adminIds)
                ->group('sa.id')
                ->select();

            $storeIds = [];
            $seatIds = [];

            foreach ($storeAdminList as $res) {
                $storeIds = array_merge($storeIds, explode(',', $res['store_ids']));
                $seatIds = array_merge($seatIds, explode(',', $res['seat_ids']));
            }
            

            $this->authStoreIds = array_filter(array_unique($storeIds));
            $this->authHouseIds = array_filter(array_unique($seatIds));
        }
    }
}
