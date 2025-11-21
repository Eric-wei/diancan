<template>
	<view>
		<u-navbar title="店铺码" @leftClick="leftClick" :autoBack="true">
		</u-navbar>
		<view class="back">
			<image src="/static/Big_picture/ckg 6.png" mode=""></image>
		</view>
		<height :hg='System_height'></height>
		

		<view class="qr">
			<view class="qr_1" @tap="routerTo('/pages/me_all/personage/personage')">
				<view class="qr_1_img">
					<image :src="shopuser.image"  mode="aspectFill"></image>
				</view>
				<text>{{shopuser.title}}</text>
			</view>
			<view class="qr_2">
				<image :src="img" mode="" :show-menu-by-longpress="true"></image>
			</view>
			
			<view class="qr_3">
				长按下载/识别店铺码
			</view>
		</view>

	</view>
</template>

<script>
	import {
		userInfo
	} from "@/api/public.js"
	import {
		user_code,
		storeCode
	} from "@/api/user.js"
	import {
		cellphone
	} from "@/utils/type_height.js"
	export default {
		data() {
			return {
				clear1: null,
				clear2: null,
				userall: {},
				time: 30,
				img: '',
				System_height: cellphone() //系统高度
			};
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		onLoad() {
			this.userInfoApi()
			this.user_codeApi()
			// 每30秒刷新一次
			// this.clear1 = setInterval(res => {
			// 	this.time--
			// 	if (this.time == 0) {
			// 		this.time = 30
			// 	}
			// }, 1000)
			// this.clear2 = setInterval(res => {
			// 	this.user_codeApi()
			// 	uni.showToast({
			// 		title: '已刷新二维码',
			// 		icon: "none"
			// 	})
			// }, 30000)
		},
		methods: {
			leftClick() {
				let curPage = getCurrentPages();
				if (curPage.length == 1) {
					uni.reLaunch({
						url: "/pages/merchant/my/my"
					})
				} else {
					uni.navigateBack()
				}
			},
			// 返回上一页
			goback() {
				uni.navigateBack({
					delta: 1
				})
			},
			async userInfoApi() {
				let data = await userInfo()
				if (data.code == 1) {
					uni.setStorageSync('user', data.data)
					this.userall = data.data
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			async user_codeApi() {
				let data = await storeCode({
					store_id: this.shopuser.id
				})
				if (data.code != 1) {
					uni.hideToast()
					uni.showModal({
						title: '提示',
						content: data.msg,
					})
				} else {
					this.img = data.data.qrcode
				}
			}
		},
		onUnload() {
			clearInterval(this.clear1)
			clearInterval(this.clear2)
		}
	}
</script>

<style lang="scss">
	.back {
		position: absolute;
		width: 100%;
		height: 100vh;
	}

	.nav-tab {
		display: flex;
		align-items: center;
		justify-content: space-between;
		z-index: 2;

		.nav-tab_left {
			padding: 30rpx;
		}

		.nav-tab_content {
			font-size: 34rpx;
			font-weight: 500;
			color: #FFFFFF;
			z-index: 2;
			text-align: center;
			padding-right: 100rpx;
		}
	}

	.qr {
		width: 700rpx;
		height: 714rpx;
		background: #FFFFFF;
		border-radius: 27rpx;
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		top: 30%;

		.qr_1 {
			margin: 0 auto;
			text-align: center;

			.qr_1_img {
				width: 120rpx;
				height: 120rpx;
				border-radius: 50%;
				overflow: hidden;
				position: relative;
				bottom: 50rpx;
				margin: 0 auto;
			}

			text {
				font-size: 32rpx;
				font-weight: 400;
				color: #343434;
				position: relative;
				bottom: 50rpx;
			}

		}

		.qr_2 {
			width: 340rpx;
			height: 340rpx;
			margin: 0 auto;
		}

		.qr_3 {
			font-size: 24rpx;
			font-weight: 400;
			color: #9B9B9B;
			text-align: center;
			margin-top: 50rpx;
		}
	}
</style>