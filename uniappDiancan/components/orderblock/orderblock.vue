<template>
	<!-- 订单页的头部的卡片 -->
	<view>
		<view class="box" style="position: relative;">
			<view class="codebackimgs  flex jc" v-if="isSeat">
				<view class="codebackimgts">
					<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg"></image>
					<view class="w100h100 codebacktt flex jc ac">
						餐桌号
					</view>
				</view>
			</view>
			<view class="codebackimgs  flex jc" v-else>
				<view class="codebackimgts">
					<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg"></image>
					<view class="w100h100 codebacktt flex jc ac">
						取餐号
					</view>
				</view>
			</view>

			<view class="content">

				<view class="content_main" v-if="isSeat">
					<!-- 	<view class="content_main_1">
						桌号
					</view> -->
					<view class="content_main_4">
						<span style="padding:10rpx 14rpx;" v-for="(item,index) in  seat"
							:key="index">{{item.seat_title }}</span>

					</view>

				</view>
				<view class="content_main" v-else>
					<!-- 	<view class="content_main_1">
						取餐号
					</view> -->
					<view class="content_main_2">
						{{code}}
					</view>
					<view class="content_main_3">
						请向商家出示取餐号
					</view>
				</view>
				<u-line dashed></u-line>
				<view class="content_title">
					<text v-if="str==1">待付款</text>
					<text v-if="str==2">待自提</text>
					<text v-if="str==3">已完成</text>
					<text v-if="str==4">待退款</text>
					<text v-if="str==5">已取消</text>
					<text v-if="str==6">备餐</text>
					<text v-if="str==7">待取单</text>
					<text v-if="str==8">待取单</text>
					<text v-if="str==9">已退款</text>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	import config from '@/config.js'
	export default {
		name: "orderblock",
		props: ["code", "str", 'isSeat', 'seat'],
		data() {
			return {
				Httpimg: config.HttpImg,
			};
		}
	}
</script>

<style lang="scss" scoped>
	.box {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 20rpx auto;
		padding: 0 20rpx;
		// min-height: 280rpx;
		padding-top: 58rpx;
		box-sizing: border-box;

		.content {
			width: 605rpx;
			margin: 0 auto;

			.content_title {
				text-align: center;
				padding: 21rpx 0;
				// border-bottom: 1rpx solid #E3E3E3;
			}

			.content_main {
				text-align: center;
				margin-bottom: 24rpx;

				.content_main_1 {
					font-size: 24rpx;
					font-weight: 400;
					color: #333333;
					margin-top: 30rpx;
				}

				.content_main_2 {
					font-size: 48rpx;
					font-weight: bold;
					color: #333;
					padding: 10rpx 0;
				}

				.content_main_4 {
					font-size: 48rpx;
					font-weight: 400;
					color: #333;
					padding: 10rpx 0 0;
				}

				.content_main_3 {
					font-size: 24rpx;
					font-weight: 400;
					color: #5F5F5F;
				}
			}
		}
	}
</style>