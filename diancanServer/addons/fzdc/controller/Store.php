<?php

namespace addons\fzdc\controller;

use app\admin\model\fzdc\goods\Goods;
use app\admin\model\fzdc\goods\SkuItem;
use app\admin\model\fzdc\goods\SkuProduct;
use app\admin\model\fzdc\goods\SkuSpec;
use app\admin\model\fzdc\goods\Type;
use app\admin\model\fzdc\order\Cart;
use app\admin\model\fzdc\order\Detail;
use app\admin\model\fzdc\SearchHistory;
use app\admin\model\fzdc\order\Order;
use app\admin\model\fzdc\store\StoreCollect;
use app\admin\model\fzdc\store\View;
use app\common\controller\Api;
use app\admin\model\fzdc\Store as St;
use fast\Http;
use think\Request;

class Store extends Api
{
    protected $noNeedLogin = ['default', 'list', 'info', 'goodsDetail', 'item', 'goodsSearch','itemPrice'];
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct(?Request $request = null)
    {
        $this->config = get_addon_config("fzdc");
        parent::__construct($request);
    }

    /*
     * 默认门店
     */
    public function default()
    {
        $user_id = $this->auth->id;
        $lon = $this->request->header("lon") ?? '113.663221';
        $lat = $this->request->header("lat") ?? '34.7568711';
        $id = $this->request->post("id") ?? '0';
        $where = [];
        if ($id) {
            //查询门店是否存在
            $res = St::get($id);
            if (!$res || $res['state'] == '0') {
                $this->error("门店不存在或状态不正确");
            }
            $where['id'] = ["=", $id];
        }
        $data = St::where('state', 1)
            ->where($where)
            ->where("deletetime", null)
            ->field("id,iscwf,cwf,title,tz_url,alias_title,address,lon,lat,`desc`,tel,image,images,start_times,end_times,subscribe_switch,delivery_switch,content,takeaway_switch,trade_switch," . fzdc_getDistanceBuilder($lat, $lon))
            ->order("distance", "asc")
            ->find();

        // 检查是否找到门店
        if (!$data) {
            $this->error("暂无可用门店，请先添加门店");
        }

        $data['is_collection'] = 0;

        $is_business = 0;
        $startTime = $data['start_times'];
        $endTime = $data['end_times'];
        $currentTime = date("H:i:s");
        if ($endTime < $startTime) {
            if ($currentTime >= $startTime || $currentTime < $endTime) {
                $is_business = 1;
            }
        } else {
            if ($currentTime >= $startTime && $currentTime < $endTime) {
                $is_business = 1;
            }
        }

        $data['is_business'] = $is_business;
        if (!$data['trade_switch']) {
            $data['is_business'] = 0;
        }


        if (StoreCollect::where(["user_id" => $user_id, "store_id" => $data->id])->find()) {
            $data['is_collection'] = 1;
        }
        $images = explode(',', $data['images']);
        foreach ($images as &$v) {
            if ($v){
                $v = cdnurl($v, true);
            }
        }
        $data['images'] = $images;
        //计算可自取时间
        $arr = [];
        $i = 0;

        // 处理跨天结束时间（当结束时间小于当前时间自动视为次日）
        $end_time_str = date('Y-m-d') . ' ' . $data['end_times'];
        $end_timestamp = strtotime($end_time_str);
        if ($end_timestamp <= time()) {
            $end_timestamp = strtotime('+1 day', $end_timestamp);
        }

        while (true) {
            $current_timestamp = time() + ($i + 1) * 900;

            // 直接与结束时间戳比较
            if ($current_timestamp > $end_timestamp) {
                // 添加精确的结束时间点
                $arr[] = date("H:i", $end_timestamp);
                break;
            }

            // 正常添加间隔时间点
            $arr[] = date("H:i", $current_timestamp);

            $i++;
        }

        $data['time'] = $arr;
        //增加访问数
        if ($user_id) {
            $re = View::get(["user_id" => $user_id, "store_id" => $data['id']]);
            if ($re) {
                $re->updatetime = time();
                $re->save();
            } else {
                View::create([
                    "user_id" => $user_id,
                    "store_id" => $data['id'],
                    "createtime" => time(),
                    "updatetime" => time(),
                ]);
            }
        }


        $this->success('返回成功', $data);
    }


