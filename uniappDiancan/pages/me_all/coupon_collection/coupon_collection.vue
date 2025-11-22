<template>
	<view>
		<view class="hade">
			<view class="tabnav">
				<navbar background={} backIconColor='#FFFFFF'></navbar>
			</view>
			<view class="hade_img">
				<image :src="pub.con_img" mode=""></image>
			</view>
			<view class="counp_content">
				<height :hg="20"></height>
				<text class="counp_content_title">限时抢券</text>
				<view class="content" v-if="Httpimg">
					<view style="margin-top: 150rpx;" v-if="coupon_list.length<1">
						<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
							text='暂无内容'></u-empty>
					</view>
					<view class="content_box" :style="{backgroundImage: `url(${Httpimg}couponBack.png)`,}"
						v-for="(item,index) in coupon_list" :key="item">
						<view>
							<view class="content_box_1 flex jsb ">
								<view class="content_box_1_right">
									<view>
										{{item.title}}
									</view>
									<view>
										{{item.draw_range}}
									</view>
								</view>
								<view class="content_box_1_left">
									<view>
										{{item.used_amount}}<text style="font-size: 24rpx;">元</text>
									</view>
									<view>
										满{{item.with_amount}}元可用
									</view>
								</view>

							</view>
							<view class="content_box_2">
								<view class="content_box_2_left" @tap="rule(index)">
									<view>使用规则</view>
									<view class="">
										<image src="../../../static/Project_drawing 18.png" mode=""></image>
									</view>
									<view class="rule" v-show="item.show">
										{{item.remarks}}
									</view>
								</view>
								<view class="content_box_2_right" v-if="item.status" @tap="coupon_drawApi(item.id)">
									领取
								</view>
								<view class="content_box_2_right" v-else>
									不可领
								</view>
							</view>

						</view>
					</view>
					<height :hg="20"></height>
				</view>
			</view>
		</view>
		<login :show="enter" @loadpage="unloadpage" @closepage='enter=false'></login>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		coupon_couponList,
		coupon_draw
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				coupon_list: [],
				pub: uni.getStorageSync('public'),
				Httpimg: config.HttpImg,
				enter: false,
			};
		},
		onLoad() {
			this.coupon_couponListApi()
		},
		methods: {
			async coupon_couponListApi() {
				let data = await coupon_couponList()
				this.coupon_list = []
				this.coupon_list.push(...data.data)
			},
			rule(index) {
				if (!this.coupon_list[index].remarks) {
					uni.showToast({
						title: '无使用用规则',
						icon: 'none'
					})
					return
				}
				if (this.coupon_list[index].show) {
					this.coupon_list[index].show = 0
				} else {
					this.coupon_list[index].show = 1
				}
			},
			async coupon_drawApi(id) {
				uni.$once('loginOpen', () => {
					// console.log('需要登录')
					this.enter = true
				})
				// console.log(1111)
				let data = await coupon_draw({
					id
				})
				if (data.code == 1) {
					this.coupon_couponListApi()
					uni.showToast({
						title: data.msg,
						icon: "success"
					})
				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})

				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.hade {
		position: relative;

		.tabnav {
			width: 100%;
			position: absolute;
			top: 0;
			z-index: 2;
		}

		.hade_img {
			width: 100%;
			height: 742rpx;
		}

		.counp_content {
			background: #FAFAFA;
			border-radius: 38rpx 38rpx 0rpx 0rpx;
			position: relative;
			bottom: 60rpx;

			.counp_content_title {
				font-size: 36rpx;
				font-weight: 500;
				color: #3A3A3A;
				padding: 30rpx 30rpx;
			}

			.content_box {
				width: 702rpx;
				// height: 230rpx;
				// background: #FFFFFF;
				margin: 20rpx auto;
				border-radius: 20rpx;
				padding: 24rpx 20rpx;
				box-sizing: border-box;
				background-size: 100% 100%;
				background-repeat: no-repeat;

				.content_box_1 {
					display: flex;
					// align-items: center;
					// height: 160rpx;
					padding: 24rpx 0;
					box-sizing: border-box;

					.content_box_1_left {
						text-align: center;
						// padding-left: 20rpx;
						// padding-top: 20rpx;

						view:nth-child(1) {
							font-size: 40rpx;
							font-weight: 600;
							color: #FA5151;
						}

						view:nth-child(2) {
							font-size: 24rpx;
							font-weight: 400;
							color: #979797;
						}
					}

					.content_box_1_right {
						// padding-left: 40rpx;
						// padding-top: 30rpx;

						view:nth-child(1) {
							font-size: 30rpx;
							font-weight: bold;
							color: #333;
							white-space: nowrap;
							overflow: hidden;
							text-overflow: ellipsis;
						}

						view:nth-child(2) {
							font-size: 24rpx;
							font-weight: 400;
							color: #A0A0A0;
							margin-top: 8rpx;
						}
					}
				}

				.content_box_2 {
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-top: 1rpx solid #F6F6F6;
					margin-top: 20rpx;
					padding-top: 15rpx;

					.content_box_2_left {
						display: flex;
						align-items: center;
						position: relative;

						view:nth-child(1) {
							font-size: 24rpx;
							font-weight: 400;
							color: #343434;
						}

						view:nth-child(2) {
							width: 33rpx;
							height: 33rpx;
							margin-left: 9rpx;
						}
					}

					.content_box_2_right {
						width: 151rpx;
						height: 58rpx;
						background: #E40030;
						border-radius: 8rpx;

						font-size: 24rpx;
						font-weight: 400;
						color: #FFFFFF;
						line-height: 58rpx;
						text-align: center;
					}
				}

				.rule {
					width: 299rpx;
					background: #FFFFFF;
					box-shadow: 0rpx 2rpx 10rpx 0rpx rgba(0, 0, 0, 0.13);
					border-radius: 8rpx;
					position: absolute;
					bottom: -30rpx;
					left: 140rpx;
					padding: 20rpx 20rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #A0A0A0;
				}
			}
		}
	}
</style>