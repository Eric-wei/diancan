import
request
from "../utils/request.js"

// 获取公共参数
export function Agency_centerlist(data) {
	return request({
		url: 'distribution/index',
		method: 'POST',
		data
	})
}



export function distributiondraw(data) {
	return request({
		url: 'distribution/draw',
		method: 'POST',
		data
	})
}

//获取二维码
export function commoncode(data) {
	return request({
		url: 'common/wxacode',
		method: 'POST',
		data
	})
}

// 推广海报
export function distlist(data) {
	return request({
		url: 'distribution/distlist',
		method: 'POST',
		data
	})
}

// 佣金明细
export function brokeragedetailed(data) {
	return request({
		url: 'distribution/detailed',
		method: 'POST',
		data
	})
}
// 我的团队
export function distributionteam(data) {
	return request({
		url: 'distribution/team',
		method: 'POST',
		data
	})
}

// 我的团队列表 
export function teamlist(data) {
	return request({
		url: 'distribution/teamlist',
		method: 'POST',
		data
	})
}

//推广课程列表
export function distribution_list(data) {
	return request({
		url: 'distribution/listing',
		method: 'POST',
		data
	})
}

//生成推广课程海报
export function distribution_wxacode(data) {
	return request({
		url: 'distribution/wxacode',
		method: 'POST',
		data
	})
}

//推广课程收益列表
export function distribution_lesson_sy(data) {
	return request({
		url: 'distribution/lesson_sy',
		method: 'POST',
		data
	})
}

//获取课程分类
export function course_classification(data) {
	return request({
		url: 'lesson/type',
		method: 'POST',
		data
	})
}

//提现记录
export function distributiondrawLog(data) {
	return request({
		url: 'distribution/drawLog',
		method: 'POST',
		data
	})
}
//玩法说明
export function commonplay(data) {
	return request({
		url: 'common/play',
		method: 'POST',
		data
	})
}

export function common(data) {
	return request({
		url: 'common/common',
		method: 'POST',
		data
	})
}
export function pact(data) {
	return request({
		url: 'user/pact',
		method: 'POST',
		data
	})
}