<template>
	<view>

		<view class="hade">
			<u-navbar title="积分商城" :fixed="true" :placeholder="true" @rightClick="rightClick" :autoBack="true">
			</u-navbar>
			<image class="hade_img" :src="Httpimg+'pointsBack.png'" mode="" v-if="Httpimg"></image>
			<!-- <height :hg='System_height'></height> -->
			<!-- 	<view class="nav-tab">
				<view class="nav-tab_left" @tap="goback">
					<u-icon name="arrow-left" color="#FFFFFF" size="32"></u-icon>
				</view>
				<view class="nav-tab_content">
					积分商城
				</view>
				<view class="">
				</view>
			</view> -->
			<view class="integral_count flex ae">
				<image class="integral_countimg" src="/static/images/jibi.png" mode=""></image>
				<view>{{user.score}}</view>
				<image class="integral_countimg2" :src="Httpimg+'arrowright.png'" mode="" v-if="Httpimg"></image>
			</view>
			<view class="screen flex ac">
				<view class="screen_content flex ac" @tap="routerTo('/pages/me_all/Integraldetail/Integraldetail')">
					<image class="lcrice" :src="Httpimg+'problem.png'" mode="" v-if="Httpimg"></image>
					<text>积分明细</text>
					<image class="lcrice" :src="Httpimg+'arrowright.png'" mode="" v-if="Httpimg"></image>
					<!-- <text>|</text> -->
				</view>
				<view class="screen_content  flex ac"
					@tap="routerTo('/pages/order_all/login_protocol/login_protocol?name='+'积分协议'+'&state='+1)">
					<image class="lcrice" :src="Httpimg+'problem.png'" mode="" v-if="Httpimg"></image>
					<text>积分规则</text>
					<!-- <text>|</text> -->
					<image class="lcrice" :src="Httpimg+'arrowright.png'" mode="" v-if="Httpimg"></image>
				</view>
				<view class="screen_content  flex ac" @tap="routerTo('/pages/me_all/convert/convert')">
					<image class="lcrice" :src="Httpimg+'problem.png'" mode="" v-if="Httpimg"></image>
					<text>兑换记录</text>
					<image class="lcrice" :src="Httpimg+'arrowright.png'" mode="" v-if="Httpimg"></image>
				</view>
			</view>
		</view>
		<view class="pointsproducte">
			<!--  -->
			<view class="" style="background: #fff;">
				<u-tabs :scrollable="false" :list="list1" lineColor='#333' :lineWidth='70' :lineHeight="6" :activeStyle="{
			        color: '#333',
					fontWeight:'bold'
			    }" :inactiveStyle="{
			        color: '#AAAAAA',
			    }" @click="click"></u-tabs>
			</view>
			<view style="margin-top: 150rpx;" v-if="order_list.length<1">
				<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无商品'></u-empty>
			</view>
			<view class="content_order">
				<view class="content_order_box" v-for="(item,index) in order_list" :key="item"
					@tap="routerTo('/pages/me_all/integralcommodity/integralcommodity?id='+item.id)">
					<view class="content_order_box_img">
						<image :src="item.thumbnail" mode="aspectFill"></image>
					</view>
					<view class="content_order_box_bottom">
						<text class="content_order_box_bottom_1">{{item.title}}</text>
						<view class="content_order_box_bottom_2">
							<text>{{item.price}}积分</text>
							<text>剩余{{item.stock}}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		point_type,
		point_list,
	} from "@/api/comm.js"
	import {
		cellphone
	} from "@/utils/type_height.js"
	export default {
		data() {
			return {
				list1: [{
					name: '全部',
				}, {
					name: '优惠券',
				}, {
					name: '精美周边'
				}],
				user: uni.getStorageSync('user'),
				order_list: [],
				page: 1,
				System_height: cellphone(), //系统高度

			};
		},
		onLoad() {
			this.point_typeApi()
		},
		computed: {
			Httpimg() {
				return config.HttpImg;
			}
		},
		methods: {
			click(item) {
				this.point_listApi(item.id)
			},
			// 返回上一页
			goback() {
				uni.navigateBack({
					delta: 1
				})
			},
			async point_typeApi() {
				let data = await point_type()
				this.list1 = []
				this.list1.push(...data.data)
				this.point_listApi()
			},
			async point_listApi(type) {
				let data = await point_list({
					type: type || this.list1[0]?.id,
					page: 1,
					limit: 10
				})
				this.order_list = []
				this.order_list.push(...data.data.rows)
			},
			//触底加载
			async point_listApi_down(type) {
				let data = await point_list({
					type: type || this.list1[0].id,
					page: this.page,
					limit: 10
				})
				this.order_list.push(...data.data.rows)
			}
		},
		onReachBottom() {
			this.page++
			this.point_listApi_down()
		}
	}
