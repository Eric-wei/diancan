<template>
	<view>
		<view class="conten">
			<u-search :showAction="true" v-model="search" placeholder="想点什么搜一搜" :searchIconSize='40' :height='70'
				@custom='searchall' @change='inputchange' @search='searchall' actionText="搜索"
				:animation="true"></u-search>
			<view class="content" v-show="order_list.length<1">
				<view class="content_left" v-if="store_hotSearchApi_data.length>0">
					<text>热门搜索</text>
				</view>
				<view class="content_right">
					<text v-for="(item,index) in store_hotSearchApi_data" :key="item" @tap='label(item)'>{{item}}</text>
				</view>
			</view>
			<view class="content" v-show="order_list.length<1">
				<view class="content_left" v-if="store_historyApi_data.length>0">
					<text>历史搜索</text>
					<text @tap="store_delHistoryApi">清除所有</text>
				</view>
				<view class="content_right">
					<text v-for="(item,index) in store_historyApi_data " :key="item"
						@tap='label(item.title)'>{{item.title}}</text>
				</view>
			</view>
		</view>
		<view class="box flex-jb" v-for="(item,index) in order_list"
			@tap="routerTo('/pages/order_all/goodsordersearch/goodsordersearch?id='+item.id+'&store_id='+store_id)"
			:key="item.id">
			<view class="box_left ">
				<view class="box_left_img">
					<image :src="item.image" mode="aspectFill"></image>
					<!-- <view class="box_left_img_text" v-if="item.tags"> {{item.tags}}</view> -->
				</view>
			</view>
			<view class="box_right flex jsb">
				<view class="box_right_title line-1">{{item.title}}</view>
				<view class="flex ac">
					<view class="box_right_ranking">
						{{item.tags}}
					</view>
				</view>
				
				<view class="box_right_sell">
					{{item.desc}}
				</view>
				<view class="box_right_price ae">
					<view class="box_right_price_left">
						<text class="box_right_price_leftl">
							¥
						</text>
						<text class="box_right_price_leftb">
							{{item.minprice}}
						</text>
						<text class="box_right_price_leftl">
							起
						</text>
					</view>
					<view class="box_right_price_left-little">
						¥{{item.line_price}}起
					</view>
				</view>
			</view>
		</view>

	</view>
</template>

<script>
	import {
		store_history,
		store_delHistory,
		store_hotSearch,
		store_goodsSearch
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				search: '', //搜索内容
				store_id: '', //店铺id
				store_historyApi_data: [], //历史搜索
				store_hotSearchApi_data: [], //热门搜索
				order_list: []
			};
		},
		onLoad(obj) {
			this.store_id = obj.id
			let token = uni.getStorageSync('userinfo')
			if (token) {
				this.store_historyApi()
				this.store_hotSearchApi()
			}

		},
		methods: {
			async searchall() {
				let data = await store_goodsSearch({
					store_id: this.store_id,
					search: this.search,
					type: uni.getStorageSync('option')
				})
				if (data.data.length) {
					this.order_list = []
					this.order_list.push(...data.data)
				} else {
					uni.showToast({
						title: '无商品',
						icon: "none"
					})
				}
				this.store_historyApi()
			},
			inputchange() {
				if (this.search.length <= 0) {
					this.order_list = []
				}
			},
			async store_historyApi() {
				let data = await store_history({
					id: this.store_id
				})
				this.store_historyApi_data = []
				this.store_historyApi_data.push(...data.data)
			},
			async store_delHistoryApi() {
				let data = await store_delHistory({
					id: this.store_id
				})
				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
					})
				} else {
					uni.showToast({
						title: data.msg,
					})
				}
				this.store_historyApi()
			},
			async store_hotSearchApi() {
				let data = await store_hotSearch({
					id: this.store_id
				})
				this.store_hotSearchApi_data = []
				this.store_hotSearchApi_data.push(...data.data)
			},
			label(name) {
				this.search = name
				this.searchall()
			}
		}
	}
</script>
<style>
	page {
		background: #fff;
	}
</style>
<style lang="scss">
	.conten {
		width: 95%;
		margin: 0 auto;
	}

	.content {
		margin: 50rpx 0;

		.content_left {
			font-size: 24rpx;
			color: #A6A6A6;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.content_right {
			width: 100%;
			display: flex;
			align-items: center;
			flex-wrap: wrap;

			text {
				background-color: #F5F5F5;
				padding: 10rpx 30rpx;
				border-radius: 12rpx;
				font-size: 24rpx;
				color: #000;
				margin: 20rpx 20rpx 20rpx 0;
				display: inline-block;
			}
		}
	}

	.box {
		padding: 0 20rpx;
		margin: 30rpx auto;
		display: flex;

		.box_left {
			.box_left_img {
				width: 176rpx;
				height: 176rpx;
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
			width: calc(100% - 176rpx - 20rpx);
			height: 176rpx;
			flex-direction: column;

			.box_right_title {
				font-weight: bold;
				font-size: 28rpx;
				color: #333333;
				display: inline-block;
			}

			.box_right_ranking {
				padding: 4rpx 8rpx;
				border-radius: 4rpx 4rpx 4rpx 4rpx;
				border: 2rpx solid #DADADA;
				font-size: 20rpx;
				color: #777777;
			}

			.box_right_sell {
				font-size: 24rpx;
				font-weight: 400;
				color: #8A8A8A;
			}

			.box_right_price {
				display: flex;
				line-height: 1;
				

				.box_right_price_left {
					margin-right: 10rpx;


					.box_right_price_leftl {
						font-size: 24rpx;
						color: #FA5151;
					}

					.box_right_price_leftb {
						font-size: 34rpx;
						color: #FA5151;
						font-weight: bold;

					}
				}

				.box_right_price_left-little {
					font-size: 24rpx;
					color: #AAAAAA;
					text-decoration: line-through;
				}

				.box_right_price_right {
					width: 101rpx;
					height: 47rpx;
					background: #05B6F6;
					border-radius: 17rpx;
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