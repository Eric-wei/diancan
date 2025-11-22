<template>
	<!-- 商品卡牌 -->
	<view>
		<block>
			<view class="box" v-for="(item,index) in content" :key="item"
				@tap="routerTo('/pages/order_all/goodsordersearch/goodsordersearch2?id='+item.id+'&store_id='+store_id+'&activate_data='+activate_data)">
				<view class="box_left">
					<view class="box_left_img">
						<image :src="item.img" mode="aspectFill"></image>
					</view>
				</view>
				<view class="box_right flex jsb">
					<text class="box_right_title">{{item.title}}</text>

					<view class="box_right_sell">
						桌号：{{item.no}}
					</view>
					<view class="box_right_price">
						<!-- 	<view class="box_right_price_left">
							¥{{item.minprice}}
						</view> -->
						<view class="box_right_price_right">
							<text>预约</text>
						</view>
					</view>
				</view>
			</view>
		</block>
		<login v-if="enter" @loadpage="unloadpage" @closepage='closepage'></login>
	</view>
</template>

<script>
	export default {
		name: "ordercard",
		props: {
			content: {
				type: Array,
				default: [],
				required: true
			},
			store_id: {
				type: String,
				default: ' ',
				required: true
			},
			activate_data: {
				type: Number,
				default: '',
				required: true
			}

		},
		data() {
			return {
				enter: '',
			};
		},
		methods: {
			routergo(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					// uni.showToast({
					// 	title: '请登录',
					// 	icon: 'none'
					// })
					return
				} else {
					uni.navigateTo({
						url: url
					})
				}
			},
			stop(e) {
				this.$emit('specification', e)
			},
			//未登录关闭弹出层需要关掉组件
			closepage() {
				this.enter = false
			},
		}
	}
</script>

<style lang="scss">
	.box {
		padding-bottom: 60rpx;
		display: flex;

		.box_left {
			.box_left_img {
				width: 220rpx;
				height: 218rpx;
				position: relative;

				image {
					border-radius: 11rpx;
				}

				.box_left_img_text {
					width: 63rpx;
					height: 36rpx;
					background: #05B6F6;
					border-radius: 8rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FDFEFF;
					line-height: 36rpx;
					text-align: center;
					padding: 3rpx 5rpx;
					position: absolute;
					top: -10rpx;
					right: -5rpx;
				}
			}
		}

		.box_right {
			width: 300rpx;
			margin-left: 20rpx;
			flex-direction: column;
			padding-bottom: 10rpx;
			box-sizing: border-box;
			.box_right_title {
				font-size: 32rpx;
				font-weight: 600;
				color: #353535;
				display: inline-block;
				margin: 10rpx 0;
			}

			.box_right_ranking {
				width: 182rpx;
				height: 34rpx;
				background: #ECF3FF;
				border-radius: 6rpx;
				line-height: 34rpx;
				text-align: center;
				font-size: 24rpx;
				font-weight: 400;
				color: #05B6F6;
				margin-bottom: 20rpx;
			}

			.box_right_sell {
				font-size: 24rpx;
				font-weight: 400;
				color: #8A8A8A;
				margin-bottom: 20rpx;
			}

			.box_right_price {
				display: flex;
				align-items: center;
				justify-content: space-between;

				.box_right_price_left {
					font-size: 32rpx;
					font-weight: 600;
					color: #353535;
				}

				.box_right_price_right {
					width: 101rpx;
					height: 47rpx;
					background: #E40030;
					border-radius: 8rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FDFEFF;
					line-height: 47rpx;
					text-align: center;
					position: relative;

					.box_right_price_right_count {
						width: 30rpx;
						height: 30rpx;
						background: #F65329;
						border-radius: 50%;
						text-align: center;
						line-height: 30rpx;
						font-size: 24rpx;
						font-weight: 600;
						color: #FFF8F6;
						position: absolute;
						top: -10rpx;
						right: -10rpx;
					}

				}
			}
		}
	}
</style>