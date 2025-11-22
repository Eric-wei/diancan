<template>
	<view class="box" v-show="display">
		<view class="swp">
			<u-swiper :list="list1" :height='500' @change="change" @click="click"></u-swiper>
		</view>
		<view class="title">
			<view class="title_top">
				{{order_detalis.title}}
			</view>
			<view class="title_bottom">
				<view>{{order_detalis.price}}积分 <span
						class="color-9 lineThrough u-font-22">{{order_detalis.line_price}}</span>
				</view>
				<text>剩余{{order_detalis.stock}}</text>
			</view>
		</view>
		<height :hg='100'></height>
		<view class="content_text">
			<view v-html="order_detalis.content">

			</view>
		</view>
		<view class="btn" @tap="routerTo('/pages/me_all/confirm/confirm?id='+order_detalis.id)">
			<view class="btn_text">
				立即兑换
			</view>
		</view>
		<height :hg='187'></height>
	</view>
</template>

<script>
	import {
		point_detail
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				list1: [],
				order_detalis: {},
				display: false
			};
		},
		onLoad(obj) {
			this.point_detailApi(obj.id)

		},
		methods: {
			// 轮播图change事件
			change(e) {},
			// 轮播图点击事件
			click(e) {},
			async point_detailApi(id) {
				let data = await point_detail({
					id: id
				})
				this.list1 = []
				this.list1.push(...data.data.images)
				this.order_detalis = data.data
				uni.setNavigationBarTitle({
					title: this.order_detalis.title //这是修改后的导航栏文字
				})
				this.display = true
			}
		}
	}
</script>

<style lang="scss">
	.title {
		width: 701rpx;
		height: 160rpx;
		background: #FFFFFF;
		border-radius: 22rpx;
		position: absolute;
		top: 430rpx;
		left: 50%;
		transform: translateX(-50%);

		.title_top {
			font-size: 32rpx;
			font-weight: 600;
			color: #333333;
			text-indent: 1em;
			padding: 20rpx 0;
		}

		.title_bottom {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 0 30rpx;

			text:nth-child(1) {
				font-size: 32rpx;
				font-weight: 600;
				color: #05B6F6;
				line-height: 50rpx;

			}

			text:nth-child(2) {
				font-size: 24rpx;
				font-weight: 400;
				color: #9F9F9F;
			}
		}
	}

	.content_text {
		width: 701rpx;
		margin: 0 auto;
		margin-bottom: 30rpx;
		background: #FFFFFF;
		border-radius: 22rpx;
		min-height: 400rpx;
		height: auto !important;
		height: 400rpx;

		view {
			padding: 30rpx;
		}
	}

	.btn {
		width: 100%;
		height: 143rpx;
		background: #FFFFFF;
		position: fixed;
		bottom: 0;

		.btn_text {
			width: 701rpx;
			height: 88rpx;
			background: #E40030;
			border-radius: 8rpx;
			margin: 20rpx auto;
			font-size: 26rpx;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 88rpx;
			text-align: center;
		}
	}
</style>