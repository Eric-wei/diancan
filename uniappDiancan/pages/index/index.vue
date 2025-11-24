<template>
	<view class="content" v-show="display">
		<view class="hader">
			<!-- add高度组件 -->
			<view class="img_box">
				<view class="img_box">
					<u-swiper :list="list1" :indicator='false' keyName="image" indicatorMode='dot' :height='565'
						@click="hadeswp"></u-swiper>
				</view>
				<view class="flex jc ac">
					<view class="user flex jsb ac">
						<view class="flex ac">
							<view class="user_head_portrait" @tap="routergo('/pages/me_all/personage/personage')">
								<image :src="userall.avatar" mode="aspectFill"></image>
							</view>
							<view class="user_2" @tap="routergo('/pages/me_all/personage/personage')">
								<view class="user_2_1">
									{{userall.username||'尊敬的用户'}}
								</view>
							</view>
						</view>
						<view class="user_3 flex ac f1" v-if="userall.is_shlogin" @click.stop="funislogin()">

							<text class="nologin">登录/注册</text>
							<u-icon name="arrow-right" color="#333" size="20"></u-icon>
						</view>
						<view class="flex ac" v-else>
							<view class="pointsli funli" @tap="routergo('/pages/me_all/integral/integral')">
								<view class="funlinum">
									{{userall.score||0}}
								</view>
								<view class="funlitt">
									积分
								</view>
							</view>
							<view class="couponli funli" @tap="routergo('/pages/me_all/coupon/coupon')">
								<view class="funlinum">
									{{c_user.coupon_num||0}}
								</view>
								<view class="funlitt">
									优惠券
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- <height :hg='30'></height> -->
			<view class="card">
				<view class="card_1 flex jc ac">
					<view class="card_1_left" @tap="router(1)" v-if="common.iszq==1">
						<view class="card_1_left_img">
							<image :src="Httpimg+'jg2.png'" mode="aspectFill" v-if="Httpimg"></image>
						</view>
						<view class="">
							<view class="cardBt">到店自取</view>
							<view class="cardLt">下单免排队</view>
						</view>
					</view>
					<view class="card_1plank"></view>
					<view class="card_1_right" @tap="router(2)" v-if="common.iswm==1">
						<view class="card_1_left_img">
							<image :src="Httpimg+'jg1.png'" mode="aspectFill" v-if="Httpimg"></image>
						</view>
						<view class="">
							<view class="cardBt">外卖到家</view>
							<view class="cardLt">美食送到家</view>
						</view>
					</view>
				</view>
			</view>
			<view class="card pa24 mt24" v-if="false">
				<swiper class="couponimg mb24" :indicator-dots="false" :autoplay="true" :interval="3000"
					:duration="1000">
					<swiper-item class="couponimg" v-for="item in list21">
						<image class="w100h100" :src="item.image" mode="aspectFill" @click="routergo(item.url)"></image>
					</swiper-item>
				</swiper>
				<view class="flex ac jsb">
					<view class="entranceli" @tap="routergo('/pages/me_all/integral/integral')">
						<image class="entranceliimg" :src="Httpimg+'index1.png'" mode="" v-if="Httpimg"></image>
						<view class="entrancelitt">
							积分商城
						</view>
					</view>

					<view class="entranceli" v-if="common.ispayh==1" @tap="scanCode(2)">
						<image class="entranceliimg" :src="Httpimg+'index2.png'" mode="" v-if="Httpimg"></image>
						<view class="entrancelitt">
							先餐后付
						</view>
					</view>
					<view class="entranceli" @tap="scanCode(1)" v-if="common.ists==1">
						<image class="entranceliimg" :src="Httpimg+'index2.png'" mode="" v-if="Httpimg"></image>
						<view class="entrancelitt">
							先付后餐
						</view>
					</view>
					<view class="entranceli" @tap="routergo('/pages/reserved/reserved')" v-if="common.isyy==1">
						<image class="entranceliimg" :src="Httpimg+'index3.png'" mode="" v-if="Httpimg"></image>
						<view class="entrancelitt">
							提前预约
						</view>
					</view>
					<button open-type="contact" @contact="contact">
						<view class="entranceli">
							<image class="entranceliimg" :src="Httpimg+'index4.png'" mode="" v-if="Httpimg"></image>
							<view class="entrancelitt">
								加盟咨询
							</view>
						</view>
					</button>
				</view>



			</view>

			<view class="card  mt24">
				<view class="card_3">
					<u-swiper :list="list2" keyName="image" :indicator='true' indicatorMode='dot' :height='240'
						@click="swp"></u-swiper>
				</view>
				<!-- <view class="beianhao" v-if="common.mini_filings">
					备案号：{{common.mini_filings || ''}}
				</view> -->
			</view>

			<login :show="enter" @loadpage="loadpage" @closepage='closepage'></login>
		</view>
		<height :hg='40'></height>
		<barcode id='1' class="barcode" autostart="true" ref="barcode" background="rgb(0,0,0)" frameColor="#1C86EE"
			scanbarColor="#1C86EE" :filters="fil" @marked="success1" @error="fail1"></barcode>
	</view>
