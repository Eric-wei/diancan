<template>
	<view>
		<view class="hade_vip" v-if="!int.openid">
			完善资料特权
		</view>
		<view class="content_box">
			<view class="content">
				<view>
					<button class="content_img" open-type="chooseAvatar" @chooseavatar="chooseavatar">
						<image :src="img" mode="aspectFill"></image>
					</button>
				</view>
				<height :hg="80"></height>
				<view class="content_input">
					<view>
						昵称
					</view>
					<view>
						<input type="nickname" v-model="name" placeholder="请填写">
					</view>
				</view>
				<view class="content_input">
					<view>
						手机号
					</view>
					<view>
						<input style="color: #000000;" type="text" v-model="cell" placeholder="请填写手机号">
					</view>
				</view>
				<view class="content_input">
					<view>
						性别
					</view>
					<view>
						<u-radio-group v-model="radiovalue1" :iconSize='20' :labelSize='50' :size='30' placement="row"
							@change="groupChange">
							<u-radio :customStyle="{marginBottom: '8px'}" v-for="(item, index) in radiolist1"
								:key="index" :label="item.name" :name="item.name" @change="radioChange">
							</u-radio>
						</u-radio-group>
					</view>
				</view>
				<view class="content_input" style="border-bottom: none;" @tap="show=true">
					<view>
						生日
					</view>
					<view style="display: flex; align-items: center;">
						<view class="">
							{{birthday||'请选择生日'}}
						</view>
						<view class="">
							<u-icon name="arrow-right" color="#303030" size="28"></u-icon>
						</view>
					</view>
				</view>

			</view>
		</view>
		<view class="account">
			<view class="content_input" style="border-bottom: none; padding: 10rpx 0;">
				<view style="width: 80rpx; height: 80rpx;">
					<image src="../../../static/WeChat.png" mode=""></image>
				</view>
				<view style="display: flex; align-items: center;">
					<view class="">
						<!-- {{count}} -->
					</view>
					<view class="">
						<text v-if="int.openid" @tap="binding(0)">已绑定</text>
						<button v-else open-type="getPhoneNumber" @getphonenumber="getphonenumber">
							<text>未绑定</text>
						</button>
					</view>
				</view>
			</view>
		</view>
		<!-- 	<view class="account" @tap="cut">
			<view class="content_input" style="border-bottom: none; ">
				<view>
					退出登录
				</view>
				<view style="display: flex; align-items: center;">
					<view class="">
					</view>
					<view class="">
						<u-icon name="arrow-right" color="#303030" size="28"></u-icon>
					</view>
				</view>
			</view>
		</view> -->
		<view class="save" @tap="sub">
			保存
		</view>
		<u-datetime-picker :show="show" minDate="" :maxDate="Number(new Date())" @cancel="show=false"
			@close='show=false' @confirm='time' v-model="value1" mode="date"></u-datetime-picker>
	</view>
</template>