    /*
     * 门店列表
     */
    public function list()
    {
        $user_id = $this->auth->id;
        $lon = $this->request->header("lon") ?? '113.663221';
        $lat = $this->request->header("lat") ?? '34.7568711';
        $page = $this->request->post('page') ?? 1;  //页码
        $limit = $this->request->post('limit') ?? 10; //每页显示条数
        $search = $this->request->post('search');
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?location=' . $lat . ',' . $lon . '&key=' . $this->config['tenmap_key'] . '&get_poi=0';
        $info = Http::get($url);        //请求API接口，请求方式为GET
        $info = json_decode($info, true);        //返回数据转码为JSON

        $info = isset($info['result']['formatted_addresses']['recommend'])? $info['result']['formatted_addresses']['recommend'] : '';
        $where = [];
        if ($search) {
            $where['title'] = array('like', '%' . $search . '%');
        }
        $data = St::where('state', 1)
            ->where("deletetime", null)
            ->where($where)
            ->field("id,iscwf,cwf,title,alias_title,address,lon,lat,`desc`,tel,image,images,start_times,end_times,subscribe_switch,delivery_switch,takeaway_switch,trade_switch," . fzdc_getDistanceBuilder($lat, $lon))
            ->order("distance", "asc")
            ->paginate(["page" => $page, "list_rows" => $limit])
            ->each(function ($item) use ($user_id) {
                $item->is_collection = 0;
                if (StoreCollect::where(["user_id" => $user_id, "store_id" => $item->id])->find()) {
                    $item->is_collection = 1;
                }

                $item->is_business = 0;
                $startTime = $item->start_times;
                $endTime = $item->end_times;
                $currentTime = date("H:i:s");
                if ($endTime < $startTime) {
                    if ($currentTime >= $startTime || $currentTime < $endTime) {
                        $item->is_business = 1;
                    }
                } else {
                    if ($currentTime >= $startTime && $currentTime < $endTime) {
                        $item->is_business = 1;
                    }
                }
                if (!$item->trade_switch) {
                    $item->is_business = 0;
                }
            });

        $result = array("total" => $data->total(), "rows" => $data->items(), "address" => $info);
        $this->success('返回成功', $result);
    }

    /*
     * 收藏的门店列表
     */
    public function collectionList()
    {
        $user_id = $this->auth->id;
        $lon = $this->request->header("lon") ?? '113.663221';
        $lat = $this->request->header("lat") ?? '34.7568711';
        $page = $this->request->post('page') ?? 1;  //页码
        $limit = $this->request->post('limit') ?? 10; //每页显示条数
        $search = $this->request->post('search');
        $ids = StoreCollect::where("user_id", $user_id)->column("store_id");
        if (empty($ids)) {
            $this->error("暂无收藏的门店");
        }
        $where = [];
        if ($search) {
            $where['title'] = array('like', '%' . $search . '%');
        }

        $data = St::where('state', 1)
            ->where("deletetime", null)
            ->where("id", "in", $ids)
            ->where($where)
            ->field("id,title,alias_title,address,lon,lat,`desc`,tel,image,images,start_times,end_times,subscribe_switch,delivery_switch,takeaway_switch,trade_switch," . fzdc_getDistanceBuilder($lat, $lon))
            ->order("distance", "asc")
            ->paginate(["page" => $page, "list_rows" => $limit])
            ->each(function ($item) {
                $item->is_business = 0;
                $startTime = $item->start_times;
                $endTime = $item->end_times;
                $currentTime = date("H:i:s");
                if ($endTime < $startTime) {
                    if ($currentTime >= $startTime || $currentTime < $endTime) {
                        $item->is_business = 1;
                    }
                } else {
                    if ($currentTime >= $startTime && $currentTime < $endTime) {
                        $item->is_business = 1;
                    }
                }
                if (!$item->trade_switch){
                    $item->is_business = 0;
                }
            });
        $result = array("total" => $data->total(), "rows" => $data->items());
        $this->success('返回成功', $result);
    }

