import config from '@/config.js'
import {
	get
} from 'lodash'
import wxApi from './wxApi'
const request = function(obj) {
	return new Promise(function(resolve, reject) {
		let opt = {
			url: config.serverIp + obj.url + getParams(obj.params),
			method: obj.method,
			timeout: obj.timeout || 10000 * 3,
			header: {
				'Content-type': 'application/json' || 'application/x-www-form-urlencoded',
			},
			data: obj.data,
			success: res => {
				if (res.data.code !== 1) {
					uni.showToast({
						title: res.data.msg,
						duration: 2000,
						icon: 'none',
					})
				}
				if (res.statusCode === 200) {
					// lodash 截取
					resolve(get(res, 'data', {}))
					//未登录
				} else if (res.statusCode === 401) {
					uni.removeStorageSync('userinfo')
					uni.removeStorageSync('user')
					uni.showToast({
						title: res.data.msg,
						icon: 'none',
						duration: 2000,
					})
					uni.$emit('loginOpen')
					//未绑定微信
				} else if (res.statusCode === 402) {
					uni.showToast({
						title: res.data.msg,
						icon: 'none',
						duration: 2000,
						success() {
							uni.showToast({
								title: res.data.msg,
								icon: 'none',
								duration: 2000,
								success() {
									setTimeout(res => {
										uni.navigateTo({
											url: '/pages/me_all/binding/binding'
										})
									}, 2000)
								}
							})
						}
					})
				} {
					reject({
						code: -1,
						msg: 'server error'
					})
				}
			},
			fail: function({
				errMsg
			}) {
				if (errMsg === 'request:fail timeout') {
					uni.showToast({
						title: '服务忙,稍等会再试吧',
						icon: 'none'
					})
				}
				reject({
					code: -1,
					msg: 'server error'
				})
			}
		}
		let token = uni.getStorageSync('userinfo')
		let location = uni.getStorageSync('location')
		let pid = uni.getStorageSync('pid')
		if (location) {
			location = location
		} else {
			location = {
				errMsg: "getFuzzyLocation:ok",
				latitude: 34.771770000000004,
				longitude: 113.63472
			}
		}
		uni.getStorageSync('userinfo') ? opt.header.token = token.token : ' '
		uni.getStorageSync('location') ? opt.header.lat = location.latitude : ' '
		uni.getStorageSync('location') ? opt.header.lon = location.longitude : ' '
		uni.getStorageSync('pid') ? opt.header.pid = pid : ' '
		uni.request(opt)
	})
}
const getParams = obj => {
	let str = '?'
	for (let s in obj) {
		str += `${s}=${obj[s]}&`
	}
	return str.substr(0, str.length - 1)
}

export default request