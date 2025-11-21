<template>
	<view>
		<!-- 购买积分商品的确认订单 -->
		<view class="">
			<view class="main">
				<height :hg='30'></height>
				<view class="hade"
					@tap="routerTo('/pages/order_all/shipping_address/shipping_address?exchange=1&goback='+1+'&item='+JSON.stringify(content))">
					<view class="hade_left">
						<view class="hade_left_1">
							{{address_data.address+address_data.remark||'请先填写地址，用于商品配送'}}
						</view>
						<view class="hade_left_2" v-if='address_data.address'>
							<text>{{address_data.title}}</text>
							<text>{{address_data.mobile}}</text>
						</view>
					</view>
					<view class="hade_right">
						<u-icon name="arrow-right" color="#979797" size="28"></u-icon>
					</view>
				</view>
				<height :hg='30'></height>
				<view class="content_2">
					<view class="content_2_box">
						<view class="content_2_1">
							积分商城
						</view>
						<view class="content_2_2">
							<view style="display: flex;">
								<view class="content_2_2_left">
									<image :src="content.images[0]" mode=""></image>
								</view>
								<view class="content_2_2_title">
									{{content.title}}
								</view>
							</view>
							<view class="content_2_2_price">
								<view class="">
									{{content.price}}积分
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
								{{content.price}}
							</view>
						</view>
						<view class="content_2_4">
							<view class="content_2_4_box">
								<text> 合计</text>
								<text>￥{{content.price}}</text>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>

		<view class="bnt">
			<view class="bnt_box">
				<view class="bnt_box_left">
					<text>合计:</text>
					<text>¥{{content.price}}积分</text>
				</view>
				<view class="bnt_box_right" @tap="sub">
					立即兑换
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		point_exchange
	} from "@/api/comm.js"
	import {
		point_detail
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				content: {},
				address_data: {}
			};
		},
		onLoad(obj) {
			// if (obj.query.address) {
			// 	this.address_data = JSON.parse(obj.query.address)
			// }
			this.point_detailApi(obj.id)
		},
		onShow() {
			let pages = getCurrentPages();
			let currPage = pages[pages.length - 1]; // 获取当前页面
			if (currPage.$item) {
				this.address_data = currPage.$item.address
				// this.content = currPage.$item.item
			}


		},
		methods: {
			async point_detailApi(id) {
				let data = await point_detail({
					id: id
				})
				if (data.code == 1) {
					this.content = data.data
				}
			},
			async sub() {
				let data = await point_exchange({
					id: this.content.id,
					address_id: this.address_data.id
				})
				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
						icon: "none",
						success() {
							setTimeout(res => {
								uni.reLaunch({
									url: '/pages/me_all/convert/convert'
								})
							}, 2000)
						}
					})
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			}
		}
	}
</script>

<style lang="scss">
	.main {
		width: 692rpx;
		margin: 0 auto;

		.hade {
			display: flex;
			align-items: center;
			justify-content: space-between;
			background: #FFFFFF;
			border-radius: 24rpx;
			padding: 20rpx 0;

			.hade_left {
				padding: 0 20rpx;

				.hade_left_1 {
					font-size: 24rpx;
					font-weight: 400;
					color: #131313;
					height: 60rpx;
					line-height: 60rpx;
				}

				.hade_left_2 {
					text:nth-child(1) {
						font-size: 26rpx;
						font-weight: 400;
						color: #656565;
					}

					text:nth-child(2) {
						font-size: 26rpx;
						font-weight: 400;
						color: #959595;
						margin-left: 10rpx;
					}
				}
			}

			.hade_right {
				padding: 0 40rpx;
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
					padding: 20rpx 0;
					border-top: 1rpx solid #dfdfdf40;
					border-bottom: 1rpx solid #dfdfdf40;

					.content_2_2_left {
						width: 98rpx;
						height: 96rpx;

						image {
							border-radius: 4rpx;
						}
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
					border-bottom: 1rpx solid #dfdfdf40;

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
	}

	.bnt {
		width: 100%;
		height: 127rpx;
		background: #FFFFFF;
		position: fixed;
		bottom: 0;

		.bnt_box {
			width: 90%;
			margin: 20rpx auto;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.bnt_box_left {
				text:nth-child(1) {
					font-size: 26rpx;
					font-weight: 400;
					color: #313131;
				}

				text:nth-child(2) {
					font-size: 30rpx;
					font-weight: 500;
					color: #F65329;
					margin-left: 10rpx;
				}
			}

			.bnt_box_right {
				width: 207rpx;
				height: 82rpx;
				background: #E40030;
				border-radius: 8rpx;
				font-size: 28rpx;
				font-weight: 400;
				color: #fff;
				line-height: 82rpx;
				text-align: center;
			}
		}
	}
</style>