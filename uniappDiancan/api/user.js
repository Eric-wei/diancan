import
request
from "../utils/request.js"


// 发送验证码
export function send(data) {
	return request({
		url: 'sms/send',
		method: 'POST',
		data
	})
}
// 检测手机验证码
export function check(data) {
	return request({
		url: 'sms/check',
		method: 'POST',
		data
	})
}
// 注册/登录接口
export function login(data) {
	return request({
		url: 'user/login',
		method: 'POST',
		data
	})
}
// 绑定微信账号
export function binding(data) {
	return request({
		url: 'suser/binding',
		method: 'POST',
		data
	})
}
// 解绑微信账号
export function remove(data) {
	return request({
		url: 'user/remove',
		method: 'POST',
		data
	})
}
// 退出登录
export function logout(data) {
	return request({
		url: 'user/logout',
		method: 'POST',
		data
	})
}
// 获取用户协议
export function pact(data) {
	return request({
		url: 'user/pact',
		method: 'POST',
		data
	})
}
// 修改订阅
export function subscribe(data) {
	return request({
		url: 'user/subscribe',
		method: 'POST',
		data
	})
}
// 消息推送列表
export function message(data) {
	return request({
		url: 'user/message',
		method: 'POST',
		data
	})
}
// 获取会员码
export function user_code(data) {
	return request({
		url: 'user/code',
		method: 'POST',
		data
	})
}


// 解绑微信账号
export function user_remove(data) {
	return request({
		url: 'user/remove',
		method: 'POST',
		data
	})
}
// 绑定微信账号
export function user_binding(data) {
	return request({
		url: 'user/binding',
		method: 'POST',
		data
	})
}
//消息详情
export function message_detail(data) {
	return request({
		url: 'user/message_detail',
		method: 'POST',
		data
	})
}


// 授权登录
export function wx_login(data) {
	return request({
		url: 'user/wx_login',
		method: 'POST',
		data
	})
}

//退款(已完成的订单可以发起退款)
export function refund(data) {
	return request({
		url: 'order/refund',
		method: 'POST',
		data
	})
}


// 静默登录
export function auto_login(data) {
	return request({
		url: 'user/auto_login',
		method: 'POST',
		data
	})
}

// 获取openid
export function get_openid(data) {
	return request({
		url: 'user/get_openid',
		method: 'POST',
		data
	})
}


// 立即购买
export function buyNow(data) {
	return request({
		url: 'order/buyNow',
		method: 'POST',
		data
	})
}


// 配送管理
export function distributionEdit(data) {
	return request({
		url: 'business/distribution_edit',
		method: 'POST',
		data
	})
}

// 修改商家信息
export function storeEdit(data) {
	return request({
		url: 'business/store_edit',
		method: 'POST',
		data
	})
}



// 获取分类列表
export function goodsCategory(data) {
	return request({
		url: 'business/goods_category',
		method: 'POST',
		data
	})
}


// 启用/禁用商品分类
export function goodsCategorySwitch(data) {
	return request({
		url: 'business/goods_category_switch',
		method: 'POST',
		data
	})
}


// 删除商品分类
export function goodsCategoryDel(data) {
	return request({
		url: 'business/goods_category_del',
		method: 'POST',
		data
	})
}



// 添加/修改商品分类
export function goodsCategoryAdd(data) {
	return request({
		url: 'business/goods_category_add',
		method: 'POST',
		data
	})
}



// 商品分类详情
export function goodsCategoryDetail(data) {
	return request({
		url: 'business/goods_category_detail',
		method: 'POST',
		data
	})
}



// 规格列表
export function skuTemplateList(data) {
	return request({
		url: 'business/sku_template_list',
		method: 'POST',
		data
	})
}


// 规格添加
export function skuTemplate(data) {
	return request({
		url: 'business/sku_template',
		method: 'POST',
		data
	})
}

// 规格详情
export function skuTemplateDetail(data) {
	return request({
		url: 'business/sku_template_detail',
		method: 'POST',
		data
	})
}


// 添加商品
export function goodsAdd(data) {
	return request({
		url: 'business/goods_add',
		method: 'POST',
		data
	})
}

// 商品列表
export function goodsList(data) {
	return request({
		url: 'business/goods_list',
		method: 'POST',
		data
	})
}

// 商品详情
export function goodsDetail(data) {
	return request({
		url: 'business/goods_detail',
		method: 'POST',
		data
	})
}

// 商品上下架
export function goods_switch(data) {
	return request({
		url: 'business/goods_switch',
		method: 'POST',
		data
	})
}



// 商品上下架
export function delgoods(data) {
	return request({
		url: 'business/goods_del',
		method: 'POST',
		data
	})
}


// 商品上下架
export function storeCode(data) {
	return request({
		url: 'business/store_code',
		method: 'POST',
		data
	})
}