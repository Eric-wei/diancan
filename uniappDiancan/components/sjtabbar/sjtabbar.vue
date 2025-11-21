<template>
	<!-- tabbar -->
	<view>
		<view class="" style="height: 128rpx;"> </view>
		<view class="tabbar">
			<!-- 首页 -->
			<!-- <view class="" @click="goIndex('/pages/merchant/index/index')">
				<image v-if="indexShow==true" class="icon" src="" mode=""></image>
				<image v-else class="icon" src="" mode=""></image>
				<view class="" :class="{'active':indexShow==true}">
					首页
				</view>
			</view> -->
			<view class="" @click="goIndex('/pages/merchant/takeout/order')">
				<view class="flex jc">
					<image v-if="indexShow==true" class="icon" src="/static/tab1/wmc.png" mode=""></image>
					<image v-else class="icon" src="/static/tab1/wm.png" mode=""></image>
				</view>
				<view class="icont" :class="{'active':indexShow==true}">
					外卖订单
				</view>
			</view>
			<!-- 分类 -->
			<view class="" @click="goIndex('/pages/merchant/order/order')">
				<view class="flex jc">
					<image v-if="ddShow==true" class="icon" src="/static/tab1/orderc.png" mode=""></image>
					<image v-else class="icon" src="/static/tab1/order.png" mode=""></image>
				</view>
				<view class="icont" :class="{'active':ddShow==true}">
					门店订单
				</view>
			</view>
			<view class="" @click="goIndex('/pages/merchant/sjreserved/sjreserved')" v-if="common.isyy==1">
				<view class="flex jc">

					<image v-if="yyShow==true" class="icon" src="/static/tab/tab5c.png" mode=""></image>
					<image v-else class="icon" src="/static/tab/tab5.png" mode=""></image>
				</view>
				<view class="icont" :class="{'active':yyShow==true}">
					预约订单
				</view>
			</view>
			<!-- 我的 -->
			<view class="" @click="goIndex('/pages/merchant/my/my')">
				<view class="flex jc">
					<image v-if="myShow==true" class="icon" src="/static/tab1/myc.png" mode=""></image>
					<image v-else class="icon" src="/static/tab1/my.png" mode=""></image>
				</view>
				<view class="icont" :class="{'active':myShow==true}">
					我的
				</view>
			</view>
			<!--返回 -->
			<view class="" @click="goIdx()">
				<view class="flex jc">

					<image v-if="fhShow==true" class="icon" src="../../static/fh1.svg" mode=""></image>
					<image v-else class="icon" src="../../static/fh.svg" mode=""></image>
				</view>
				<view class="icont" :class="{'active':fhShow==true}">
					返回
				</view>
			</view>
		</view>
	</view>
	</view>
</template>

<script>
	export default {
		name: "sjtabbar",
		props: {
			// 首页
			indexShow: {
				type: Boolean,
				default: false
			},
			// 课程
			ddShow: {
				type: Boolean,
				default: false
			},
			// 我都、的
			myShow: {
				type: Boolean,
				default: false
			},

			// 我都、的
			fhShow: {
				type: Boolean,
				default: false
			},
			// 预约
			yyShow: {
				type: Boolean,
				default: false
			},
			pagesPath: {
				type: String,
			}
		},
		data() {
			return {
				common: uni.getStorageSync('public')
			};
		},

		methods: {
			// 首页
			goIndex(url) {
				if (this.pagesPath !== url) {
					uni.redirectTo({
						url
					})
				}
			},
			goIdx() {
				uni.setStorageSync('option', 1)
				uni.setStorageSync('tableNumber', false)
				uni.setStorageSync('addAdish', false)
				uni.reLaunch({
					url: '/pages/index/index'
				})
			}

		}
	}
</script>

<style lang="scss" scoped>
	.icont {
		text-align: center;
		color: #AAAAAA;
	}

	.tabbar {
		width: 100%;
		height: calc(100rpx + env(safe-area-inset-bottom));
		background: #FFFFFF;
		box-shadow: 0px -2rpx 12rpx 0px rgba(0, 0, 0, 0.12);
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
		color: #2C2C36;
		font-size: 20rpx;
		padding-top: 10rpx;
		box-sizing: border-box;
		display: flex;
		justify-content: space-around;
		z-index: 99;
	}

	.icon {
		width: 48rpx;
		height: 48rpx;
		margin: auto;
		margin-bottom: 4rpx;

	}

	.active {
		color: #333;
	}

	.tabcenter {
		width: 200rpx;
		height: 120rpx;
		margin-bottom: 50rpx;
		position: absolute;
		top: -38rpx;
		left: 280rpx;
	}
</style>