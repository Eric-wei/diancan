<template>
	<view>
		<!-- 配送价格卡牌 -->
		<view class="content"
			@tap="routerTo('/pages/order_all/goodsordersearch/goodsordersearch?id='+item.goods_id+'&store_id='+item.score_id+'&activate_data='+item.type)">
			<view class="content_left">
				<view class="content_left_img">
					<image :src="item.image" mode="aspectFill"></image>
				</view>
				<view class="content_left_title flex jsa">
					<view class="content_left_title_1">
						{{item.title || ''}}
					</view>
					<view class="content_left_title_2">
						{{item.item_name || ''}}
					</view>
				</view>
			</view>
			<view class="content_right flex jsa">
				<view class="flex ae">
					<text class="content_right_priceicon">¥</text>
					<view class="content_right_price">
						{{item.price || 0.00}}
					</view>
				</view>
				
				<view class="content_right_count">
					x{{item.count || 1}}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "expensedetail",
		props: {
			item: {
				type: Object
			}
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		display: flex;
		justify-content: space-between;
		padding: 25rpx;

		.content_left {
			display: flex;

			.content_left_img {
				width: 104rpx;
				height: 104rpx;
				margin-right: 16rpx;

				image {
					border-radius: 8rpx;
				}
			}

			.content_left_title {
				margin-left: 10rpx;
				flex-direction: column;

				.content_left_title_1 {
					font-size: 24rpx;
					// font-weight: bold;
					color: #333;
				}

				.content_left_title_2 {
					font-size: 20rpx;
					font-weight: 400;
					color: #777;
				}
			}
		}

		.content_right {
			text-align: right;
			flex-direction: column;
			.content_right_priceicon{
				font-size: 24rpx;
			}

			.content_right_price {
				font-size: 34rpx;
				font-weight: bold;
				color: #353535;
				line-height: 1;
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