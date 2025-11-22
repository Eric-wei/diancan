import
request
from "../utils/request.js"

// 轮播图
export function index_list(data) {
	return request({
		url: 'index/list',
		method: 'POST',
		data
	})
}
// 轮播图
export function orderCancel(data) {
	return request({
		url: 'order/cancel',
		method: 'POST',
		data
	})
}
// 我的优惠券列表
export function coupon_list(data) {
	return request({
		url: 'coupon/list',
		method: 'POST',
		data
	})
}
// 获取可用优惠券
export function coupon_usable(data) {
	return request({
		url: 'coupon/usable',
		method: 'POST',
		data
	})
}
// 获取可领取优惠券列表
export function coupon_couponList(data) {
	return request({
		url: 'coupon/couponList',
		method: 'POST',
		data
	})
}
// 领取优惠券
export function coupon_draw(data) {
	return request({
		url: 'coupon/draw',
		method: 'POST',
		data
	})
}
// 默认门店
export function store_default(data) {
	return request({
		url: 'store/default',
		method: 'POST',
		data
	})
}
// 收藏门店
export function store_collection(data) {
	return request({
		url: 'store/collection',
		method: 'POST',
		data
	})
}
// 取消收藏门店
export function store_cancelColl(data) {
	return request({
		url: 'store/cancelColl',
		method: 'POST',
		data
	})
}
// 选择规格获取商品价格
export function store_itemPrice(data) {
	return request({
		url: 'store/itemPrice',
		method: 'POST',
		data
	})
}
// 获取商品规格
export function store_item(data) {
	return request({
		url: 'store/item',
		method: 'POST',
		data
	})
}
// 门店商品搜索接口
export function store_goodsSearch(data) {
	return request({
		url: 'store/goodsSearch',
		method: 'POST',
		data
	})
}
// 门店下的分类及商品
export function store_info(data) {
	return request({
		url: 'store/info',
		method: 'POST',
		data
	})
}

// 搜索历史列表
export function store_history(data) {
	return request({
		url: 'store/history',
		method: 'POST',
		data
	})
}
// 删除搜索历史
export function store_delHistory(data) {
	return request({
		url: 'store/delHistory',
		method: 'POST',
		data
	})
}
// 热门搜索列表
export function store_hotSearch(data) {
	return request({
		url: 'store/hotSearch',
		method: 'POST',
		data
	})
}
//门店列表
export function store_list(data) {
	return request({
		url: 'store/list',
		method: 'POST',
		data
	})
}
//收藏的门店列表
export function store_collectionList(data) {
	return request({
		url: 'store/collectionList',
		method: 'POST',
		data
	})
}

//商品详情
export function store_goodsDetail(data) {
	return request({
		url: 'store/goodsDetail',
		method: 'POST',
		data
	})
}
//餐具数量
export function order_tableware(data) {
	return request({
		url: 'order/tableware',
		method: 'POST',
		data
	})
}

//购物车列表
export function order_carList(data) {
	return request({
		url: 'order/carList',
		method: 'POST',
		data
	})
}
//添加购物车
export function order_joinCar(data) {
	return request({
		url: 'order/joinCar',
		method: 'POST',
		data
	})
}
//减少
export function order_reduce(data) {
	return request({
		url: 'order/reduce',
		method: 'POST',
		data
	})
}
//清空购物车
export function order_empty(data) {
	return request({
		url: 'order/empty',
		method: 'POST',
		data
	})
}


//收货地址列表
export function address_list(data) {
	return request({
		url: 'address/list',
		method: 'POST',
		data
	})
}
//添加收货地址
export function address_add(data) {
	return request({
		url: 'address/add',
		method: 'POST',
		data
	})
}
//修改收货地址
export function address_exec(data) {
	return request({
		url: 'address/exec',
		method: 'POST',
		data
	})
}
//删除收货地址
export function address_del(data) {
	return request({
		url: 'address/del',
		method: 'POST',
		data
	})
}
//获取用户默认收货地址
export function getDefaultAddress(data) {
	return request({
		url: 'address/getDefaultAddress',
		method: 'POST',
		data
	})
}
//创建订单
export function order_create(data) {
	return request({
		url: 'order/create',
		method: 'POST',
		data
	})
}
//发起支付
export function order_pay(data) {
	return request({
		url: 'order/pay',
		method: 'POST',
		data
	})
}



//获取积分商品分类
export function point_type(data) {
	return request({
		url: 'point/type',
		method: 'POST',
		data
	})
}
//积分商品列表
export function point_list(data) {
	return request({
		url: 'point/list',
		method: 'POST',
		data
	})
}
//积分商品详情
export function point_detail(data) {
	return request({
		url: 'point/detail',
		method: 'POST',
		data
	})
}
//积分商品兑换
export function point_exchange(data) {
	return request({
		url: 'point/exchange',
		method: 'POST',
		data
	})
}


//积分商品兑换记录
export function point_take(data) {
	return request({
		url: 'point/take',
		method: 'POST',
		data
	})
}
//获取积分规则
export function point_rule(data) {
	return request({
		url: 'point/rule',
		method: 'POST',
		data
	})
}
//获取积分明细
export function point_detailed(data) {
	return request({
		url: 'point/detailed',
		method: 'POST',
		data
	})
}

//订单 自提/外卖
export function order_order(data) {
	return request({
		url: 'order/list',
		method: 'POST',
		data
	})
}
//订单详情
export function order_detail(data) {
	return request({
		url: 'order/detail',
		method: 'POST',
		data
	})
}

//取消订单
export function order_cancel(data) {
	return request({
		url: 'order/cancel',
		method: 'POST',
		data
	})
}
//确认收货
export function confirm(data) {
	return request({
		url: 'order/confirm',
		method: 'POST',
		data
	})
}

//获取桌号信息
export function seat(data) {
	return request({
		url: 'order/seat',
		method: 'POST',
		data
	})
}

//获取支付优惠
export function recharge(data) {
	return request({
		url: 'order/recharge',
		method: 'POST',
		data
	})
}


//桌号分类及列表
export function seat_list(data) {
	return request({
		url: 'order/seat_list',
		method: 'POST',
		data
	})
}

//预约桌号
export function reservation(data) {
	return request({
		url: 'order/reservation',
		method: 'POST',
		data
	})
}


//取消预约
export function cancel_reservation(data) {
	return request({
		url: 'order/cancel_reservation',
		method: 'POST',
		data
	})
}


//取消预约
export function jiac(data) {
	return request({
		url: 'order/jiac',
		method: 'POST',
		data
	})
}


//获取当前订单
export function orderCurrent(data) {
	return request({
		url: 'order/current',
		method: 'POST',
		data
	})
}


//修改购物车规格接口
export function changeSpec(data) {
	return request({
		url: 'order/changeSpec',
		method: 'POST',
		data
	})
}


//消费记录 不用传参
export function recharges(data) {
	return request({
		url: 'order/recharges',
		method: 'POST',
		data
	})
}


//意见反馈
export function feedbackadd(data) {
	return request({
		url: 'user/feedback',
		method: 'POST',
		data
	})
}