    /*
     * 收藏门店
     */
    public function collection()
    {
        $user_id = $this->auth->id;
        $store_id = $this->request->post("store_id");
        if (!$store_id) {
            $this->error("缺少参数");
        }
        $res = St::get($store_id);
        if (!$res) {
            $this->error("门店不存在");
        }
        //判断是否已收藏
        $res = StoreCollect::get(["user_id" => $user_id, "store_id" => $store_id]);
        if ($res) {
            $this->error("已收藏,不需要重复收藏");
        }
        StoreCollect::create([
            "user_id" => $user_id,
            "store_id" => $store_id,
            "createtime" => time()
        ]);
        $this->success('成功');
    }

    /*
     * 取消收藏
     */
    public function cancelColl()
    {
        $user_id = $this->auth->id;
        $store_id = $this->request->post("store_id");
        if (!$store_id) {
            $this->error("缺少参数");
        }
        $res = St::get($store_id);
        if (!$res) {
            $this->error("门店不存在");
        }
        //判断是否已收藏
        $res = StoreCollect::get(["user_id" => $user_id, "store_id" => $store_id]);
        if (!$res) {
            $this->error("没有收藏此门店");
        }
        StoreCollect::destroy([
            "user_id" => $user_id,
            "store_id" => $store_id,
        ]);
        $this->success('成功');
    }

    /*
     * 获取门店下方的分类及商品
     */
    public function info()
    {
        $user_id = $this->auth->id;
        $store_id = $this->request->post("store_id");                             //门店id
        $shipment = $this->request->post("shipment") ?? 1;                          //出货类型
        $productivity_order = $this->request->post("productivity_order") ?? "desc";  //销量排序
        if (!$store_id) {
            $this->error("缺少参数");
        }
        $res = St::get($store_id);
        if (!$res) {
            $this->error("门店不存在");
        }
        $where = [];
        if (!in_array($shipment, [1, 2, 3])) {
            $this->error("出货类型不正确");
        }
        $where['shipment'] = ["in", [intval($shipment), 3]];

        $arr = [];
        //查询门店下方分类
        Type::where("state", 1)->where("deletetime", null)->field("id,name,switch,store_ids,icon")->order("weigh", "desc")->select()->each(function ($item) use ($store_id, &$arr) {
            if ($item['icon']){
                $item['icon'] = cdnurl($item['icon'], true);
            }
            if ($item->switch == 1) {
                $arr[] = $item->toArray();
            } else {
                $ids = explode(',', $item->store_ids);

                foreach ($ids as $v) {
                    if ($v == $store_id) {
                        $arr[] = $item->toArray();
                    }
                }
            }
        });
        //查询分类下方的商品
        foreach ($arr as &$value) {
            $desc = [];
            Goods::where(["type_id" => $value['id'], "state" => 1])->where($where)->where("deletetime", null)->order("weigh","desc")->field("id,title,tags,switch,store_ids,image,type,price,line_price,desc")->select()->each(function ($item) use ($store_id, &$desc) {
                $item->image = cdnurl($item->image, true);
                //商品标签
                if ($item->tags != "") {
                    $tags = explode(",", $item->tags);
                    $item->tags = $tags;
                }
                $item->sku_count = SkuItem::where(["goods_id" => $item->id])->count();
                //商品最低价格
                if ($item->sku_count){
                    $sku =  SkuProduct::where(["goods_id" => $item->id])->order("price asc")->find();
                    $item->minprice = $sku->price;
                    $item->line_price = $sku->market_price;
                }else{
                    $item->minprice = $item->price;
                }
                //商品月售单数
                $count = Detail::with(['orders'])->where(["orders.status" => 3])->where("detail.goods_id",$item->id)->whereTime("detail.createtime",'m')->sum('detail.goods_count');
                $item->count = bcadd($this->config['foundation'], $count);
                if ($item->switch == 1) {
                    $desc[] = $item->toArray();
                } else {
                    $ids = explode(',', $item->store_ids);

                    foreach ($ids as $v) {
                        if ($v == $store_id) {
                            $desc[] = $item->toArray();
                        }
                    }
                }
            });

            $value['goods'] = $desc;
        }
        $this->success("成功", $arr);
    }

