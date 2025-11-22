<template>
	<view>
		<view class="content">
			<view class="content_hadrimg">
				<image src="../../static/Big_picture/ckg 1.png" mode=""></image>
			</view>
			<view class="content_main">
				<view class="content_main_box">
					<view class="content_main_box_1">
						<view class="content_main_box_1_left" @tap="routergo('/pages/me_all/personage/personage')">
							<view class="content_main_box_1_left_img">
								<image :src="user.avatar || '../../static/avatar.png'" mode="aspectFill"></image>
							</view>
							<view class="content_main_box_1_left_title">
								<view class="content_main_box_1_left_title_1">
									<text>{{user.username||'请登录'}}</text>
									<text>LV{{user.level||0}}</text>
								</view>
								<view class="content_main_box_1_left_title_2">
									再获得{{user.upgrade||0}}个积分可升级为LV{{user.level+1||0}}
								</view>
								<view class="content_main_box_1_left_title_3">
									{{user.score||0}}/{{user.upgrade||0}}
								</view>
							</view>
						</view>
						<view class="content_main_box_1_right" @tap="routergo('/pages/me_all/codeqr/codeqr')">
							<image src="../../static/Project_drawing 23.png" mode=""></image>
						</view>
					</view>

					<view class="content_main_box_2" v-if="!user.openid"
						@tap="routergo('/pages/me_all/personage/personage')">
						<view class="content_main_box_2_left">
							完善资料特权
						</view>
						<view class="content_main_box_2_right">
							去完善
						</view>
					</view>
				</view>
				<height :hg='!user.openid?100:10'></height>
				<view class="card-box">
					<view class="card" @tap="routergo('/pages/me_all/coupon/coupon')">
						<view class="card_left">
							<text class="card_left_title">优惠券</text>
							<view class="card_left_icon">
								<text>快来使用</text>
								<u-icon name="arrow-right" color="#B46569" size="24"></u-icon>
							</view>
						</view>
						<view class="card_right">
							<image src="../../static/Project_drawing 8.png" mode="aspectFill"></image>
						</view>
					</view>
					<view class="card" style="background: linear-gradient(90deg, #ab69e2 0%, #ab69e2 100%);"
						@tap="routergo('/pages/wallet/wallet')">
						<view class="card_left">
							<text class="card_left_title" style="color: #fff;">钱包</text>
							<view class="card_left_icon" style="color: #fff ;">
								<text>快来使用</text>
								<u-icon name="arrow-right" color="#fff" size="24"></u-icon>
							</view>
						</view>
						<view class="card_right">
							<image src="../../static/Project_drawing_41.png" mode="aspectFill"></image>
						</view>
					</view>

					<view class="card" style="background: linear-gradient(90deg, #F1F6FD 0%, #DDEEFF 100%);"
						@tap="routergo('/pages/me_all/integral/integral')">
						<view class="card_left">
							<text class="card_left_title">积分商城</text>
							<view class="card_left_icon" style="color: #8ABBE1 ;">
								<text>可兑换商品</text>
								<u-icon name="arrow-right" color="#7BBDE5" size="24"></u-icon>
							</view>
						</view>
						<view class="card_right">
							<image src="../../static/Project_drawing 11.png" mode="aspectFill"></image>
						</view>
					</view>
				</view>
				<view class="option">
					<view class="option_content" v-if="user.store_user == 1"
						@tap="gopage('/pages/merchant/index/index')">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="../../static/icon.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">商家中心</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>
					<view class="option_content" @tap="routergo('/pagesB/ageency/ageency')">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="@/static/tuig.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">推广中心</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>
					<view class="option_content"
						@tap="routergo('/pages/order_all/shipping_address/shipping_address?goback='+2)">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="../../static/Project_drawing 19.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">我的地址</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>
					<button open-type="contact" @contact="contact">
						<view class="option_content">
							<view class="option_content_left">
								<view class="option_content_left_icon">
									<image src="../../static/Project_drawing 14.svg" mode=""></image>
								</view>
								<text class="option_content_left_title">官方客服</text>
							</view>
							<view class="option_content_icon">
								<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
							</view>
						</view>
					</button>
					<view class="option_content"
						@tap="routergo('/pages/order_all/login_protocol/login_protocol?name='+'会员协议'+'&state='+0)">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="../../static/Project_drawing 16.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">会员协议</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>

					<view class="option_content"
						@tap="routergo('/pages/me_all/messagenotification/messagenotification')">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="../../static/Project_drawing 17.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">消息通知</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>
					<view class="option_content" @tap="routergo('/pages/me_all/setmessage/setmessage')">
						<view class="option_content_left">
							<view class="option_content_left_icon">
								<image src="../../static/set.svg" mode=""></image>
							</view>
							<text class="option_content_left_title">设置</text>
						</view>
						<view class="option_content_icon">
							<u-icon name="arrow-right" color=" #969696" size="28"></u-icon>
						</view>
					</view>

				</view>
			</view>
		</view>
		<login v-if="enter" @loadpage="unloadpage" @closepage='closepage'></login>
	</view>
