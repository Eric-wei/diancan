<template>
	<view>
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
				logincode: ''
			}
		},
		onLoad() {
			let user = uni.getStorageSync('user') //初始化信息
			this.int = user
		},
		methods: {
			async binding(index, e) {
				if (index) {
					let code = await loginApi.loginApi()
					this.logincode = code.code
					e.code = code.code
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
			getphonenumber(e) {
				e.detail.code = this.logincode
				this.binding(1, e.detail)
			},
		}
	}
</script>

<style lang="scss" scoped>
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
</style>