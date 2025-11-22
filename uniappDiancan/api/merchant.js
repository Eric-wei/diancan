import request from '@/utils/request.js'
//获取店铺资料
export function store_info(data) {
	return request({
		url: 'business/store_info',
		method: 'POST',
		data
	})
}

//店铺认证
export function store_authentication(data) {
	return request({
		url: 'business/store_authentication',
		method: 'POST',
		data
	})
}

//修改店铺资料
export function store_update(data) {
	return request({
		url: 'business/store_update',
		method: 'POST',
		data
	})
}

//订单列表
export function order_list(data) {
	return request({
		url: 'business/order_list',
		method: 'POST',
		data
	})
}

//订单详情
export function order_detail(data) {
	return request({
		url: 'business/order_detail',
		method: 'POST',
		data
	})
}


//接单
export function receiving_orders(data) {
	return request({
		url: 'business/receiving_orders',
		method: 'POST',
		data
	})
}



//修改店铺营业状态
export function trade_switchs(data) {
	return request({
		url: 'business/trade_switch',
		method: 'POST',
		data
	})
}

//获取提现账号列表
export function withdrawal_account_list(data) {
	return request({
		url: 'business/withdrawal_account_list',
		method: 'POST',
		data
	})
}


//获取提现账号列表
export function withdrawal(data) {
	return request({
		url: 'business/withdrawal',
		method: 'POST',
		data
	})
}

//添加提现账号
export function add_withdrawal_account(data) {
	return request({
		url: 'business/add_withdrawal_account',
		method: 'POST',
		data
	})
}


//获取提现明细
export function withdrawal_detail(data) {
	return request({
		url: 'business/withdrawal_detail',
		method: 'POST',
		data
	})
}

//确认送出（已接单的订单可以送出）
export function send_out(data) {
	return request({
		url: 'business/send_out',
		method: 'POST',
		data
	})
}


//收入支出明细
export function revenue(data) {
	return request({
		url: 'business/revenue',
		method: 'POST',
		data
	})
}

//打印小票
export function print(data) {
	return request({
		url: 'business/print',
		method: 'POST',
		data
	})
}

//退款(已完成的订单可以发起退款)
export function refund(data) {
	return request({
		url: 'business/refund',
		method: 'POST',
		data
	})
}


//拒绝退款(已完成的订单可以发起退款)
export function resold_order(data) {
	return request({
		url: 'business/resold_order',
		method: 'POST',
		data
	})
}


//解绑提现账号
export function del_withdrawal_account(data) {
	return request({
		url: 'business/del_withdrawal_account',
		method: 'POST',
		data
	})
}


//提现账号详情
export function withdrawal_account_detail(data) {
	return request({
		url: 'business/withdrawal_account_detail',
		method: 'POST',
		data
	})
}

//加菜接口(先添加购物车 然后加菜)
export function jiac(data) {
	return request({
		url: 'order/jiac',
		method: 'POST',
		data
	})
}


//商家加减菜品
export function add_reduce_menu(data) {
	return request({
		url: 'business/add_reduce_menu',
		method: 'POST',
		data
	})
}


//
export function over(data) {
	return request({
		url: 'business/over',
		method: 'POST',
		data
	})
}

//门店扫用户码支付
export function pay_code(data) {
	return request({
		url: 'order/pay_code',
		method: 'POST',
		data
	})
}

//现金支付
export function cash_payment(data) {
	return request({
			url: 'business/cash_payment',
			method: 'POST',
			data
	})
}
//释放桌号
export function release_desk(data) {
	return request({
			url: 'business/release_desk',
			method: 'POST',
			data
	})
}
//完成预约
export function complete_appointment(data) {
	return request({
			url: 'business/complete_appointment',
			method: 'POST',
			data
	})
}