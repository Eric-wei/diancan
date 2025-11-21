<template>
	<view class="content" v-show="display">
		<view class="hader">
			<!-- add高度组件 -->
			<view class="img_box">
				<view class="img_box">
					<u-swiper :list="list1" :indicator='false' keyName="image" indicatorMode='dot' :height='635'
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
							<image :src="Httpimg+'jg2.png'" mode="" v-if="Httpimg"></image>
						</view>
						<view class="">
							<view class="cardBt">自取</view>
							<view class="cardLt">下单免排队</view>
						</view>
					</view>
					<view class="card_1plank"></view>
					<view class="card_1_right" @tap="router(2)" v-if="common.iswm==1">
						<view class="card_1_left_img">
							<image :src="Httpimg+'jg1.png'" mode="" v-if="Httpimg"></image>
						</view>
						<view class="">
							<view class="cardBt">外卖</view>
							<view class="cardLt">美食送到家</view>
						</view>
					</view>
				</view>
			</view>
			<view class="card pa24 mt24">
				<swiper class="couponimg mb24" :indicator-dots="false" :autoplay="true" :interval="3000"
					:duration="1000">
					<swiper-item class="couponimg" v-for="item in list21">
						<image class="w100h100" :src="item.image" mode="aspectFill" @click="routergo(item.url)"></image>
					</swiper-item>
				</swiper>
				<view class="flex ac jsb">
					<view class="entranceli" @tap="routerTo('/pages/me_all/integral/integral')">
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
					<view class="entranceli" @tap="routerTo('/pages/reserved/reserved')" v-if="common.isyy==1">
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
			loadpage() {
				this.unloadpage()
			},
			async getUser() {
				let token = uni.getStorageSync('userinfo').token
				if (token) {
					let user = await userInfo()
					uni.hideToast()
					if (user.code == 1) {
						uni.setStorageSync('user', user.data)
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
				common().then(data => {
					if (data.code == 1) {
						uni.setStorageSync('public', data.data)
						this.common = data.data
						this.public = Object.assign({}, uni.getStorageSync('public'))
						if (!uni.getStorageSync('user')) {
							uni.setStorageSync('user', data.data)
						}
						this.userall = Object.assign({}, uni.getStorageSync('user'))
						this.display = true
					}
					uni.hideToast()
				})

			},
			async router(url) {
				let shop = uni.getStorageSync('shop')
				let idx = uni.getStorageSync('option')
				let data = await store_default({
					id: shop || ''
				})
				if (data.code == 1) {
					if (url == 2) {
						if (data.data.takeaway_switch == 0) {
							uni.showToast({
								title: '该店铺暂不支持外卖',
								icon: "none"
							})
							return
						}
					} else {
						if (data.data.delivery_switch == 0) {
							uni.showToast({
								title: '该店铺暂不支持自提',
								icon: "none"
							})
							return
						}
					}
				}
				if (idx !== url) {
					order_empty()
				}
				uni.setStorageSync('tableNumber', false)
				uni.setStorageSync('option', url)
				uni.reLaunch({
					url: '/pages/order/order'
				})


			},
			swp(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
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
				let data = await index_list({
					type: 1
				})
				this.list1 = []
				this.list1.push(...data.data)
			},
			async index_listApi_2() {
				let data = await index_list({
					type: 2
				})
				this.list2 = []
				this.list2.push(...data.data)
			},
			async index_listApi_3() {
				let data = await index_list({
					type: 5
				})
				this.list21 = []
				this.list21.push(...data.data)
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
		height: 692rpx;
		position: relative;

		::v-deep .u-swiper__indicator {
			position: absolute;
			bottom: 150rpx !important;
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
					width: 88rpx;
					height: 88rpx;
					margin: auto;
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
					width: 88rpx;
					height: 88rpx;
					margin: auto;

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