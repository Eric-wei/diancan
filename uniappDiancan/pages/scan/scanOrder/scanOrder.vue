<template>
	<view>
		<view class="head">
			<view class="head_title">
				巷口妈妈
			</view>
			<view class="">
				<u-tabs :list="tabList" :activeStyle="{color: '#00C0FF',fontWeight: 'bold',transform: 'scale(1.05)' }"
					itemStyle="width:44%;padding:0rpx 36rpx 20rpx 0;textAlign:center;" lineWidth='58'
					@click="click"></u-tabs>
			</view>
		</view>
		<view class="" style="height: 250rpx;">

		</view>


		<view class="refund">
			<view class="refund_text">
				餐座号
			</view>
			<view class="refund_text2">
				B14
			</view>
		</view>
		<block>
			<view class="store">
				<view class="">
					<storelocation :content_list='content_list'></storelocation>
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

				<view class="store_down">
					<view class="store_down_left">
						加菜
					</view>
					<view class="store_down_right">
						立即支付
					</view>
				</view>
			</view>
		</block>

	</view>

</template>

<script>
	export default {
		data() {
			return {
				content_list: {
					store: '云尚艾特米店',
					createtime: '2023-03-15 15:22:07'
				},
				tabList: [{
					name: '当前订单',
				}, {
					name: '历史订单',
				}]
			};
		},
		onLoad(obj) {

		},
		methods: {



		}
	}
</script>

<style lang="scss">
	.head {
		padding: 113rpx 34rpx 0;
		box-sizing: border-box;
		background-color: #fff;
		font-size: 30rpx;
		font-weight: 600;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;

		.head_title {
			margin-bottom: 40rpx;
		}
	}

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
		justify-content: flex-end;
		margin-top: 40rpx;

		.store_down_left {
			width: 207rpx;
			height: 82rpx;
			background: #232323;
			border-radius: 41rpx;
			color: #FFFFFF;
			line-height: 82rpx;
			text-align: center;
			margin-right: 24rpx;
		}

		.store_down_right {
			width: 207rpx;
			height: 82rpx;
			background: #02B6FD;
			border-radius: 41rpx;
			color: #FFFFFF;
			line-height: 82rpx;
			text-align: center;
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

		.refund_text {
			font-size: 26rpx;
			color: #333333;
		}

		.refund_text2 {
			font-size: 54rpx;
			color: #00C4FF;
			margin: 6rpx 0 0;
		}
	}

	::v-deep .u-modal__content {
		padding-top: 0 !important;
	}

	.slot-content {
		padding-top: 20rpx;
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