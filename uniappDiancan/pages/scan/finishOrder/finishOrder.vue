<template>
	<view>
		<view class="refund">
			已完成
		</view>
		<block>
			<view class="store">
				<view class="size14 blod">
					云尚艾特米店
				</view>
				<view class="content_detail"
					@tap="routerTo('/pages/order_all/goodsordersearch/goodsordersearch?id='+item.goods_id+'&store_id='+item.score_id+'&activate_data='+item.type)">
					<view class="content_left">
						<view class="content_left_img">
							<image src="@/static/Project_drawing 8.png" mode="aspectFill"></image>
						</view>
						<view class="content_left_title">
							<view class="content_left_title_1">
								【人气尝鲜】祁蒙山…
							</view>
							<view class="content_left_title_2">
								1人份+微辣+炒鸡灵魂方便面两块
							</view>
							<view class="hue1 size12">
								<span>餐座号：B2</span>
								<span style="margin-left:30rpx ;">用餐人数：8</span>
							</view>
						</view>
					</view>
					<view class="content_right">
						<view class="content_right_price">
							¥24.9
						</view>
						<view class="content_right_count">
							x1
						</view>
					</view>
				</view>

				<view class="addTitle blod">
					加餐菜品
				</view>

				<view class="">
					<view v-for="item in 2" class="content_detail">
						<view class="content_left">
							<view class="content_left_img">
								<image src="@/static/Project_drawing 8.png" mode="aspectFill"></image>
							</view>
							<view class="content_left_title">
								<view class="content_left_title_1">
									【人气尝鲜】祁蒙山…
								</view>
								<view class="content_left_title_2">
									1人份+微辣+炒鸡灵魂方便面两块
								</view>
							</view>
						</view>
						<view class="content_right">
							<view class="content_right_price">
								¥24.9
							</view>
							<view class="content_right_count">
								x1
							</view>
						</view>
					</view>
				</view>

				<view class="addBtn">
					更多
				</view>


				<view class="store_down">
					<view class="store_down_left">
						联系商家
					</view>
					<view class="store_down_right">
						<text>实付￥</text>
						<text>0.00</text>
					</view>
				</view>
			</view>


			<view class="content">
				<view class="content_text">
					<view class="content_text_title">
						订单信息
					</view>
					<view class="content_text_time">
						<text>下单门店</text>
						<text>巷口妈妈云尚艾特米店备份</text>
					</view>

					<view class="content_text_location">
						<text>下单时间</text>
						<text>2023-03-16 11:52:06</text>
					</view>
					<view class="content_text_location">
						<text>订单号</text>
						<text><text style="color: #282828;">7210986921231649958</text><text
								@tap="setClipboardData(content_list.order_no)"
								style="color: #A2A2A2 ;">｜复制</text></text>
					</view>
					<view class="content_text_location">
						<text>备注信息</text>
						<text>无</text>
					</view>

				</view>
			</view>

		</block>

	</view>
</template>

<script>
	export default {
		data() {
			return {};
		},
		onLoad(obj) {

		},
		methods: {



		}
	}
</script>

<style lang="scss">
	.store {
		width: 660rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 20rpx auto;
		padding: 32rpx 20rpx;

		.store_left {
			padding: 20rpx 32rpx 0;

			.store_left_1 {
				font-size: 32rpx;
				font-weight: 400;
				color: #393939;
			}

			.store_left_2 {
				font-size: 24rpx;
				font-weight: 400;
				color: #979797;
			}
		}

		.store_content {
			.store_content_text {
				font-size: 24rpx;
				margin-top: 16rpx;
			}

			image {
				width: 103rpx;
				height: 106rpx;
			}
		}
	}

	.addBtn {
		width: 100%;
		height: 31rpx;
		font-weight: 400;
		font-size: 24rpx;
		color: #999999;
		text-align: center;
	}

	.store_down {
		padding: 0 20rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;

		.store_down_left {
			width: 150rpx;
			height: 53rpx;
			border-radius: 7rpx;
			border: 1rpx solid #00CCFF;
			font-size: 22rpx;
			color: #00C8FF;
			line-height: 31rpx;
			text-align: center;
			line-height: 53rpx;
		}

		.store_down_right {
			text:nth-child(1) {
				font-size: 24rpx;
				font-weight: 400;
				color: #1D1D1D;
			}

			text:nth-child(2) {
				font-size: 32rpx;
				font-weight: 500;
				color: #1D1D1D;
			}
		}
	}

	.refund {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 22rpx auto;
		color: #333333;
		font-size: 32rpx;
		padding: 36rpx 0;
		display: block;
		text-align: center;
	}

	::v-deep .u-modal__content {
		padding-top: 0 !important;
	}

	.slot-content {
		padding-top: 20rpx;
	}

	.content {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 0rpx auto;
		margin-top: 20rpx;

		.content_text {
			padding: 20rpx 40rpx;

			.content_text_title {
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

			.record_pay {
				display: flex;
				flex-direction: row-reverse;
				padding-top: 20rpx;
				border-top: 1rpx dashed #EFEFEF;

				view {
					width: 155rpx;
					height: 55rpx;
					background: #00C8FF;
					border-radius: 28rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #F9FDFF;
					line-height: 55rpx;
					text-align: center;
					margin-right: 10rpx;
				}
			}
		}
	}

	.addTitle {
		font-size: 32rpx;
		color: #232323;
		padding-bottom: 12rpx;
		box-sizing: border-box;
		border-bottom: 1rpx solid #F2F2F2;
	}

	.content_detail {
		display: flex;
		justify-content: space-between;
		padding: 25rpx;

		.content_left {
			display: flex;

			.content_left_img {
				width: 103rpx;
				height: 106rpx;
				margin-right: 16rpx;

				image {
					border-radius: 8rpx;
				}
			}

			.content_left_title {
				margin-left: 10rpx;

				.content_left_title_1 {
					font-size: 32rpx;
					font-weight: 400;
					color: #353535;
				}

				.content_left_title_2 {
					font-size: 24rpx;
					font-weight: 400;
					color: #989898;
					margin: 4rpx 0;
				}
			}
		}

		.content_right {
			text-align: right;

			.content_right_price {
				font-size: 32rpx;
				font-weight: 400;
				color: #353535;
			}

			.content_right_count {
				font-size: 24rpx;
				font-weight: 400;
				color: #989898;
				margin-top: 10rpx;
			}
		}
	}
</style>