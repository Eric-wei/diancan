<template>
	<!-- 订单页的头部的卡片 -->
	<view>
		<view class="box">
			<view class="content">
				<view class="content_title">
					<!-- 自提 -->
					<text v-if="str.order_type==1&&str.status==1">待付款</text>
					<text v-if="str.order_type==1&&str.status==2">待自提</text>
					<text v-if="str.order_type==1&&str.status==3">已完成</text>
					<text v-if="str.order_type==1&&str.status==4">待退款</text>
					<text v-if="str.order_type==1&&str.status==5">已取消</text>
					<text v-if="str.order_type==1&&str.status==6">备餐</text>
					<text v-if="str.order_type==1&&str.status==7">待取单</text>
					<text v-if="str.order_type==1&&str.status==8">待取单</text>
					<text v-if="str.order_type==1&&str.status==9">已退款</text>
					<!-- 外卖 -->
					<text v-if="str.order_type==2&&str.status==1">待付款</text>
					<text v-if="str.order_type==2&&str.status==2">待收货</text>
					<text v-if="str.order_type==2&&str.status==3">已完成</text>
					<text v-if="str.order_type==2&&str.status==4">待退款</text>
					<text v-if="str.order_type==2&&str.status==5">已取消</text>
					<text v-if="str.order_type==2&&str.status==6">备餐</text>
					<text v-if="str.order_type==2&&str.status==7">待取货</text>
					<text v-if="str.order_type==2&&str.status==8">待取单</text>
					<text v-if="str.order_type==2&&str.status==9">已退款</text>
				</view>
				<view class="content_main">
					<view class="content_main_1">
						<text>剩余支付时间</text>
						<u-count-down @finish='call(str.id)' :time="time*1000" format="HH:mm:ss"></u-count-down>
					</view>
					<view class="content_main_3">
						<text @tap='call(str.id)'>取消订单</text>
						<text @tap="pay(str.order_no)">立即支付</text>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		order_pay,
		order_cancel
	} from "@/api/comm.js"
	import wxApi from "@/utils/wxApi.js"
	export default {
		name: "orderblock",
		props: ["str"],
		data() {
			return {

			};
		},
		computed: {
			time() {
				let time = new Date(this.str.createtime).getTime() / 1000;
				time = time + 60 * 2
				let current = new Date().getTime() / 1000
				return time - current
			}
		},
		methods: {
			async pay(order_no) {
				let openid = uni.getStorageSync('openid') || ''
				let data = await order_pay({
					orderNo: order_no,
					openid
				})
				if (data.code == 1) {
					wxApi.wxparApi(data.data, 'pages/order_form/order_form', uni.getStorageSync('public')
						.message)
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			async call(id) {
				let that = this
				let data = await order_cancel({
					order_id: id
				})
				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
						icon: "success",
						success() {
							that.$emit('call')
						}
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
	.box {
		width: 660rpx;
		height: 300rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 20rpx auto;
		padding: 0 20rpx;

		.content {
			width: 605rpx;
			margin: 0 auto;

			.content_title {
				text-align: center;
				padding: 21rpx 0;
				border-bottom: 1rpx solid #E3E3E3;
			}

			.content_main {
				text-align: center;

				.content_main_1 {
					width: 278rpx;
					font-size: 26rpx;
					font-weight: 400;
					color: #333333;
					display: flex;
					align-items: center;
					margin: 40rpx auto;
				}

				.content_main_3 {
					width: 370rpx;
					margin: 0 auto;
					display: flex;
					align-items: center;
					justify-content: space-between;

					text:nth-child(1) {
						width: 150rpx;
						height: 53rpx;
						border-radius: 7rpx;
						border: 1rpx solid #D2D2D2;
						font-size: 24rpx;
						font-weight: 400;
						color: #313131;
						padding: 5rpx 10rpx;
						box-sizing: content-box;
						line-height: 53rpx;
						text-align: center;
					}

					text:nth-child(2) {
						width: 150rpx;
						height: 53rpx;
						background: #E40030;
						border-radius: 7rpx;
						font-size: 24rpx;
						font-weight: 400;
						color: #FFFFFF;
						padding: 5rpx 10rpx;
						line-height: 53rpx;
						text-align: center;
					}
				}
			}
		}
	}
</style>