</template>

<script>
	import {
		userInfo
	} from "@/api/public.js"
	export default {
		props: ['user'],
		data() {
			return {
				token: uni.getStorageSync('userinfo').token || null,
				enter: false
			};
		},

		methods: {

			gopage(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					// uni.showToast({
					// 	title: '请登录',
					// 	icon: 'none'
					// })
					return
				} else {
					if (this.user.store_user == 1) {
						uni.navigateTo({
							url
						})
					} else {
						uni.$showMsg('您还没有关联店铺')
					}
				}

			},
			contact(e) {},
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
			closepage() {
				this.enter = false
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
	.content {
		position: relative;

		.content_hadrimg {
			width: 100%;
			height: 459rpx;
			position: absolute;
			top: 0;
		}

		.content_main {
			position: absolute;
			top: 450rpx;
			position: relative;

			.content_main_box {
				width: 703rpx;
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 0 0;

				margin: 0 auto;
				position: absolute;
				top: -180rpx;
				left: 50%;
				transform: translateX(-50%);

				.content_main_box_1 {
					width: 645rpx;
					margin: 0 auto;
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 40rpx 0;

					.content_main_box_1_left {
						display: flex;
						margin-left: 10rpx;

						.content_main_box_1_left_img {
							width: 107rpx;
							height: 107rpx;

							image {
								border-radius: 50%;
							}
						}

					}

					.content_main_box_1_left_title {
						margin-left: 20rpx;

						.content_main_box_1_left_title_1 {
							text:nth-child(1) {
								font-size: 32rpx;
								font-weight: 500;
								color: #333333;
							}

							text:nth-child(2) {
								height: 32rpx;
								background: #F1FBFF;
								border-radius: 10rpx 10rpx 10rpx 0rpx;
								font-size: 24rpx;
								font-weight: 400;
								color: #00CCFF;
								padding: 5rpx 10rpx;
								margin-left: 10rpx;
							}
						}

						.content_main_box_1_left_title_2 {
							font-size: 24rpx;
							font-weight: 400;
							color: #5F5F5F;
							line-height: 30rpx;
							padding-top: 10rpx;
						}

						.content_main_box_1_left_title_3 {
							font-size: 24rpx;
							font-weight: 400;
							color: #5F5F5F;
						}
					}
				}

				.content_main_box_1_right {
					width: 60rpx;
					height: 60rpx;
					margin-right: 10rpx;
				}

				.content_main_box_2 {
					width: 645rpx;
					height: 70rpx;
					background: #F1FBFF;
					border-radius: 8rpx;
					margin: 0 auto;
					display: flex;
					align-items: center;
					justify-content: space-between;

					.content_main_box_2_left {
						font-size: 26rpx;
						font-weight: 400;
						color: #00C8FF;
						margin-left: 10rpx;
					}

					.content_main_box_2_right {
						width: 120rpx;
						height: 54rpx;
						background: #00CCFF;
						border-radius: 27rpx;

						font-size: 24rpx;
						font-weight: 400;
						color: #F1FCFF;
						line-height: 54rpx;
						text-align: center;
						margin-right: 10rpx;
					}
				}
			}

			.option {
				width: 703rpx;
				margin: 0 auto;

				.option_content {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 40rpx 0;
					border-bottom: 1rpx solid #F3F3F3;

					.option_content_left {
						display: flex;
						align-items: center;

						.option_content_left_icon {
							width: 34rpx;
							height: 34rpx;
							margin-right: 20rpx;
						}

						.option_content_left_title {
							font-size: 32rpx !important;
							font-weight: 400 !important;
							color: #1A1A1A !important;
						}
					}

				}
			}
		}

		.card-box {
			width: 703rpx;
			height: 138rpx;
			margin: 20rpx auto 0;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.card {
				width: 31%;
				height: 138rpx;
				background: linear-gradient(90deg, #FDEFEC 0%, #FBE1E5 100%);
				border-radius: 16rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;
				position: relative;


				.card_left {
					position: absolute;
					left: 0;
					z-index: 2;
					padding-left: 20rpx;

					.card_left_title {
						font-size: 32rpx;
						font-weight: 500;
						color: #020202;

					}

					.card_left_icon {
						z-index: 2;
						display: flex;
						align-items: center;

						font-size: 24rpx;
						font-weight: 400;
						color: #A9696B;
						padding-top: 10rpx;
					}
				}

				.card_right {
					width: 106rpx;
					height: 68rpx;
					position: absolute;
					right: 0;
					bottom: 0;

					image {
						border-radius: 0 16rpx 16rpx 0;
					}
				}
			}
		}


	}
</style>