<template>
	<!-- 选择取餐方式 -->
	<view>
		<view class="content">
			<view class="content-nav">
				<view class="content_title flexc">
					<image src="@/static/Project_drawing 9.svg" mode=""></image>
					<text>堂食 【{{seatInfo.title||''}}】</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		seat
	} from "@/api/comm.js"
	export default {
		name: "select",
		data() {
			return {
				select: uni.getStorageSync('option'),
				seatInfo: {}
			};
		},
		created() {
			if (uni.getStorageSync('scene')) {
				this.getseat(uni.getStorageSync('scene'))
			}
		},
		methods: {
			selectfun(count) {
				// this.select = count
				// this.$emit('msgclick', count)
			},
			async getseat(id) {
				let that = this
				let res = await seat({
					id
				})
				if (res.code == 1) {
					that.seatInfo = res.data
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		width: 100%;
		background: #fff;
		white-space: nowrap;
		border-radius: 15rpx;
		overflow: hidden;
	}

	// .content-nav {
	// 	margin-left: -33rpx;
	// }
	.content_title {
		padding: 20rpx 16rpx;
		box-sizing: border-box;
		font-size: 26rpx;

		image {
			width: 32rpx;
			height: 32rpx;
			margin-right: 10rpx;
		}
	}

	.item {
		width: 24%;
		height: 80rpx;
		display: inline-block;
		font-size: 28rpx;
		text-align: center;
		line-height: 80rpx;
		// background: linear-gradient(90deg, #F2F2F2 0%, #fff 100%);
		transform: skew(30deg);
	}

	.item:not(:last-child) {
		border-right: 2px solid #F2F2F2;
	}

	.item-word {

		transform: skew(-30deg);

		.item-word_img {
			width: 34rpx;
			height: 34rpx;
			margin: 0px 4px 0 0;
		}
	}
</style>