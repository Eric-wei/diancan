<template>
	<view>
		<!-- :closeable='true' -->
		<u-popup :show="show" :round="20" @close="close" :safeAreaInsetTop='true'>
			<view class="login">
				<view class="close" @click="close">
					<u-icon name='close' size='40'></u-icon>
				</view>
				<view class="hadr">
					<view>手机号登录/注册</view>
					<view>欢迎登录</view>
				</view>
				<view class="input">
					<view class="input_1">
						<view class="input_1_left">
							手机号
						</view>
						<input type="text" v-model="cell" maxlength="11" placeholder="请输入手机号">
						<view class="input_1_right" style="" v-if="cell.length!=11">
							获取验证码
						</view>
						<view class="input_1_right" v-else style="background: #E40030;" @tap="timeevent">
							<text v-if="time==60">获取验证码</text>
							<text v-else>{{time}}s</text>
						</view>
					</view>
					<view class="input_2">
						<view class="input_2_left">
							验证码
						</view>
						<input type="text" v-model="code" maxlength="4" placeholder="请输入验证码">
						<view class="input_2_right" :style="{background: code.length==4?'#E40030':''}" @tap="checkApi">
							登录
						</view>
					</view>
				</view>
				<view class="wechat">
					<view class="wechat_division" v-if="common.is_shlogin!==1">
						<u-divider text="手机号快捷登录" textSize='24rpx'></u-divider>
					</view>
					<view class="" v-if="common.is_shlogin!==1">
						<button v-if="common.login_type==1" open-type="getPhoneNumber" @getphonenumber="getphonenumber">
							<view class="wechat_img">
								<image src="@/static/phone.png" mode=""></image>
							</view>
						</button>
						<view v-else class="wechat_img" @click="loginClick">
							<image src="@/static/phone.png" mode=""></image>
						</view>
					</view>

					<view class="agreement">
						<label class="radio" @tap="selectevent">
							<radio value="r1" style="transform: scale(0.7);" color="#E40030" :checked="select" />
						</label>
						<view>
							<text>登录即代表同意</text>
							<text
								@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'用户协议'+'&state='+0)">《用户协议》,</text>
							<text
								@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'隐私政策'+'&state='+0)">《隐私政策》</text>
							<text>及</text>
							<text
								@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'第三方SDK类服务商说明'+'&state='+0)">《第三方SDK类服务商说明》</text>
						</view>
					</view>
				</view>
			</view>
		</u-popup>


		<u-popup :closeOnClickOverlay='false' :show="usershow" :round="10" mode='center' @close="usershow=false">
			<view class="user_box">
				<view class="user_box_title">
					<view class="" style="margin-left: 30rpx;"></view>
					<view class="">登录后可享受更多功能</view>
					<view class="" @click="usershow=false">
						<u-icon name='close' size='40'></u-icon>
					</view>
				</view>
				<button open-type='chooseAvatar' @chooseavatar="setimg">
					<view class="user_avatar">
						<image v-if="loginavatar" :src="loginavatar" mode=""></image>
						<image v-else src="../../static/avatar.png" mode=""></image>
					</view>
				</button>

				<view class="user_ipt">
					<view>昵称</view>
					<input @change='change' type="nickname" v-model="login_name" placeholder="请输入昵称" />
				</view>

				<view class="user_tag">
					99%+的用户使用选择微信头像和微信昵
					称，便于订单发货和售后沟通
				</view>

				<view class="user_btn" @click="wxlogin()">
					立即登录
				</view>
			</view>
		</u-popup>

	</view>
</template>