</script>

<style lang="scss">
	::v-deep .u-tabs__wrapper__nav__line {
		margin-left: 30rpx;
	}

	::v-deep .u-status-bar {
		background: transparent !important;
	}

	::v-deep .u-navbar__content {
		background: transparent !important;
	}

	.lcrice {
		width: 24rpx;
		height: 24rpx;
		margin-right: 6rpx;
	}


	.pointsproducte {
		border-radius: 20rpx 20rpx 0 0;
		overflow: hidden;
		position: relative;
	}


	.hade {
		width: 100%;
		height: 419rpx;
		position: relative;

		.hade_img {
			position: absolute;
			top: 0;
			width: 100%;
			height: 440rpx;
			z-index: 0;

		}

		.nav-tab {
			display: flex;
			align-items: center;
			justify-content: space-between;

			.nav-tab_left {
				padding: 40rpx;
			}

			.nav-tab_content {
				font-size: 34rpx;
				font-weight: 500;
				color: #FFFFFF;
				z-index: 2;
				padding-right: 100rpx;
			}
		}

		.integral_count {
			font-weight: bold;
			font-size: 64rpx;
			color: #9B551A;
			margin-top: 50rpx;
			position: relative;
			padding: 0 32rpx;
			box-sizing: border-box;
			line-height: 1;

			.integral_countimg {
				width: 64rpx;
				height: 64rpx;
				margin-right: 24rpx;
			}

			.integral_countimg2 {
				width: 32rpx;
				height: 32rpx;
				margin-left: 10rpx;
			}

			text {
				width: 100%;
				text-align: center;
				z-index: 2;
			}
		}

		.screen {
			position: relative;
			padding: 0 32rpx;
			box-sizing: border-box;
			margin-top: 50rpx;

			.screen_content {
				margin-right: 22rpx;

				text {
					font-size: 24rpx;
					color: rgba(155, 85, 26, 0.6);
				}

				// text:nth-child(2) {
				// 	font-size: 15rpx;
				// 	color: #FFFFFF;
				// 	padding: 0 20rpx;
				// }
			}
		}

	}

	.content_order {
		width: 95%;
		margin: 0 auto;
		margin-top: 40rpx;
		display: flex;
		align-content: center;
		justify-content: space-between;
		flex-wrap: wrap;

		.content_order_box {
			width: 340rpx;
			border-radius: 14rpx;
			overflow: hidden;
			margin-bottom: 20rpx;

			.content_order_box_img {
				width: 340rpx;
				height: 250rpx;
			}

			.content_order_box_bottom {
				height: 140rpx;
				background: #FFFFFF;
				padding: 0 20rpx;

				.content_order_box_bottom_1 {
					font-size: 26rpx;
					font-family: PingFangSC-Medium, PingFang SC;
					font-weight: 500;
					color: #383838;
					display: inline-block;
					margin: 20rpx 0;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}

				.content_order_box_bottom_2 {
					display: flex;
					align-items: center;
					justify-content: space-between;

					text:nth-child(1) {
						font-size: 32rpx;
						font-family: PingFangSC-Medium, PingFang SC;
						font-weight: 500;
						color: #FA5151;
					}

					text:nth-child(2) {
						font-size: 24rpx;
						font-family: PingFangSC-Regular, PingFang SC;
						font-weight: 400;
						color: #9F9F9F;
					}
				}
			}

		}
	}
</style>