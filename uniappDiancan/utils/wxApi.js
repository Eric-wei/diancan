// 服务的地址、 图片拼接地址
import config from '@/config.js'
// 网络请求http配置文件
import request from '@/utils/request.js'
import {
	login
} from '../api/user'
// 获取身份信息
// import {
// 	authentication
// } from '@/api/user.js'
import {
	userInfo
} from "@/api/public.js"
export default {
	/**
	 * @路由调转
	 */
	routerTo(url, type = 'navigateTo') {
		uni[type]({
			url
		})
	},
	routerGo(url, type = 'navigateTo') {
		let token = uni.getStorageSync('userinfo').token
		if (token) {
			uni[type]({
				url
			})
		} else {
			uni.showToast({
				'title': '请登录',
				icon: 'none'
			})
		}
	},
	/**
	 * @路由返回
	 */
	routerBack(delta = 1) {
		uni.navigateBack({
			delta
		})
	},

	/**
	 * @米转换为公里
	 */
	mkm(x) {
		let nub = Math.round((x / 100) / 10);
		return nub.toFixed(2)
	},
	/**
	 * @数字精度
	 */
	BumberPrecision(x) {
		return Number(x).toFixed(2)
	},
	/**
	 * @微信获取用户信息
	 */
	getWxUserInfo() {
		return new Promise((reslove, reject) => {
			uni.getUserInfo({
				success(data) {
					reslove(data)
				},
				fail(err) {
					reject(err)
				}
			})


		})
	},
	setuser() {
		userInfo().then(user => {
			if (user.code == 1) {
				uni.setStorageSync('user', user.data)
			}
		})


	},
	/**
	 * @微信支付
	 * @orderInfo 参数对象格式
	 * @支付成功跳转页面url
	 */
	wxparApi(orderInfo, url, map, rest, navidx) {
		let that = this
		wx.requestPayment({
			timeStamp: orderInfo.timeStamp,
			nonceStr: orderInfo.nonceStr,
			package: orderInfo.package,
			signType: orderInfo.signType,
			paySign: orderInfo.paySign,
			success(res) {
				if (url) {
					that.subscription(map, url, navidx, rest)
				} else {
					uni.showToast({
						title: '支付成功',
						icon: 'none',
						duration: 2000,
						success() {
							if (navidx == 1) {
								uni.navigateBack()
							}
							if (navidx == 2) {
								that.unloadpage()
								return
							}
							uni.reLaunch({
								url: url
							})
							that.setuser()
						}
					})
				}
			},
			fail(res) {
				uni.showToast({
					title: '支付失败',
					icon: 'error',
					duration: 2000,
					success() {
						if (navidx == 1) {

						} else if (navidx == 2) {
							that.unloadpage()
						} else {

							uni.reLaunch({
								url: url
							})
						}
						that.setuser()
					}
				})
			}
		})
	},

	/**
	 * @微信获取code
	 */
	loginApi() {
		return new Promise((reslove, reject) => {
			uni.login({
				success(res) {
					reslove(res)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @选择图片或拍照
	 */
	chooseImage(count = 1) {
		return new Promise((reslove, reject) => {
			uni.chooseImage({
				count,
				success(data) {
					reslove(data)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @上传图片
	 */
	uploadImg(url) {
		let that = this
		return new Promise(async (relove, reject) => {
			let tempFilePaths // 储存图片信息
			// 是否传入图片地址
			if (!url) {
				await that.chooseImage().then(d => {
					tempFilePaths = d.tempFilePaths[0]
				}).catch(err => {
					tempFilePaths = err
				})
			} else {
				tempFilePaths = url
			}

			let {
				token
			} = uni.getStorageSync('userinfo')
			// 开始上传
			uni.uploadFile({
				url: config.serverHost + '/api/common/upload',
				fileType: 'image',
				filePath: tempFilePaths,
				name: 'file',
				header: {
					token
				},
				success: res => {
					let result = JSON.parse(res.data)
					relove(result.data.fullurl)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},

	/**
	 * @无需登录上传图片
	 */
	uploadimage(url) {
		let that = this
		return new Promise(async (relove, reject) => {
			let tempFilePaths // 储存图片信息
			// 是否传入图片地址
			if (!url) {
				await that.chooseImage().then(d => {
					tempFilePaths = d.tempFilePaths[0]
				}).catch(err => {
					tempFilePaths = err
				})
			} else {
				tempFilePaths = url
			}
			// 开始上传
			uni.uploadFile({
				url: config.serverHost + '/addons/fzdc/common/upload',
				fileType: 'image',
				filePath: tempFilePaths,
				name: 'file',

				success: res => {
					let result = JSON.parse(res.data)
					relove(result.data)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @扫码
	 */
	scanCode() {
		return new Promise((reslove, reject) => {
			uni.scanCode({
				success: (data) => {
					reslove(data)
					// this.rou
				},
				fail(err) {
					reject(err)
				}
			})
		})
	},
	/**
	 * @打电话
	 */
	openTel(phoneNumber) {
		uni.makePhoneCall({
			phoneNumber
		});
	},
	routerGo(url, type = 'navigateTo') {
		let token = uni.getStorageSync('userinfo').token
		if (token) {
			uni[type]({
				url
			})
		} else {
			uni.showToast({
				'title': '请登录',
				icon: 'none'
			})
		}
	},
	showToast(res) {
		uni.showToast({
			title: res,
			icon: 'none'
		})
	},
	/**
	 * @设置系统剪贴板的内容
	 * @String 参数
	 */
	setClipboardData(data) {
		uni.setClipboardData({
			data: data,
			showToast: true,
			success: function() {}
		});
	},
	/**
	 * @对象转get参数
	 */
	getParams: obj => {
		let str = '?'
		for (let s in obj) {
			str += `${s}=${obj[s]}&`
		}
		return str.substr(0, str.length - 1)
	},
	/**
	 * @展示图片
	 */
	viewImage(e, urls) {
		urls = urls.map(obj => {
			return obj = uni.getStorageSync('loginInfo').imgUrl + '/' + obj
		})
		uni.previewImage({
			urls,
			current: e.currentTarget.dataset.url
		});
	},
	/**
	 * @获取登录数据
	 */
	getLoginInfo() {
		let that = this
		return new Promise(function(relove, reject) {
			that.getLoginCode().then(code => {
				authentication({
					code
				}).then(d => {
					uni.setStorageSync('loginInfo', d.data)
					relove(d)
				}).catch(reject)
			})
		})
	},
	/**
	 * @获取两个经纬度的距离
	 */
	getDistance(lat1, lng1, lat2, lng2) {
		var radLat1 = lat1 * Math.PI / 180.0;
		var radLat2 = lat2 * Math.PI / 180.0;
		var a = radLat1 - radLat2;
		var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;
		var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) +
			Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)));
		s = s * 6378.137; // EARTH_RADIUS;
		s = Math.round(s * 10000) / 10;
		return s; // m
	},
	/**
	 * @配置授权位置
	 */
	getLocation() {
		return new Promise((relove, reject) => {
			// 授权
			uni.getLocation({
				success: data => {
					relove(data)
				},
				fail(err) {
					wx.getSetting({
						success: (res) => {
							var statu = res.authSetting;
							if (!statu['scope.userLocation']) {
								wx.showModal({
									title: '是否授权当前位置',
									content: '需要获取您的地理位置，请确认授权，否则地图功能将无法使用',
									success(tip) {
										if (tip.confirm) {
											wx.openSetting({
												success: data => {
													if (data
														.authSetting[
															"scope.userLocation"
														] === true
													) {
														wx.showToast({
															title: '授权成功',
															icon: 'success',
															duration: 1000
														})
														//授权成功之后，再调用chooseLocation选择地方
														setTimeout(
															function() {
																uni.getLocation({
																	success: data => {
																		relove
																			(
																				data
																			)
																	},
																	fail(
																		err
																	) {
																		reject
																			(
																				err
																			)
																	}
																})

															}, 1000)
													} else {
														reject()
													}
												},
												fail() {

												}
											})
										} else {
											wx.showToast({
												title: '授权失败',
												icon: 'none',
												duration: 1000
											})
											reject()
										}
									}
								})

							} else {
								uni.getLocation({
									success: data => {
										relove(data)
									},
									fail(err) {
										reject(err)
									}
								})
							}
						}
					})
				}
			})
		})
	},
	/**
	 * @uni方式拿到位置
	 */
	uniGetLocation() {
		return new Promise((relove, reject) => {
			uni.getLocation({
				success: d => {
					relove(d)
				},
				fail: d => {
					reject(d)
				}
			})

		})
	},
	/**
	 * @微信小程序模糊定位位置
	 */
	wxgetFuzzyLocation() {
		return new Promise((relove, reject) => {
			wx.getFuzzyLocation({
				type: 'wgs84',
				success: d => {
					relove(d)
				},
				fail: d => {
					uni.showToast({
						title: '未授权位置请在小程序设置开启',
						icon: 'none'
					})
					reject(d)
				}
			})
		})
	},
	/**
	 * @打开地图手动选择位置
	 */
	getchooseLocation() {
		return new Promise((relove, reject) => {
			uni.chooseLocation({
				success: function(d) {
					relove(d)
				},
				fail: function(d) {
					reject(d)
				}
			});
		})
	},
	/**
	 * @使用应用内置地图查看位置
	 */
	getopenLocation(latitude, longitude) {
		return new Promise((relove, reject) => {
			uni.openLocation({
				latitude: Number(latitude),
				longitude: Number(longitude),
				success: function(d) {
					relove(d)
				},
				fail: function(d) {
					reject(d)
				}
			});
		})
	},

	/**
	 * @获取url参数
	 */
	get_url(url, name) {
		var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
		var trueUrl1 = url.split('?')[2];
		var trueUrl2 = trueUrl1.match(/(\S*)#/)[1];
		var r = trueUrl2.match(reg);
		if (r != null) {
			return unescape(r[2]);
		}
		return null;
	},
	/**
	 * @获取缓存数据
	 */
	getStorage(key) {
		return uni.getStorageSync(key) || ''
	},
	unloadpage() {
		const pages = getCurrentPages()
		// 声明一个pages使用getCurrentPages方法
		const curPage = pages[pages.length - 1]
		// 声明一个当前页面
		curPage.onLoad(curPage.options) // 传入参数
		curPage.onShow()
		curPage.onReady()
	},

	subRouter(url, navidx) {
		let that = this
		if (navidx == 1) {
			uni.navigateBack()
		} else if (navidx ==
			2) {
			that.unloadpage()
		} else if (navidx == 3) {
			uni.redirectTo({
				url
			})
		} else {
			uni.reLaunch({
				url
			})
		}
		uni.hideLoading()
	},
	/**
	 * @微信消息订阅 tmp为数组,url为支订阅完成跳转的页面
	 */
	subscription(tmp, url, navidx, rest) {
		uni.showLoading()
		let that = this
		uni.requestSubscribeMessage({
			tmplIds: tmp,
			success(res) {
				that.subRouter(url, navidx)
			},
			fail(res) {
				that.subRouter(url, navidx)
			},
		})
	},

	/**
	 * @微信小程序 开启全页面分享功能在APP.js中onLaunch中调用此方法
	 */
	onLaunch(options) {
		this.overShare()
	},
	overShare() {
		wx.onAppRoute((res) => {
			let pages = getCurrentPages()
			let view = pages[pages.length - 1]
			if (view) {
				wx.showShareMenu({
					withShareTicket: true,
					menus: ['shareAppMessage', 'shareTimeline'],
					success(res) {},
					fail(e) {}
				})
			}
		})
	}


}