    /*
     * 商品详情
     */
    public function goodsDetail()
    {
        $goods_id = $this->request->post("goods_id"); //商品id
        $data = Goods::where(["id" => $goods_id, "state" => 1])->field("id,title,images,type,json,price,switch,store_ids")->find();
        $data['image'] = cdnurl($data['image'], true);
        if (empty($data)) {
            $this->error("数据不存在");
        }
        //商品最低价格
        $data['sku_count'] = SkuItem::where(["goods_id" => $data['id']])->count();
        if ($data['sku_count']){
            $data['minprice'] = SkuProduct::where(["goods_id" => $data['id']])->min("price");
        }else{
            $data['minprice'] = $data['price'];
        }
        //商品月售单数
        $count = Detail::with(['orders'])->where(["orders.status" => 3])->where("detail.goods_id", $goods_id)->whereTime("detail.createtime",'m')->sum('detail.goods_count');
        $data['count'] = bcadd($this->config['foundation'], $count);
        $images = explode(',', $data['images']);
        foreach ($images as &$v) {
            $v = cdnurl($v, true);
        }
        $data['images'] = $images;
        if ($data['switch']) {
            $store = \app\admin\model\fzdc\store\Store::all();
            foreach ($store as $sv) {
                \app\admin\model\fzdc\store\Store::where(["id" => $sv['id']])->update(["cleck_goods_counts" => $sv['cleck_goods_counts'] + 1]);
            }
        } else {
            if (!empty($data['store_ids'])) {
                $ids = explode(',', $data['store_ids']);
                foreach ($ids as $iv) {
                    $store = \app\admin\model\fzdc\store\Store::get($iv);
                    \app\admin\model\fzdc\store\Store::where(["id" => $iv])->update(["cleck_goods_counts" => $store['cleck_goods_counts'] + 1]);
                }
            }
        }

        $this->success("成功", $data);
    }

    /*
     * 门店商品搜索接口
     */
    public function goodsSearch()
    {
        $user_id = $this->auth->id ?? 0;
        $store_id = $this->request->post("store_id");                             //门店id
        $search = $this->request->post("search");                               //搜索条件
        $type = $this->request->post("type") ?? 1;                               //出餐类型
        $productivity_order = $this->request->post("productivity_order") ?? "desc";  //销量排序

        if (!in_array($type, [1, 2, 3])) {
            $this->error("出货类型不正确");
            $where['shipment'] = ["in", [$type, 3]];
        }
        $where = [];
        if ($search) {
            $where['title'] = array('like', '%' . $search . '%');
        }
        $desc = [];
        Goods::where(["state" => 1])->where($where)->where("deletetime", null)->order("weigh","desc")->field("id,tags,type,price,title,image,switch,store_ids,desc,line_price")->select()->each(function ($item) use ($store_id, &$desc) {
            $item->image = cdnurl($item->image, true);
            //商品月售单数
            $count = Detail::with(['orders'])->where(["orders.status" => 3])->where("detail.goods_id",$item->id)->whereTime("detail.createtime",'m')->sum('detail.goods_count');
            $item->count = bcadd($this->config['foundation'], $count);
            //商品标签
            if ($item->tags != null) {
                $item->tags = explode(",", $item->tags);
            }
            //商品最低价格
            $item->sku_count = SkuItem::where(["goods_id" => $item->id])->count();
            //商品最低价格
            if ($item->sku_count){
                $sku =  SkuProduct::where(["goods_id" => $item->id])->order("price asc")->find();
                $item->minprice = $sku->price;
                $item->line_price = $sku->market_price;
            }else{
                $item->minprice = $item->price;
            }
            if ($item->switch == 1) {
                $desc[] = $item->toArray();
            } else {
                $ids = explode(',', $item->store_ids);

                foreach ($ids as $v) {
                    if ($v == $store_id) {
                        $desc[] = $item->toArray();
                    }
                }
            }

        });
        if (count($desc) > 1) {
            if ($productivity_order == "asc") {
                array_multisort(array_column($desc, 'count'), SORT_ASC, $desc);
            } else {
                array_multisort(array_column($desc, 'count'), SORT_DESC, $desc);
            }
        }

        for ($i = 0; $i < 3; $i++) {
            if (isset($desc[$i])) {
                $desc[$i]['xl_desc'] = "门店销量第" . bcadd($i, 1) . "名";

            }
        }
        if ($user_id) {
            //查询是否有搜索历史
            $res = SearchHistory::get(["user_id" => $user_id, "title" => $search]);
            if ($res) {
                $res->setInc("number");
                $res->save(["updatetime" => time()]);
            } else {
                //保存搜索历史
                SearchHistory::create([
                    "user_id" => $user_id,
                    "title" => $search,
                    "number" => 1,
                    "createtime" => time(),
                    "updatetime" => time(),
                ]);
            }
        }
        $this->success("成功", $desc);
    }

