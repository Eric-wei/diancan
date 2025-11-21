<template>
	<view>
		<view class="content">
			<view style="margin-top: 450rpx;" v-if="coupon_list.length<1">
				<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无内容'></u-empty>
			</view>
			<view class="content_box" v-for="(item,index) in coupon_list" :key="item"
				:style="{backgroundImage: `url(${Httpimg}couponBack.png)`,}" v-if="Httpimg">
				<view>
					<view class="content_box_1 fllex jsb ">
						<view class="content_box_1_right">
							<view>
								{{item.title}}
							</view>
							<view>
								{{item.draw_range}}
							</view>
							<!-- <view>
								{{item.draw_range}}
							</view> -->
						</view>
						<view class="content_box_1_left">
							<view>
								{{item.used_amount}}<text style="font-size: 24rpx;">元</text>
							</view>
							<view>
								满{{item.with_amount}}元可用
							</view>
						</view>

					</view>
					<view class="content_box_2">
						<view class="content_box_2_left" @tap="rule(index)">
							<view>使用规则</view>
							<view class="">
								<image src="../../../static/Project_drawing 18.png" mode=""></image>
							</view>
							<view class="rule" v-show="item.show">
								{{item.remarks}}
							</view>
						</view>
						<view class="content_box_2_right" @tap="gonavget">
							立即使用
						</view>
					</view>

				</view>
			</view>
			<height :hg="20"></height>
		</view>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		coupon_list
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				coupon_list: [],
				Httpimg: config.HttpImg,
			};
		},
		onLoad() {
			this.coupon_listApi()
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
		methods: {
			rule(index) {
				if (!this.coupon_list[index].remarks) {
					uni.showToast({
						title: '无使用规则',
						icon: "none"
					})
					return
				}
				if (this.coupon_list[index].show) {
					this.coupon_list[index].show = 0
				} else {
					this.coupon_list[index].show = 1
				}
			},
			async coupon_listApi() {
				let data = await coupon_list()
				if (data.code == 1) {
					this.coupon_list = []
					this.coupon_list.push(...data.data)
				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})
				}
			},
			gonavget() {
				uni.switchTab({
					url: '/pages/order/order'
				})
			},

		},

	}
</script>

<style lang="scss" scoped>
	.content_box {
		width: 702rpx;
		// height: 230rpx;
		margin: 20rpx auto;
		border-radius: 20rpx;
		background-size: 100% 100%;
		background-repeat: no-repeat;
		padding: 24rpx 20rpx;
		box-sizing: border-box;

		.content_box_1 {
			display: flex;
			// align-items: center;
			// height: 160rpx;
			padding: 24rpx 0;
			box-sizing: border-box;

			.content_box_1_left {
				text-align: center;
				// padding-left: 20rpx;
				// padding-top: 20rpx;

				view:nth-child(1) {
					font-size: 40rpx;
					font-weight: 600;
					color: #FA5151;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #979797;
					margin-top: 8rpx;
				}
			}

			.content_box_1_right {
				// padding-left: 40rpx;
				// padding-top: 30rpx;

				view:nth-child(1) {
					font-size: 30rpx;
					color: #333333;
					font-weight: bold;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					color: #777777;
					margin-top: 8rpx;
				}

				view:nth-child(3) {
					font-size: 24rpx;
					color: #777777;
					margin-top: 12rpx;
				}
			}
		}

		.content_box_2 {
			display: flex;
			align-items: center;
			justify-content: space-between;
			border-top: 1rpx solid #F6F6F6;
			margin-top: 20rpx;
			padding-top: 15rpx;

			.content_box_2_left {
				display: flex;
				align-items: center;
				position: relative;

				view:nth-child(1) {
					font-size: 24rpx;
					font-weight: 400;
					color: #343434;
				}

				view:nth-child(2) {
					width: 33rpx;
					height: 33rpx;
					margin-left: 10rpx;
				}
			}

			.content_box_2_right {
				width: 151rpx;
				height: 58rpx;
				background: #E40030;
				border-radius: 8rpx;

				font-size: 24rpx;
				font-weight: 400;
				color: #FFFFFF;
				line-height: 58rpx;
				text-align: center;
			}
		}

		.rule {
			width: 299rpx;
			background: #FFFFFF;
			box-shadow: 0rpx 2rpx 10rpx 0rpx rgba(0, 0, 0, 0.13);
			border-radius: 8rpx;
			position: absolute;
			bottom: -30rpx;
			left: 140rpx;
			padding: 20rpx 20rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #A0A0A0;
		}
	}
</style>