</template>
<script>
	import config from '@/config.js'
	import {
		userInfo,
		common
	} from "@/api/public.js"
	import {
		index_list,
		store_default,
		order_empty,
		seat
	} from "@/api/comm.js"
	import {
		assign,
		forEach
	} from "lodash"
	import route from 'uview-ui/libs/util/route'
	import {
		mapState
	} from 'vuex'
	export default {
		data() {
			return {
				Httpimg: config.HttpImg,
				title: 'Hello',
				// 轮播图
				list1: [],
				list21: [],
				list2: [],
				userall: {},
				public: {},
				display: false,
				enter: false,
				common: uni.getStorageSync('public') || ''
			}
		},
		onLoad(e) {
			if (e.pid) {
				uni.setStorageSync('pid', e.pid)
			}

			if (e.scene) {
				uni.setStorageSync('pid', e.scene)
				// let bb = e.scene.split('&')
				// let cc = bb[1]
				// //2先餐后付款 //1先付款后餐
				// uni.setStorageSync('firstPay', cc)
				// uni.setStorageSync('option', 3)
				// uni.setStorageSync('scene', bb[0])
			}

			// 先使用缓存数据显示页面，避免真机环境下网络请求失败导致页面不显示
			this.initDisplayWithCache()

			// 获取公共参数
			this.index_listApi_1()
			this.index_listApi_2()
			this.index_listApi_3()
		},
		onShow() {
			// this.userInfoApi()
			this.enter = false
			this.commonApi()
			this.getUser()
		},
		computed: {
			...mapState(['islogin']),
			c_user() {
				return uni.getStorageSync('user')
			}
		},
		methods: {
			// 使用缓存数据初始化页面显示，避免真机环境下网络请求失败导致页面不显示
			initDisplayWithCache() {
				// 尝试从缓存加载数据
				const cachedPublic = uni.getStorageSync('public')
				const cachedUser = uni.getStorageSync('user')
				
				if (cachedPublic) {
					this.common = cachedPublic
					this.public = Object.assign({}, cachedPublic)
				}
				
				if (cachedUser) {
					this.userall = Object.assign({}, cachedUser)
				}
				
				// 立即显示页面，不等待网络请求
				this.display = true
			},
			loadpage() {
				this.unloadpage()
			},
			async getUser() {
				try {
					let userinfo = uni.getStorageSync('userinfo')
					if (userinfo && userinfo.token) {
						let user = await userInfo()
						try {
							uni.hideToast()
						} catch (e) {
							// 忽略 hideToast 错误
						}
						if (user && user.code == 1) {
							uni.setStorageSync('user', user.data)
							this.userall = Object.assign({}, user.data)
						}
					}
				} catch (err) {
					console.error('getUser error:', err)
					// 失败时使用缓存数据
					const cachedUser = uni.getStorageSync('user')
					if (cachedUser) {
						this.userall = Object.assign({}, cachedUser)
					}
				}
			},
			scanCode(e) {
				uni.scanCode({
					success: (res) => {
						uni.removeStorageSync('peopleCount')
						if (res.path) {
							let option = uni.getStorageSync('option')
							if (option !== 3) {
								order_empty()
							}
							uni.setStorageSync('option', 3)
							uni.setStorageSync('tableNumber', false)
							uni.reLaunch({
								url: res.path
							})
						} else {
							uni.showToast({
								title: "座号二维码有误，请重新扫一扫！",
								icon: 'none'
							})
						}
						// 识别成功，执行对应操作
					}
				})
			},
			commonApi() {
				// 设置超时处理，避免真机环境下请求时间过长
				const timeoutId = setTimeout(() => {
					// 如果30秒后还没响应，使用缓存数据并显示页面
					if (!this.display) {
						console.warn('commonApi timeout, using cache data')
						this.common = uni.getStorageSync('public') || {}
						this.userall = Object.assign({}, uni.getStorageSync('user') || {})
						this.display = true
					}
				}, 5000) // 5秒超时
				
				common().then(data => {
					clearTimeout(timeoutId)
					if (data && data.code == 1) {
						uni.setStorageSync('public', data.data)
						this.common = data.data
						this.public = Object.assign({}, uni.getStorageSync('public'))
						if (!uni.getStorageSync('user')) {
							uni.setStorageSync('user', data.data)
						}
						this.userall = Object.assign({}, uni.getStorageSync('user'))
					}
					this.display = true
					try {
						uni.hideToast()
					} catch (e) {
						// 忽略 hideToast 错误
					}
				}).catch(err => {
					clearTimeout(timeoutId)
					// API 失败时也显示页面，使用缓存数据
					console.error('commonApi error:', err)
					// 如果还没有显示，使用缓存数据
					if (!this.display) {
						this.common = uni.getStorageSync('public') || {}
						this.userall = Object.assign({}, uni.getStorageSync('user') || {})
						this.display = true
					}
					try {
						uni.hideToast()
					} catch (e) {
						// 忽略 hideToast 错误
					}
				})

			},
		async router(url) {
			// 检查是否已登录
			if (!uni.getStorageSync('userinfo')) {
				this.enter = true
				return
			}
			
			try {
				let shop = uni.getStorageSync('shop')
				let idx = uni.getStorageSync('option')
				
				// 显示加载提示
				uni.showLoading({
					title: '加载中...',
					mask: true
				})
				
				// 调用默认门店接口，如果没有shop则获取默认门店
				let data = await store_default({
					id: shop || ''
				})
				
				uni.hideLoading()
				
				if (data && data.code == 1 && data.data) {
					// 保存门店信息
					uni.setStorageSync('shop', data.data.id)
					uni.setStorageSync('currentShop', data.data)
					
					// 检查门店是否营业
					if (data.data.is_business == 0) {
						uni.showModal({
							title: '提示',
							content: '该店铺已歇业，是否选择其他门店？',
							success: (res) => {
								if (res.confirm) {
									uni.navigateTo({
										url: '/pages/order_all/selectstore/selectstore'
									})
								}
							}
						})
						return
					}
					
					// 检查外卖开关
					if (url == 2) {
						if (data.data.takeaway_switch == 0) {
							uni.showModal({
								title: '提示',
								content: '该店铺暂不支持外卖，是否选择到店自取？',
								success: (res) => {
									if (res.confirm && data.data.delivery_switch == 1) {
										// 切换到自取模式
										this.router(1)
									} else if (res.confirm) {
										// 选择其他门店
										uni.navigateTo({
											url: '/pages/order_all/selectstore/selectstore'
										})
									}
								}
							})
							return
						}
					} else {
						// 检查自取开关
						if (data.data.delivery_switch == 0) {
							uni.showModal({
								title: '提示',
								content: '该店铺暂不支持自提，是否选择外卖配送？',
								success: (res) => {
									if (res.confirm && data.data.takeaway_switch == 1) {
										// 切换到外卖模式
										this.router(2)
									} else if (res.confirm) {
										// 选择其他门店
										uni.navigateTo({
											url: '/pages/order_all/selectstore/selectstore'
										})
									}
								}
							})
							return
						}
					}
					
					// 如果切换了订单类型，清空购物车
					if (idx !== url) {
						await order_empty()
					}
					
					uni.setStorageSync('tableNumber', false)
					uni.setStorageSync('option', url)
					uni.reLaunch({
						url: '/pages/order/order'
					})
				} else {
					// 没有获取到门店信息，提示选择门店
					uni.showModal({
						title: '提示',
						content: '暂无可用门店，请先选择门店',
						success: (res) => {
							if (res.confirm) {
								uni.navigateTo({
									url: '/pages/order_all/selectstore/selectstore'
								})
							}
						}
					})
				}
			} catch (err) {
				uni.hideLoading()
				console.error('router error:', err)
				
				// 根据错误类型显示不同的提示
				let errorMsg = '请求失败，请稍后重试或选择门店'
				if (err && err.msg) {
					errorMsg = err.msg
				} else if (err && err.message) {
					errorMsg = err.message
				}
				
				uni.showModal({
					title: '提示',
					content: errorMsg,
					success: (res) => {
						if (res.confirm) {
							uni.navigateTo({
								url: '/pages/order_all/selectstore/selectstore'
							})
						}
					}
				})
			}
		},
			swp(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				}
				// 判断是否是优惠券，如果是则跳转到领券中心
				let item = this.list2[url]
				if (item) {
					// 检查URL、标题或类型中是否包含优惠券相关关键词
					let urlStr = item.url || ''
					let titleStr = item.title || ''
					let typeStr = item.type || ''
					let imageStr = item.image || ''
					
					if (urlStr.includes('coupon') || urlStr.includes('优惠券') || urlStr.includes('领券') ||
						titleStr.includes('优惠券') || titleStr.includes('领券') ||
						typeStr === 'coupon' || typeStr === '优惠券' ||
						imageStr.includes('coupon') || imageStr.includes('优惠券')) {
						this.routergo('/pages/me_all/coupon_collection/coupon_collection')
						return
					}
				}
				let arr = ['/pages/index/index', '/pages/order/order', '/pages/order_form/order_form', '/pages/me/me']
				if (arr.includes(this.list2[url].url)) {
					uni.switchTab({
						url: this.list2[url].url
					})
				} else {
					uni.navigateTo({
						url: this.list2[url].url
					})
				}
			},
			hadeswp(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				}
				let arr = ['/pages/index/index', '/pages/order/order', '/pages/order_form/order_form', '/pages/me/me']
				if (arr.includes(this.list1[url].url)) {
					uni.switchTab({
						url: this.list1[url].url
					})
				} else {
					uni.navigateTo({
						url: this.list1[url].url
					})
				}
			},
			contact(e) {},
			async index_listApi_1() {
				try {
					let data = await index_list({
						type: 1
					})
					this.list1 = []
					if (data && data.data) {
						this.list1.push(...data.data)
					}
				} catch (err) {
					console.error('index_listApi_1 error:', err)
					this.list1 = []
				}
			},
			async index_listApi_2() {
				try {
					let data = await index_list({
						type: 2
					})
					this.list2 = []
					if (data && data.data) {
						this.list2.push(...data.data)
					}
				} catch (err) {
					console.error('index_listApi_2 error:', err)
					this.list2 = []
				}
			},
			async index_listApi_3() {
				try {
					let data = await index_list({
						type: 5
					})
					this.list21 = []
					if (data && data.data) {
						this.list21.push(...data.data)
					}
				} catch (err) {
					console.error('index_listApi_3 error:', err)
					this.list21 = []
				}
			},
			funislogin() {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					// uni.showToast({
					// 	title: '请登录',
					// 	icon: 'none'
					// })
					return
				}
			},

			routergo(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					// uni.showToast({
					// 	title: '请登录',
					// 	icon: 'none'
					// })
					return
				} else {
					if (!uni.getStorageSync('userinfo')) {
						this.enter = true
						return
					}
					let arr = ['/pages/index/index', '/pages/order/order', '/pages/order_form/order_form', '/pages/me/me']
					if (arr.includes(url)) {
						uni.switchTab({
							url: url
						})
					} else {
						uni.navigateTo({
							url: url
						})
					}

				}
			},
			//未登录关闭弹出层需要关掉组件
			closepage() {
				this.enter = false
			},
		}
	}
