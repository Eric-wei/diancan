import
request
from "../utils/request.js"
// 获取公共参数
export function common(data) {
	return request({
		url: 'common/common',
		method: 'POST',
		data
	})
}
// 获取会员个人信息
export function userInfo(data) {
	return request({
		url: 'user/userInfo',
		method: 'POST',
		data
	})
}
// 修改会员个人信息
export function user_profile(data) {
	return request({
		url: 'user/profile',
		method: 'POST',
		data
	})
}
// 上传文件
export function upload(data) {
	return request({
		url: 'common/upload',
		method: 'POST',
		data
	})
}
// 获取会员码
export function code(data) {
	return request({
		url: 'user/code',
		method: 'POST',
		data
	})
}