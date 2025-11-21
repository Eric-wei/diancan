<template>
	<view v-show="display">
		<view class="content">
			<view class="content_0">

			</view>
			<view class="content_1">
				<view class="content_1_top">
					<text v-if="item.status==1">订单待发货 </text>
					<text v-if="item.status==2">订单待收货</text>
					<text v-if="item.status==3">订单已完成</text>
				</view>
				<view class="content_1_buttom">
					感谢您对积分商城的支持，期待您的再次光顾
				</view>
			</view>
			<view class="content_2">
				<view class="content_2_box">
					<view class="content_2_1">
						积分商城
					</view>
					<view class="content_2_2"
						@tap="routerTo('/pages/me_all/integralcommodity/integralcommodity?id='+item.goods_id)">
						<view style="display: flex;">
							<view class="content_2_2_left">
								<image :src="item.image" mode="aspectFill"></image>
							</view>
							<view class="content_2_2_title">
								{{item.goods_name}}
							</view>
						</view>
						<view class="content_2_2_price">
							<view class="">
								{{item.order_amount_total}}积分
							</view>
							<view class="">
								x1
							</view>
						</view>
					</view>
					<view class="content_2_3">
						<view>
							积分抵扣
						</view>
						<view>
							{{item.order_amount_total}}
						</view>
					</view>
					<view class="content_2_4">
						<view class="content_2_4_box">
							<text>合计</text>
							<text> ¥{{item.order_amount_total}}</text>
						</view>
					</view>
				</view>
			</view>

			<view class="content_3">
				<view class="content">
					<view class="content_text">
						<view class="content_text_title">
							订单信息
						</view>
						<view class="content_text_time">
							<text>兑换状态</text>
							<text>已完成</text>
						</view>
						<view class="content_text_location">
							<text>下单时间</text>
							<text>{{item.pay_time}}</text>
						</view>
						<view class="content_text_location">
							<text>物流单号</text>
							<text><text style="color: #282828;">{{item.numbers||'暂未发货'}}</text>
								<text @tap="setClipboardData(item.numbers)" style="color: #A2A2A2 ;">｜复制</text></text>
						</view>
						<view class="content_text_location">
							<text>订单号</text>
							<text><text style="color: #282828;">{{item.order_no}}</text>
								<text @tap="setClipboardData(item.order_no)" style="color: #A2A2A2 ;">｜复制</text></text>
						</view>
						<!-- 						<view class="content_text_location">
							<text>备注信息</text>
							<text>无</text>
						</view> -->
					</view>
				</view>
			</view>
		</view>
		<!-- 	<view class="content_4">
			<view class="content_4_btn">
				立即使用
			</view>
		</view> -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				item: {},
				display: false

			};
		},
		onLoad(obj) {
			this.item = JSON.parse(obj.query.item)
			this.display = true
		}

	}
</script>

<style lang="scss">
	.content {
		width: 692rpx;
		margin: 0 auto;

		.content_1 {
			height: 201rpx;
			background: #FFFFFF;
			border-radius: 16rpx;
			margin: 20rpx 0;
			text-align: center;

			.content_1_top {
				font-size: 32rpx;
				font-weight: 600;
				color: #333333;
				padding-top: 50rpx;
				padding-bottom: 20rpx;
			}

			.content_1_buttom {
				font-size: 24rpx;
				font-weight: 400;
				color: #313131;
			}
		}

		.content_2 {
			width: 692rpx;
			background: #FFFFFF;
			border-radius: 16rpx;

			.content_2_box {
				width: 90%;
				margin: 0 auto;

				.content_2_1 {
					padding: 30rpx 0;
					font-size: 26rpx;
					font-weight: 500;
					color: #313131;
				}

				.content_2_2 {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 30rpx 0;
					border-top: 1rpx solid #f5f5f5;
					border-bottom: 1rpx solid #f5f5f5;

					.content_2_2_left {
						width: 98rpx;
						height: 96rpx;
						border-radius: 4rpx;
						overflow: hidden;
					}

					.content_2_2_title {
						font-size: 24rpx;
						font-weight: 500;
						color: #313131;
						margin-left: 20rpx;
					}

					.content_2_2_price {
						text-align: right;

						view:nth-child(1) {
							font-size: 26rpx;
							font-weight: 400;
							color: #313131;
						}

						view:nth-child(2) {
							font-size: 26rpx;
							font-weight: 400;
							color: #9C9C9C;
							margin-top: 10rpx;
						}
					}
				}

				.content_2_3 {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 25rpx 0;
					border-bottom: 1rpx solid #f5f5f5;

					view:nth-child(1) {
						font-size: 24rpx;
						font-weight: 400;
						color: #3B3B3B;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 600;
						color: #3B3B3B;
					}
				}

				.content_2_4 {
					text-align: right;
					padding: 20rpx 0;

					.content_2_4_box {
						font-size: 26rpx;
						color: #666666;

						text:nth-child(1) {
							color: #666666;
							margin-right: 10rpx;
						}

						text:nth-child(2) {
							font-weight: 600;
						}

					}
				}
			}

		}

		.content_3 {
			.content {
				background: #FFFFFF;
				border-radius: 18rpx;
				margin: 20rpx auto;

				.content_text {
					padding: 20rpx;

					.content_text_title {
						text-indent: 1em;
						border-bottom: 1rpx solid #F7F7F7;
						font-size: 32rpx;
						font-weight: 600;
						color: #333333;
						padding-bottom: 20rpx;
					}

					.content_text_time {
						display: flex;
						align-items: center;
						justify-content: space-between;
						margin: 30rpx 0;

						text:nth-child(1) {
							font-size: 24rpx;
							font-weight: 400;
							color: #9D9D9D;
						}

						text:nth-child(2) {
							font-size: 24rpx;
							font-weight: 400;
							color: #282828;
						}
					}

					.content_text_location {
						display: flex;
						justify-content: space-between;
						margin: 30rpx 0;

						text:nth-child(1) {
							font-size: 24rpx;
							font-weight: 400;
							color: #9D9D9D;
						}

						text:nth-child(2) {
							font-size: 24rpx;
							font-weight: 400;
							color: #282828;
						}
					}
				}
			}
		}

	}

	.content_4 {
		width: 100%;
		height: 143rpx;
		background: #FFFFFF;
		position: fixed;
		bottom: 0;

		.content_4_btn {
			width: 701rpx;
			height: 88rpx;
			margin: 20rpx auto;
			background: #05B6F6;
			border-radius: 44rpx;
			font-size: 26rpx;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 88rpx;
			text-align: center;
		}
	}
</style>