<script>
	import {
		toDate
	} from "@/utils/time.js"
	import
	uploadImg
	from "@/utils/wxApi.js"
	import {
		user_profile,
		userInfo
	} from "@/api/public.js"
	import {
		user_remove,
		user_binding
	} from "@/api/user.js"
	import
	loginApi
	from '@/utils/wxApi.js'
	export default {
		data() {
			return {
				int: {},
				name: '', //用户名
				cell: '', //电话号
				radiolist1: [{
						name: '男',
						disabled: false
					},
					{
						name: '女',
						disabled: false
					}
				],
				// u-radio-group的v-model绑定的值如果设置为某个radio的name，就会被默认选中
				radiovalue1: '男',
				// 生日
				show: false,
				value1: Number(new Date()),
				birthday: '请选择',
				img: '', //头像
				logincode: ''
			};
		},
		onLoad() {
			// loginApi.loginApi().then(res => {
			// 	this.logincode = res.code
			// })
			let user = uni.getStorageSync('user') //初始化信息
			this.int = user
			this.name = user.username
			this.cell = user.mobile
			this.radiovalue1 = user.mobile = 0 ? '女' : '男'
			this.birthday = user.birthday
			this.img = user.avatar

			if (!uni.getStorageSync('userinfo')) {
				this.enter = true
				uni.showToast({
					title: '未登录',
					icon: 'none'
				})
				return
			}
		},
		onShow() {
			if (!uni.getStorageSync('userinfo')) {
				this.enter = true
				uni.showToast({
					title: '未登录',
					icon: 'none'
				})
				return
			} else {
				this.enter = false
			}
		},
		computed: {
			count() {
				if (this.value1) {
					return toDate(this.value1, 2)
				} else {
					return '请选择'
				}
			}
		},
		methods: {
			groupChange(n) {},
			radioChange(n) {},
			time(time) {
				this.birthday = toDate(time.value, 2)
				this.show = false
			},
			async chooseavatar(e) {
				this.img = e.detail.avatarUrl
				let data = await uploadImg.uploadImg(this.img)
				this.img = data
			},
			async sub() {
				let data = await user_profile({
					username: this.name,
					mobile: this.cell,
					birthday: this.birthday,
					gender: this.radiovalue = '女' ? 0 : 1,
					file: this.img
				})
				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
						icon: "success"
					})
					let user = await userInfo()
					uni.setStorageSync('user', user.data)
					uni.showToast({
						title: data.msg,
						icon: "success",
						duration: 2000,
						success() {
							setTimeout(res => {
								uni.switchTab({
									url: '/pages/me/me'
								})
							}, 2000)
						}
					})
				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})
				}
			},
			getphonenumber(e) {
				this.binding(1, e.detail)
			},
			async binding(index, e) {
				if (index) {
					let code = await loginApi.loginApi()
					this.logincode = code.code
					e.code = this.logincode
					e.iv = encodeURIComponent(e.iv)
					e.encryptedData = encodeURIComponent(e.encryptedData)
					let data = await user_binding(e)
				} else {
					let data = await user_remove()
				}
				let user = await userInfo()
				uni.setStorageSync('user', user.data)
				this.int = user.data
			},
			cut() {
				uni.clearStorageSync()
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
		}
	}
</script>

<style lang="scss">
	.u-radio {
		margin-right: 20rpx;
		margin-bottom: 0 !important;
	}

	.u-radio text {
		font-size: 30rpx !important;
	}

	.hade_vip {
		height: 73rpx;
		background: #F1FBFF;
		font-size: 26rpx;
		font-weight: 400;
		color: #00C8FF;
		line-height: 73rpx;
		text-indent: 2em;
	}

	.content_box {
		width: 705rpx;
		background: #FFFFFF;
		border-radius: 16rpx;
		margin: 0 auto;
		margin-top: 100rpx;

		.content {
			width: 90%;
			margin: 0 auto;
			position: relative;

			.content_img {
				position: absolute;
				left: 50%;
				top: -80rpx;
				transform: translateX(-50%);
				width: 138rpx;
				height: 138rpx;

				image {
					border-radius: 50%;
				}
			}

			.content_input {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 30rpx 0;
				border-bottom: 1rpx solid #eaeaea42;


				view:nth-child(1) {
					font-size: 28rpx;
					font-weight: 400;
					color: #333333;
				}

				view:nth-child(2) {
					input {
						font-size: 28rpx;
						font-weight: 400;
						color: #979797;
						text-align: right;
					}
				}
			}
		}
	}

	.account {
		width: 705rpx;
		background: #FFFFFF;
		border-radius: 16rpx;
		margin: 0 auto;
		margin-top: 10rpx;

		.content_input {
			width: 90%;
			margin: 0 auto;
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 30rpx 0;
			border-bottom: 1rpx solid #EAEAEA;

			view:nth-child(1) {
				font-size: 28rpx;
				font-weight: 400;
				color: #333333;
			}

			view:nth-child(2) {
				input {
					font-size: 28rpx;
					font-weight: 400;
					color: #979797;
					text-align: right;
				}
			}
		}
	}

	.save {
		width: 704rpx;
		height: 102rpx;
		margin: 0 auto;
		background: #00CCFF;
		border-radius: 9rpx;
		font-size: 32rpx;
		font-weight: 400;
		color: #F6FDFF;
		line-height: 102rpx;
		text-align: center;
		margin-top: 80rpx;
	}
</style>