</script>
<style>
	page {
		background: #FAFAFA !important;
	}
</style>
<style lang="scss" scoped>
	.beianhao {
		margin: 20px 0;
		text-align: center;
		font-size: 24rpx;
		color: #b5b5b5;
	}

	.mt24 {
		margin-top: 24rpx !important;
	}

	.entranceli {
		.entranceliimg {
			width: 64rpx;
			height: 64rpx;
			display: block;
			margin: auto;
		}

		.entrancelitt {
			font-size: 24rpx;
			color: #777777;
		}
	}


	.pa24 {
		padding: 24rpx;
		box-sizing: border-box;
	}

	.funli {
		margin-left: 40rpx;

		.funlinum {
			font-weight: bold;
			font-size: 28rpx;
			color: #333333;
			text-align: center;
		}

		.funlitt {
			font-weight: 400;
			font-size: 24rpx;
			color: #777777;
			margin-top: 10rpx;
			white-space: nowrap;
		}
	}


	.img_box {
		width: 100%;
		height: 622rpx;
		position: relative;
		overflow: hidden;

		::v-deep .u-swiper__indicator {
			position: absolute;
			bottom: 150rpx !important;
		}

		::v-deep .u-swiper__wrapper__item__wrapper {
			overflow: hidden !important;
		}

		::v-deep .u-swiper__wrapper__item__wrapper__image {
			object-fit: cover !important;
			width: 100% !important;
			height: 100% !important;
			object-position: center !important;
		}

		.img_box {
			position: absolute;
		}

		.user {
			width: 704rpx;
			height: 130rpx;
			background: #FFFFFF;
			border-radius: 20rpx;
			display: flex;
			align-items: center;
			margin: 0 auto;

			// top: 630rpx;
			padding: 24rpx 30rpx;
			box-sizing: border-box;
			position: absolute;
			bottom: 0;


			.user_head_portrait {
				width: 64rpx;
				height: 64rpx;
				border-radius: 50%;
				overflow: hidden;
				// margin-left: 32rpx;
			}

			.user_2 {
				margin-left: 30rpx;

				.user_2_1 {
					width: 100%;
					font-size: 28rpx;
					font-weight: 600;
					color: #313539;
					// margin-bottom: 5rpx;
					overflow: hidden;
					text-overflow: ellipsis;
					white-space: nowrap;
				}

				.user_2_2 {
					font-size: 24rpx;
					font-weight: 600;
					color: #2F2F2F;
					margin-bottom: 5rpx;
				}

				.user_2_3 {
					margin-top: 10rpx;
				}
			}

			.user_3 {
				position: absolute;
				right: 30rpx;

				.nologin {
					margin-right: 4rpx;
				}

				.user_code {
					width: 54rpx;
					height: 54rpx;
					margin: 0 auto;
				}

				text {
					font-size: 24rpx;
					font-weight: 400;
					color: #333333;
				}
			}

		}
	}

	.mb24 {
		margin-bottom: 24rpx;
	}

	.card {
		width: 690rpx;
		margin: auto;
		margin-top: 24rpx;
		background: #fff;
		border-radius: 20rpx;
		overflow: hidden;

		.couponimg {
			width: 100%;
			height: 158rpx;
			border-radius: 12rpx;
			overflow: hidden;
		}


		.card_1 {

			height: 320rpx;

			.cardBt {
				font-weight: 400;
				font-size: 44rpx;
				color: #333333;
				margin-top: 24rpx;
				text-align: center;
			}

			.cardLt {
				font-weight: 400;
				font-size: 28rpx;
				color: #777777;
				margin-top: 16rpx;
				text-align: center;
			}

			.card_1plank {
				width: 2rpx;
				height: 260rpx;
				background: #F2F2F2;
			}

			.card_2_left {
				width: 344rpx;
				border-radius: 16rpx 16rpx;
				padding: 24rpx;
				box-sizing: border-box;
				color: #3D3D3D;
				position: relative;

				.card_2_left_title {
					view:nth-child(1) {
						font-size: 24rpx;
						font-weight: 600;
						// color: #000000;
					}

					view:nth-child(2) {
						font-size: 20rpx;
						font-weight: 400;
						margin-top: 10rpx;
						z-index: 10;
					}
				}

				.card_2_left_img {
					position: absolute;
					bottom: 0rpx;
					right: 0rpx;
					z-index: 0;

					image {
						width: 180rpx;
						height: 180rpx;
					}
				}


				.card_2_left_title_2 {
					margin: 28rpx 0 0;

					view:nth-child(1) {
						font-size: 32rpx;
						font-weight: 600;
						color: #000000;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #666;
						margin-top: 20rpx;
						width: 150rpx;
						position: relative;
						z-index: 10;
					}
				}

				.card_2_left_img_2 {
					width: 100%;
					height: 100%;
					position: absolute;
					bottom: 0;
					right: 0;
					z-index: 0;
				}

			}

			.card_1_left {
				width: 343rpx;
				height: 270rpx;
				background: #fff;
				border-radius: 16rpx;
				position: relative;
				padding: 24rpx 0;
				box-sizing: border-box;

				.card_1_left_title {
					margin: 50rpx 0 0 20rpx;

					view:nth-child(1) {
						font-size: 32rpx;
						font-weight: 600;
						// color: #000000;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #666;
						margin-top: 20rpx;
					}
				}

				.card_1_left_img {
					margin: auto;
					display: flex;
					justify-content: center;
					align-items: center;
					
					image {
						width: 120rpx;
						height: 120rpx;
					}
				}
			}

			.card_1_right {
				width: 343rpx;
				height: 270rpx;
				background: #fff;
				border-radius: 16rpx;
				position: relative;
				padding: 24rpx 0;
				box-sizing: border-box;


				.card_1_left_title {
					margin: 50rpx 0 0 20rpx;

					view:nth-child(1) {
						font-size: 32rpx;
						font-weight: 600;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #666;
						margin-top: 20rpx;
						width: 190rpx;

					}
				}

				.card_1_left_img {
					margin: auto;
					display: flex;
					justify-content: center;
					align-items: center;
					
					image {
						width: 120rpx;
						height: 120rpx;
					}
				}

				.card_1_left_img_1 {
					width: 88rpx;
					height: 88rpx;

				}
			}

		}

		.card_2 {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin: 20rpx 0;

			.card_2_content {
				width: 342rpx;
				height: 161rpx;
				background: #FFFFFF;
				border-radius: 36rpx 13rpx 13rpx 13rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;

				.card_2_content_left {
					margin-left: 29rpx;
					text-align: left;

					view:nth-child(1) {
						font-size: 32rpx;
						font-weight: 500;
						color: #000000;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #AAAAAA;
						margin-top: 5rpx;
					}
				}

				.card_2_content_right {
					margin-right: 30rpx;
					width: 85rpx;
					height: 66rpx;
				}
			}
		}

		.card_3 {
			width: 100%;

		}
	}
</style>