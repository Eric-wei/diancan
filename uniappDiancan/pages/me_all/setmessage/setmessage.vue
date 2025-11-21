<template>
	<view>
		<view class="choice">
			<view class="choice_box" @tap="routerTo('/pages/me_all/binding/binding')">
				<view class="choice_box_left">
					<text>第三方账号设置</text>
				</view>
				<view class="choice_box_right" style="display: flex; align-items: center;">
					<image src="@/static/WeChat.png" mode="" style="width: 50rpx; height: 50rpx; margin-right: 10rpx;">
					</image>
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>

			<view style="height: 20rpx; background-color: #F9F9F9;">
			</view>
			<view class="choice_box" @tap="routerTo('/pages/me_all/Integraldetail/Integraldetail')">
				<view class="choice_box_left">
					<text>积分明细</text>
				</view>
				<view class="choice_box_right">
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>
			<view class="choice_box" @tap="routerTo('/pages/me_all/recharge/recharge')">
				<view class="choice_box_left">
					<text>充值记录</text>
				</view>
				<view class="choice_box_right">
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>

			<view class="choice_box" @tap="routerTo('/pagesB/expenseCalendar/expenseCalendar')">
				<view class="choice_box_left">
					<text>消费记录</text>
				</view>
				<view class="choice_box_right">
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>


			<view class="choice_box" @tap="routerTo('pages/me_all/convert/convert')">
				<view class="choice_box_left">
					<text>兑换记录</text>
				</view>
				<view class="choice_box_right">
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>
			<view class="choice_box"
				@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'关于我们'+'&state=0')">
				<view class="choice_box_left">
					<text>关于我们</text>
				</view>
				<view class="choice_box_right">
					<u-icon name="arrow-right" color="#6B6B6B" size="14"></u-icon>
				</view>
			</view>

		</view>

		<view class="btn" @tap="secede" v-if="sign==0">
			退出登录
		</view>
		<view class="btn" @tap="goidx" v-if="sign==1">
			返回客户端
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				sign: 0
			};
		},
		onLoad(e) {
			if (e.sign) {
				this.sign = e.sign
			}
		},
		methods: {
			secede() {
				// this.islogin = false;
				this.$store.commit('setislogin',false)
				uni.clearStorageSync()
				uni.setStorageSync('option', 1)
				uni.setStorageSync('tableNumber', false)
				uni.setStorageSync('addAdish', false)
				uni.switchTab({
					url: '/pages/index/index'
				})


			},
			goidx() {
				uni.reLaunch({
					url: '/pages/index/index'
				})
			},
		}
	}
</script>
<style>
	page {
		background: #F9F9F9;
	}
</style>
<style lang="scss" scoped>
	.choice {
		width: 684rpx;
		background: #FFFFFF;
		border-radius: 6rpx;
		margin: 0 auto;
		margin-top: 19rpx;
		margin-bottom: 40rpx;

		.choice_box {
			margin: 0 32rpx;
			padding: 30rpx 0;
			display: flex;
			align-items: center;
			justify-content: space-between;

			&:nth-child(4) {
				margin-bottom: 0rpx;
			}

			.choice_box_left {
				font-size: 30rpx;
				font-weight: 400;
				color: #343537;
				margin-left: 20rpx;
			}
		}
	}

	.btn {
		width: 700rpx;
		height: 125rpx;
		background: #FFFFFF;
		border-radius: 16rpx;
		margin: 0 auto;
		font-size: 32rpx;
		font-weight: 400;
		color: #F5422F;
		text-align: center;
		line-height: 125rpx;
	}
</style>