<script>
	import {
		send,
		check,
		login,
		wx_login,
		get_openid
	} from "@/api/user.js"
	import {
		userInfo,
		common
	} from "@/api/public.js"
	import
	loginApi
	from '@/utils/wxApi.js'
	import {
		mapState
	} from 'vuex'

	export default {
		props: ['show'],
		name: "login",
		data() {
			return {
				loginavatar: '',
				usershow: false,
				cell: '',
				time: 60,
				code: '',
				// 单选
				select: false,
				getuserinfo: {},
				logincode: '',
				common: null,
				login_name: "",
				login_url: null
			};
		},
		mounted() {
			loginApi.loginApi().then(res => {
				this.logincode = res.code
			})
			let userinfo = uni.getStorageSync('userinfo')
			this.commonApi()
		},

		methods: {
			async wxlogin() {
				let that = this
				if (!this.login_name) return this.showToast('请输入昵称')
				if (!this.loginavatar) return this.showToast('请上传头像')
				uni.login({
					success: (res) => {
						wx_login({
							code: res.code,
							username: this.login_name,
							avatar: this.login_url
						}).then(res1 => {
							if (res1.code == 1) {
								// that.islogin = true;
								this.$store.commit('setislogin',true)
								uni.setStorageSync('userinfo', res1.data.userinfo)
								that.userInfoApi()
								that.getopenid()
								that.usershow = false
							}
						})
					}
				})
			},
			change(e) {
				this.login_name = e.detail.value
			},
			setimg(e) {
				let img = e.detail.avatarUrl
				this.uploadimage(img).then(res => {
					this.loginavatar = res.fullurl
					this.login_url = res.url
				})
			},
			loginClick() {
				if (this.select) {
					this.usershow = true
					this.close()
				} else {
					uni.showToast({
						title: '请勾选用户协议',
						icon: "none"
					})
				}


			},
			async commonApi() {
				let data = await common()
				if (data.code == 1) {
					this.common = data.data
					uni.setStorageSync('public', data.data)
					this.public = Object.assign({}, uni.getStorageSync('public'))
					if (!uni.getStorageSync('user')) {
						uni.setStorageSync('user', data.data)
					}
					this.userall = Object.assign({}, uni.getStorageSync('user'))
					this.display = true
				}

			},
			close() {
				this.$emit('closepage')
			},
			timeevent() {
				this.sendApi()
				let timer = setInterval(() => {
					this.time--
					if (!this.time) {
						this.time = 60
						clearTimeout(timer)
					}
				}, 1000);
			},
			selectevent() {
				this.select = this.select ? false : true
			},
			async sendApi() {
				let data = await send({
					mobile: this.cell
				})
				if (data.code == 1) {

				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			// 获取openid
			getopenid() {
				uni.login({
					success: (res) => {
						// 获取get_openid
						get_openid({
							code: res.code
						}).then(res => {
							if (res.code == 1) {
								uni.setStorageSync('openid', res.data.openid)
							}
						})
					},
					fail: (err) => {
						console.log('eerr', err);
					}
				})
			},
			async checkApi() {
				if (this.select) {
					let data = await check({
						mobile: this.cell,
						captcha: this.code
					})
					if (data.code == 1) {
						// this.islogin = true;
						this.$store.commit('setislogin',true)
						uni.setStorageSync('userinfo', data.data.userinfo)
						this.$emit('closepage')
						this.userInfoApi()
						this.commonApi()
						this.getopenid()
					} else {
						uni.showToast({
							title: data.msg,
							icon: "none"
						})
					}
				} else {
					uni.showToast({
						title: '请勾选用户协议',
						icon: "none"
					})
				}
			},
			// 手机或微信登录成功后执行
			async userInfoApi() {
				let that = this
				let data = await userInfo()
				if (data.code == 1) {
					uni.setStorageSync('user', data.data)
					// uni.setStorageSync('option', data.data.type)
					this.$emit("closepage")
					uni.showToast({
						title: data.msg,
						icon: "none",
						duration: 1000,
						success() {
							// 页面重载
							const pages = getCurrentPages()
							// 声明一个pages使用getCurrentPages方法
							const curPage = pages[pages.length - 1]
							// 声明一个当前页面
							curPage.onLoad(curPage.options) // 传入参数
							curPage.onShow()
							curPage.onReady()
							that.commonApi()
						}
					})
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			// wechat
			getphonenumber(event) {
				let that = this
				if (event.detail.errMsg == "getPhoneNumber:fail user deny") {
					return
				}
				if (this.select) {
					this.getuserinfo = event.detail
					this.getuserinfo.code = this.logincode
					this.getuserinfo.iv = encodeURIComponent(this.getuserinfo.iv)
					this.getuserinfo.encryptedData = encodeURIComponent(this.getuserinfo.encryptedData)
					login(this.getuserinfo).then(res => {
						if (res.code == 1) {
							uni.setStorageSync('userinfo', res.data.userinfo)
							this.$emit("closepage")
							that.userInfoApi()
							that.getopenid()
						} else {
							uni.showToast({
								title: res.msg,
								icon: "none"
							})
						}
					})
				} else {
					uni.showToast({
						title: '请勾选用户协议',
						icon: "none"
					})
				}
			},
		}
	}
</script>

<style lang="scss">
	::v-deep.uicon-close {
		font-size: 50rpx;
	}

	.user_box {
		width: 600rpx;
		height: 700rpx;
		text-align: center;
		padding: 30rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		flex-direction: column;
		justify-content: space-between;

		.user_box_title {
			display: flex;
			align-items: center;
			justify-content: space-between;
			font-size: 34rpx;
			font-weight: 600;
		}

		.user_avatar {
			image {
				width: 200rpx;
				height: 200rpx;
				border-radius: 50%;
			}
		}

		.user_ipt {
			display: flex;
			justify-content: space-between;
			align-items: center;
			font-size: 30rpx;
			color: #3d3d3d;
			padding: 20rpx 0;
			box-sizing: border-box;
			border-bottom: 1rpx #eee solid;

			input {
				width: 400rpx;
				text-align: left;
			}
		}

		.user_tag {
			background-color: #f3f3ff;
			padding: 24rpx 30rpx;
			box-sizing: border-box;
			font-size: 24rpx;
			color: #848484;
			text-align: left;
			border-radius: 16rpx;
		}

		.user_btn {
			background-color: #E40030;
			height: 80rpx;
			width: 100%;
			font-size: 28rpx;
			color: #FFFFFF;
			text-align: center;
			line-height: 80rpx;
			border-radius: 8rpx;
		}
	}


	.login {
		height: 800rpx;
		background: #FFFFFF;
		z-index: 9999999;

		.close {
			position: absolute;
			top: 30rpx;
			right: 30rpx;
			z-index: 99999991;
		}

		.hadr {
			padding: 30rpx 40rpx;

			view:nth-child(1) {
				font-size: 32rpx;
				font-weight: 600;
				color: #0D0D0D;
			}

			view:nth-child(2) {
				font-size: 24rpx;
				font-weight: 500;
				color: #9A9A9A;
				padding: 20rpx 0;
			}
		}

		.input {
			padding: 0 40rpx;

			.input_1 {
				display: flex;
				align-items: center;
				justify-content: space-between;
				border-bottom: 1rpx solid #CCCCCC;
				padding: 30rpx 0;

				.input_1_left {
					font-size: 32rpx;
					font-weight: 500;
					color: #0D0D0D;
				}

				input {
					width: 300rpx;
					font-size: 32rpx;
					font-weight: 400;
					color: #9A9A9A;
				}

				.input_1_right {
					width: 162rpx;
					height: 57rpx;
					background: rgba(221, 180, 92, 0.6);
					border-radius: 29rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FFFFFF;
					line-height: 57rpx;
					text-align: center;
				}
			}

			.input_2 {
				display: flex;
				align-items: center;
				justify-content: space-between;
				border-bottom: 1rpx solid #CCCCCC;
				padding: 30rpx 0;

				.input_2_left {
					font-size: 32rpx;
					font-weight: 500;
					color: #0D0D0D;


				}

				input {
					width: 300rpx;
					font-size: 32rpx;
					font-weight: 400;
					color: #9A9A9A;
				}

				.input_2_right {
					width: 162rpx;
					height: 57rpx;
					background: rgba(221, 180, 92, 0.6);
					border-radius: 29rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FFFFFF;
					line-height: 57rpx;
					text-align: center;
				}
			}
		}

		.wechat {
			.wechat_division {
				width: 80%;
				height: 30rpx;
				margin: 0 auto;
				padding: 40rpx 0;
				font-size: 24rpx
			}

			.wechat_img {
				width: 100rpx;
				height: 100rpx;
				margin: 0 auto;
				margin-top: 40rpx;
			}

			.agreement {
				width: 523rpx;
				height: 66rpx;
				margin: 30rpx auto;
				font-size: 24rpx;
				font-weight: 400;
				color: #9A9A9A;
				display: flex;
				justify-content: space-between;

				text:nth-child(1) {}

				text:nth-child(2) {
					color: #E40030;
				}

				text:nth-child(3) {
					color: #E40030;
				}

				text:nth-child(4) {}

				text:nth-child(5) {
					color: #E40030;
				}
			}
		}
	}
</style>