    /*
     * 搜索历史列表
     */
    public function history()
    {
        $user_id = $this->auth->id ?? 0;
        $data = [];
        if ($user_id) {
            $data = SearchHistory::where("user_id", $user_id)->order("updatetime", "desc")->field("id,title")->select();
        }
        $this->success("成功", $data);
    }

    /*
     * 删除搜索历史列表
     */
    public function delHistory()
    {
        $user_id = $this->auth->id ?? 0;
        SearchHistory::where("user_id", "=", $user_id)->delete();
        $this->success("成功");
    }

    public function hotSearch()
    {
        $config = $this->config['hot_search'];
        $this->success("成功", explode(',', $config));
    }

    /*
     * 获取商品规格接口
     */
    public function item()
    {
        $goods_id = $this->request->post("goods_id");     //商品id
        //查询商品是否存在
        $res = Goods::get($goods_id);
        if (!$res) {
            $this->error("商品不存在");
        }
        //获取规格
        $data = SkuSpec::where(["goods_id" => $goods_id])->field("id,name")->select()->each(function ($item) {
            $item->item = SkuItem::where(["spec_id" => $item->id])->field("id,name")->select();
        });
        $this->success("成功", $data);

    }

    /*
     * 选择规格获取商品价格
     */
    public function itemPrice()
    {
        $goods_id = $this->request->post("goods_id");     //商品id
        $count = $this->request->post("count")??1;     //商品数量
        $spu_id = $this->request->post("spu_id");     //spuid
        $spu_info = $this->request->post("spu_info/a")?? [];     //spu_info 单规格传
        if (!$goods_id){
            $this->error("商品id不能为空");
        }

        $goods = Goods::get($goods_id);
        if (!$goods) {
            $this->error("商品不存在");
        }
        if ($goods['type'] == 1){

            $price = 0;
            if (!empty($spu_info)){
                foreach ($spu_info as $k => $v) {
                    if ($v['count']<=0){
                        continue;
                    }
                    $res = SkuProduct::where(["goods_id" => $goods_id, "key" => $v['spu_id']])->find();
                    if ($res) {
                        $price = bcadd($price,bcmul($res['price'],$v['count'],2),2);
                    }else{
                        $this->error("商品规格不存在");
                    }
                }
            }

            $this->success("成功", ["price" => bcmul($price,$count,2)]);
        }else{
            if (!$spu_id) {
                $this->error("spuid不能为空");
            }
            $res = SkuProduct::where(["goods_id" => $goods_id, "key" => $spu_id])->find();
            if ($res) {
                $this->success("成功", ["price" => bcmul($res['price'],$count,2)]);
            } else {
                $key = explode("_", $spu_id);
                $price = "";
                SkuProduct::where(["goods_id" => $goods_id])->select()->each(function ($item) use ($key, &$price) {
                    //匹配一个字符串中是否含有多个字符串
                    preg_match_all('#(' . implode('|', $key) . ')#', $item->key, $wordsFound);
                    $wordsFound = array_unique($wordsFound[0]);
                    $count = count(explode("_", $item->key));
                    if (count($wordsFound) == $count) {
                        $price = $item->price;
                    }
                });
                $res['price'] = bcmul($price, $count, 2);
            }
            $this->success("成功", $res);
        }

    }


}
