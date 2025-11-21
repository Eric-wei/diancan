<template>
	<!-- 选择取餐方式 -->
	<view>
		<view class="hade_select">
			<view class="hade_select_top" v-if="select==1">
				<view class="hade_select_top_zq">
					<view class="hade_select_top_zq_left">
						<view class="hade_select_top_zq_left_img">
							<image src="@/static/Project_drawing 9.svg" mode=""></image>
						</view>
						<text>自取</text>
					</view>
					<!-- @tap="selectfun(2)" -->
					<view class="hade_select_top_zq_right">
						<view class="hade_select_top_zq_right_img">
							<image src="@/static/Project_drawing 31.svg" mode=""></image>
						</view>
						<text> 外卖</text>
					</view>
				</view>
			</view>
			<view class="hade_select_down" v-if="select==2">
				<view class="hade_select_top_zq">
					<!-- @tap="selectfun(1)" -->
					<view class="hade_select_top_zq_left">
						<view class="hade_select_top_zq_left_img">
							<image src="@/static/Project_drawing 9.svg" mode=""></image>
						</view>
						<text>自取</text>
					</view>
					<view class="hade_select_top_zq_right">
						<view class="hade_select_top_zq_right_img">
							<image src="@/static/Project_drawing 31.svg" mode=""></image>
						</view>
						<text> 外卖</text>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "select",
		data() {
			return {
				select: uni.getStorageSync('option')
			};
		},
		created() {},
		methods: {
			selectfun(count) {
				this.select = count
				this.$emit('msgclick', count)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.hade_select {

		.hade_select_top {
			.hade_select_top_zq {
				display: flex;
				align-items: center;
				justify-content: space-between;
				background-color: #F2F2F2;

				.hade_select_top_zq_left {
					width: 60%;
					height: 90rpx;
					background: linear-gradient(-100deg, transparent 20rpx, #ffffff 0) top right;
					display: flex;
					align-items: center;
					justify-content: center;

					.hade_select_top_zq_left_img {
						width: 50rpx;
						height: 50rpx;
						padding-right: 10rpx;
					}
				}

				.hade_select_top_zq_right {
					width: 50%;
					height: 50rpx;
					display: flex;
					align-items: center;
					justify-content: center;

					.hade_select_top_zq_right_img {
						width: 50rpx;
						height: 50rpx;
						padding-right: 10rpx;
					}
				}
			}
		}

		.hade_select_down {
			.hade_select_top_zq {
				display: flex;
				align-items: center;
				justify-content: space-between;
				background-color: #F2F2F2;

				.hade_select_top_zq_left {
					width: 50%;
					height: 90rpx;

					display: flex;
					align-items: center;
					justify-content: center;

					.hade_select_top_zq_left_img {
						width: 50rpx;
						height: 50rpx;
						padding-right: 10rpx;
					}
				}

				.hade_select_top_zq_right {
					width: 60%;
					height: 90rpx;
					background: linear-gradient(100deg, transparent 20rpx, #ffffff 0) top left;
					display: flex;
					align-items: center;
					justify-content: center;

					.hade_select_top_zq_right_img {
						width: 50rpx;
						height: 50rpx;
						padding-right: 10rpx;
					}
				}
			}
		}


	}
</style>