<template>
	<view>
		<view class="content">
			<view class="headerplank">

			</view>
			<view class="content_hadrimg">

			</view>
			<view class="content_main">
				<view class="content_main_box">
					<view class="content_main_box_1">
						<view class="content_main_box_1_left">
							<view class="content_main_box_1_left_avatar"
								@tap.stop="routergo('/pages/me_all/personage/personage')">

								<view class="content_main_box_1_left_img" v-if="Httpimg">
									<image :src="user.avatar || Httpimg+'image.png'" mode="aspectFill"></image>
								</view>

							</view>
							<view class=" flex jsb ac w100h100 content_main_box_1_rights">
								<view class="content_main_box_1_right_code ">
									<view class="content_main_box_1_right"
										@tap.stop="routergo('/pages/me_all/codeqr/codeqr')">
										<image :src="Httpimg+'code.png'" mode="" v-if="Httpimg"></image>
									</view>
									<view class="content_main_box_1_right_codetext">
										会员码
									</view>
								</view>


								<view class="content_main_box_1_left_title flexColumn">
									<view class="content_main_box_1_left_title_1 flexc"
										@tap.stop="routergo('/pages/me_all/personage/personage')">
										<view class="me-text-beyond content_main_box_1_left_title_1_text">
											<text v-if="user.username">
												{{'HI,'+user.username||'请登录'}}
											</text>
											<text v-else>
												请登录
											</text>
										</view>
									</view>
									<view class="flex content_main_box_1_left_title_2sb">
										<view class="content_main_box_1_left_title_2 ">
											LV {{user.level||0}}
											<!-- 再获得user.upgrade||0 个积分可升级为LV +1||0 -->
										</view>
									</view>


									<view class="flex content_main_box_1_left_title_2s">
										<view class="content_main_box_1_left_title_22 ">
											再获得{{user.upgrade||0}} 个积分可升级为 LV {{user.level*1+1||0}}
										</view>
									</view>


								</view>

							</view>
							<view class="userdetailline">

							</view>
							<view class="pointsCoupon flex ac jsb">
								<view class="pointsCouponli pointsCouponlileft flex jsb ac"
									@tap.stop="routergo('/pages/me_all/integral/integral')">
									<view class="pointsCouponlil flex ac">
										<image class="pointsCouponliimg" :src="Httpimg+'me1.png'" mode=""
											v-if="Httpimg"></image>
										<view class="pointsCouponlitt">
											积分
										</view>
									</view>
									<view class="pointsCouponlinum">
										{{user.score || 0}}
									</view>
								</view>
								<view class="pointsCouponli flex jsb ac"
									@tap.stop="routergo('/pages/me_all/coupon/coupon')">
									<view class="pointsCouponlil flex ac">
										<image class="pointsCouponliimg" :src="Httpimg+'me2.png'" mode=""
											v-if="Httpimg"></image>
										<view class="pointsCouponlitt">
											优惠券
										</view>
									</view>
									<view class="pointsCouponlinum">
										{{user.coupon_num || 0}}
									</view>
								</view>
							</view>
						</view>

					</view>


				</view>


				<view class="card">
					<view class="card_title">
						我的功能
					</view>
					<view class="card_groud flexw">
						<view class="card_groud_item" v-if="user.store_user == 1"
							@tap="gopage('/pages/merchant/takeout/order',1),getjc_message()">
							<image :src="Httpimg+'mef11.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								商家中心
							</view>
						</view>
						<view class="card_groud_item" v-if="user.store_user == 1"
							@tap="routergo('/pages/me_all/integral/integral')">
							<image src="/static/images/jifensc.png" mode=""></image>
							<view class="">
								积分商城
							</view>
						</view>
						<view class="card_groud_item" v-if="public.isqb==1" @tap="routergo('/pages/wallet/wallet')">
							<image src="/static/images/wallet.png" mode="" v-if="Httpimg"></image>
							<view class="">
								钱包
							</view>
						</view>
						<view class="card_groud_item"
							@tap="routergo('/pages/order_all/shipping_address/shipping_address?goback='+2)">
							<image :src="Httpimg+'mef1.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								地址管理
							</view>
						</view>
						<view class="card_groud_item"
							@tap="routergo('/pages/me_all/coupon_collection/coupon_collection')">
							<image :src="Httpimg+'mef2.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								领券中心
							</view>
						</view>
						<view class="card_groud_item" @tap="associationshow=true">
							<image :src="Httpimg+'mef3.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								加入社群
							</view>
						</view>
						<view class="card_groud_item"
							@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'关于我们'+'&state='+0)">
							<image :src="Httpimg+'mef5.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								关于我们
							</view>
						</view>
						<button open-type="contact" @contact="token?contact:''">
							<view class="card_groud_item" style="width: 160rpx;">
								<image :src="Httpimg+'mef6.png'" mode="" v-if="Httpimg"></image>
								<view class="">
									官方客服
								</view>
							</view>
						</button>
						<view class="card_groud_item"
							@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'会员协议'+'&state='+0)">
							<image :src="Httpimg+'mef4.png'" mode="" v-if="Httpimg"></image>
							<view class="">
								会员协议
							</view>
						</view>
						<view class="card_groud_item"
							@tap="routergo('/pages/me_all/messagenotification/messagenotification')">
							<image src="/static/images/xiaoxi.png" mode="" v-if="Httpimg"></image>
							<view class="">
								消息通知
							</view>
						</view>
						<view class="card_groud_item" <view class="card_groud_item"
							@tap="routergo('/pagesB/ageency/ageency')" v-if="Httpimg">
							<image src="/static/images/tuiguang.png" mode=""></image>
							<view class="">
								推广中心
							</view>
						</view>
						<view class="card_groud_item" @tap="routergo('/pages/me_all/setmessage/setmessage')">
							<image src="/static/images/shezhi.png" mode=""></image>
							<view class="">
								我的设置
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="cardss  mt24">
				<view class="card_3">
					<u-swiper :list="list" keyName="image" :indicator='true' indicatorMode='dot' :height='240'
						@click="swp"></u-swiper>
				</view>
				<!-- <view class="beianhao" v-if="common.mini_filings">
					备案号：{{common.mini_filings || ''}}
				</view> -->
			</view>
		</view>

		<u-popup :show="associationshow" :safeAreaInsetBottom="false" closeable round="20" :safeAreaInsetTop="false"
			mode="center" @close="associationshow=false">
			<view class="associationimg">
				<view class="associationimgtitle flex jc ac">
					加入社群
				</view>
				<image class="w100h100 associationimgimg" show-menu-by-longpress :src="public.join_img" mode="widthFix">
				</image>
				<view class="associationimgtips flex jc ac">
					长按保存
				</view>
			</view>
		</u-popup>

		<login @loadpage="unloadpage" :show="enter" @closepage='closepage'></login>
	</view>
