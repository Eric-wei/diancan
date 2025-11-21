<template>
	<view>
		<!-- 配送价格卡牌 -->
		<view class="content" v-for="(item1,index) in item.seat_info" :key="index">
			<view class="content_left">
				<view class="content_left_img">
					<image :src="item1.seat_img" mode="aspectFill"></image>
				</view>
				<view class="content_left_title flex jsa">
					<view class="content_left_title_1">
						{{item1.seat_title || ''}}
					</view>
					<view class="content_left_title_2" v-if="item.subscribe_time ">
						{{item.subscribe_time | timerFormat}}
					</view>
					<view class="" v-else>

					</view>
				</view>
			</view>
			<view class="content_right flex jsa">
				<view class="content_right_price">
					¥{{item.order_amount_total || ''}}
				</view>
				<view class="content_right_count">
					x1
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
			},
			sign: {}
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
				width: 103rpx;
				height: 106rpx;

				image {
					border-radius: 8rpx;
				}
			}

			.content_left_title {
				margin-left: 10rpx;
				flex-direction: column;

				.content_left_title_1 {
					font-size: 32rpx;
					font-weight: 400;
					color: #353535;
				}

				.content_left_title_2 {
					font-size: 24rpx;
					font-weight: 400;
					color: #989898;
				}
			}
		}

		.content_right {
			text-align: right;
			flex-direction: column;

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