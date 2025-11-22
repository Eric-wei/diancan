<?php

namespace app\admin\controller\fzdc;

use app\common\controller\Backend;
use think\Cache;

/**
 * Ajax
 *
 * @icon fa fa-circle-o
 * @internal
 */
class Ajax extends Backend
{

    /**
     * 模型对象
     */
    protected $model = null;
    protected $noNeedRight = ['*'];


    public function config()
    {
        $name = $this->request->get('name');
        if ($name == 'sms') {
            $config = config('addons.hooks');
            if (isset($config['sms_send']) && $config['sms_send']) {
                $name = reset($config['sms_send']);
            } else {
                $this->error("请在插件管理中安装一款短信验证插件", "");
            }
        } elseif ($name == 'oss') {
            $config = config('addons.hooks');
            if (isset($config['upload_config_init']) && $config['upload_config_init']) {
                $availableArr = array_intersect($config['upload_config_init'], ['alioss', 'bos', 'cos', 'upyun', 'ucloud', 'hwobs', 'qiniu']);
                if ($availableArr) {
                    $name = reset($availableArr);
                }
            }
            if (!$name || $name == 'oss') {
                $this->error("请在插件管理中安装一款云存储插件", "");
            }
        } else {
            $info = get_addon_info($name);
            $addonArr = [
                'third'  => '第三方登录',
                'signin' => '会员签到',
                'epay'   => '微信支付宝整合',
            ];
            if (!$info) {
                $this->error('请检查对应插件' . (isset($addonArr[$name]) ? "《{$addonArr[$name]}》" : "") . '是否安装且启用', "");
            }
        }
        $this->redirect('addon/config?name=' . $name . '&dialog=1');
    }

    /**
     * 清除缓存
     */
    public function clearcache()
    {
        Cache::clear("fzmh");
        $config = get_addon_config('fzmh');
        @rmdirs(TEMP_PATH, false);
        $this->success("");
    }

    public function get_page_list()
    {
        $pageList = [
            ['path' => '/pages/index/index', 'name' => '首页'],
            ['path' => '/pages/me/me', 'name' => '我的'],
        ];
        if ($this->request->isAjax()) {
            $search = $this->request->get('search', '');
            $offset = $this->request->get('offset', 0);
            $limit = $this->request->get('limit', 10);
            if ($search) {
                foreach ($pageList as $index => $item) {
                    if (stripos($item['path'], $search) === false && stripos($item['name'], $search) === false) {
                        unset($pageList[$index]);
                    }
                }
                $pageList = array_values($pageList);
            }
            $result = array("total" => count($pageList), "rows" => array_slice($pageList, $offset, $limit));

            return json($result);
        }
        $this->view->assign('pageList', $pageList);
        return $this->view->fetch('fzdc/common/pages');
    }
}