</template>

<script>
	import {
		index_list,
		store_default,
		order_empty,
		seat
	} from "@/api/comm.js"
	import config from '@/config.js'
	import {
		userInfo
	} from "@/api/public.js"
	export default {
		props: ['user'],
		data() {
			return {
				associationshow: false,
				Httpimg: config.HttpImg,
				enter: false,
				token: uni.getStorageSync('userinfo').token || null,
				percentage: 0,
				list: []
			};
		},
		computed: {
			public() {
				return uni.getStorageSync('public')
			}
		},
		mounted() {
			// this.getUser()
			this.index_listApi_2()
		},

		methods: {
			swp(url) {

				uni.reLaunch({
					url: this.list[url].url
				})
			},
			async index_listApi_2() {
				let data = await index_list({
					type: 3
				})
				this.list = []
				this.list.push(...data.data)
			},
			getjc_message() {
				let js = uni.getStorageSync('public').jc_message
				uni.requestSubscribeMessage({
					tmplIds: js,
					success: (res) => {
						console.log('res', res);
					},
					fail: (err) => {
						console.log('err', err);
					}

				})
			},

			async getUser() {
				let token = uni.getStorageSync('userinfo').token
				if (token) {
					let user = await userInfo()
					if (user.code == 1) {
						uni.setStorageSync('user', user.data)
						this.percentage = (this.user.score / this.user.upgrade) * 100
					}
				}

			},
			gopage(url, e) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				} else {
					// 清除来自用户端的本地缓存数据
					uni.removeStorageSync('addAdish')
					uni.removeStorageSync('option')
					uni.removeStorageSync('scene')
					uni.removeStorageSync('firstPay')
					uni.removeStorageSync('peopleCount')
					if (this.user.store_user == 1) {
						if (e == 1) {
							uni.reLaunch({
								url
							})
						} else {
							uni.navigateTo({
								url
							})
						}

					} else {
						uni.$showMsg('您还没有关联店铺')
					}
				}

			},
			contact(e) {},
			routergo(url) {
				let token = uni.getStorageSync('userinfo').token
				if (token) {
					uni.navigateTo({
						url: url
					})
				} else {
					this.enter = true
				}

			},
			closepage() {
				this.enter = false
			}
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #F7F7F7 !important;
	}

	.associationimg {
		// height: 500rpx;
		width: 600rpx;
		padding: 0rpx 30rpx 30rpx 30rpx;
		box-sizing: border-box;

		.associationimgtitle {
			width: 100%;
			height: 60rpx;
			font-size: 28rpx;
			font-weight: bold;
		}

		.associationimgtips {
			width: 100%;
			height: 30rpx;
			font-size: 24rpx;
			color: #999;

		}

		.associationimgimg {
			border-radius: 20rpx;
		}
	}

	.cardss {
		width: 690rpx;
		margin: auto;
		margin-top: 24rpx;
		background: #fff;
		border-radius: 20rpx;
		overflow: hidden;
	}

	.userdetailline {
		width: 100%;
		height: 2rpx;
		background: #F2F2F2;
		margin-top: 24rpx;
	}

	.pointsCoupon {
		padding: 0 10rpx;

		.pointsCouponlileft {
			border-right: 2rpx solid #F2F2F2;
		}

		.pointsCouponli {
			padding: 40rpx 18rpx;
			box-sizing: border-box;
			width: 50%;

		}

		.pointsCouponlil {


			.pointsCouponliimg {
				width: 48rpx;
				height: 48rpx;
				margin-right: 10rpx;
			}

			.pointsCouponlitt {
				font-size: 28rpx;
				color: #333333;
			}
		}

		.pointsCouponlinum {
			font-weight: bold;
			font-size: 34rpx;
			color: #333333;
		}
	}


	.headerplank {
		width: 100%;
		height: 176rpx;
	}

	.content {
		position: relative;

		.content_hadrimg {
			width: 100%;
			height: 503rpx;
			position: absolute;
			top: 0;
			z-index: -1;
			background: #DFF1FB;

		}

		.content_main {
			margin-top: 56rpx;


			.content_main_box {
				border-radius: 16rpx 16rpx 0 0;
				margin: 0 auto;
				width: 686rpx;
				// height: 352rpx;
				background: #FFFFFF;
				border-radius: 20rpx;

				.content_main_box_1 {
					margin: 0 auto;
					width: 100%;
					height: 100%;

					.content_main_box_1_left {
						position: relative;
						width: 100%;

						.content_main_box_1_left_img {
							width: 124rpx;
							height: 124rpx;
							border: 2rpx solid #FFFFFF;
							border-radius: 999%;
							overflow: hidden;
						}

						.content_main_box_1_left_avatar {
							position: absolute;
							margin-right: 20rpx;
							top: -60rpx;
							left: 20rpx;
							// background: #EC3F52;

							.content_main_box_1_left_avatar_img {
								width: 133rpx;
								height: 133rpx;
								// position: absolute;
								// top: 0;
								// left: 0;
								// right: 0;
								// bottom: 0;
							}



							.content_main_box_1_left_LV {
								position: absolute;
								bottom: -16rpx;
								left: 50%;
								transform: translateX(-50%);
								font-size: 22rpx;
								color: #05B6F6;
								background-color: #F1FBFF;
								padding: 6rpx 20rpx;
								box-sizing: border-box;
								border-radius: 30rpx;
							}
						}


					}

					.content_main_box_1_left_title {
						width: 402rpx;
						padding-top: 40rpx;
						box-sizing: border-box;

						.content_main_box_1_left_title_1 {
							.content_main_box_1_left_title_1_text {
								font-weight: bold;
								font-size: 40rpx;
								color: #333;
								max-width: 300rpx;
							}

							text:nth-child(2) {
								height: 32rpx;
								background: #F1FBFF;
								border-radius: 10rpx 10rpx 10rpx 0rpx;
								font-size: 24rpx;
								font-weight: 400;
								color: #333;
								padding: 5rpx 10rpx;
								margin-left: 10rpx;
							}
						}

						.content_main_box_1_left_title_1_btn {
							width: 88rpx;
							height: 41rpx;
							background: rgba(255, 255, 255, 0.35);
							border-radius: 424rpx 424rpx 424rpx 424rpx;
							font-size: 24rpx;
							color: #333;
							line-height: 41rpx;
							text-align: center;
							margin-left: 10rpx;
							margin-top: 6rpx;

						}

						.content_main_box_1_left_title_2sb {
							margin-top: 15rpx;
						}

						.content_main_box_1_left_title_2s {
							margin-top: 20rpx;
						}

						.content_main_box_1_left_title_22 {
							font-size: 24rpx;
							color: #777777;
						}

						.content_main_box_1_left_title_2 {
							font-size: 24rpx;
							color: #FFFFFF;
							padding: 5rpx 16rpx;
							box-sizing: border-box;
							background: #E40030;
							border-radius: 4rpx;
						}

						.content_main_box_1_left_title_3 {
							font-size: 22rpx;
							color: #FFFFFF;

							.content_main_box_1_left_title_3_line {
								width: 230rpx;
								height: 8rpx;
								padding-top: 12rpx;
								margin-right: 10rpx;
							}
						}
					}
				}

				.content_main_box_1_rights {
					padding: 0 20rpx;
					box-sizing: border-box;
				}

				.content_main_box_1_right_code {
					padding-top: 100rpx;
					box-sizing: border-box;
					width: 124rpx;
				}

				.content_main_box_1_right {
					width: 60rpx;
					height: 60rpx;
					margin: auto;
				}

				.content_main_box_1_right_codetext {
					font-size: 24rpx;
					color: #777777;
					text-align: center;
					margin-top: 10rpx;
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


		}

		.card-box {
			width: 690rpx;
			background: #FFFFFF;
			border-radius: 15rpx 15rpx 15rpx 15rpx;
			margin: auto;
			padding: 23rpx 33rpx;
			box-sizing: border-box;

			.card-box_title {
				font-size: 32rpx;
				color: #3D3D3D;
				margin-bottom: 23rpx;
				font-weight: 600;
			}

			.card-box_title_content {
				.card-box_title_content_item {

					.card-box_title_content_item_top {
						width: 100%;
						text-align: center;
						margin-bottom: 15rpx;
						display: flex;
						justify-content: center;

						.img_1 {
							width: 30rpx;
							height: 33rpx;
							margin-right: 10rpx;
						}

						.img_2 {
							width: 43rpx;
							height: 33rpx;
							margin-right: 10rpx;
						}

						.img_3 {
							width: 40rpx;
							height: 33rpx;
							margin-right: 10rpx;
						}


						.text_1 {
							font-size: 28rpx;
							color: #3D3D3D;
							font-weight: 600;
						}
					}

					.card-box_title_content_item_bom {
						text-align: center;

						span:nth-child(1) {
							font-size: 28rpx;
							color: #EC3F52;
							margin-right: 10rpx;
						}

						span:nth-child(2) {
							font-size: 24rpx;
							color: #999999;
						}
					}

				}

			}


		}


		.card {
			width: 690rpx;
			margin: 20rpx auto;
			padding: 24rpx 0;
			box-sizing: border-box;
			background: #FFFFFF;
			border-radius: 15rpx 15rpx 15rpx 15rpx;

			.card_title {
				font-size: 34rpx;
				color: #3D3D3D;
				font-weight: 600;
				padding: 0 33rpx;
				box-sizing: border-box;
			}

			.card_groud {

				.card_groud_item {
					width: 25%;
					height: 100rpx;
					text-align: center;
					margin-top: 43rpx;
					font-size: 24rpx;
					color: #333;

					image {
						width: 48rpx;
						height: 48rpx;
					}
				}
			}
		}

	}
</style>