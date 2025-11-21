<template>
	<!-- OrderInformation  -->
	<view>
		<view class="content">
			<view class="content_text">
				<view class="content_text_title">
					订单信息
				</view>
				<view class="content_text_time">
					<text>下单门店</text>
					<text>{{content_list.store}}</text>
				</view>
				<view class="content_text_time" v-if="content_list.order_type==3 && content_list.seat_title">
					<text>座号信息</text>
					<text>{{content_list.seat_title}}</text>
				</view>
				<view class="content_text_location">
					<text>下单时间</text>
					<text>{{content_list.createtime}}</text>
				</view>
				<template v-if="content_list.order_type==2">
					<view class="content_text_location">
						<text>打包费</text>
						<text>￥{{content_list.db_price}}</text>
					</view>
					<view class="content_text_location">
						<text>配送费</text>
						<text>￥{{content_list.ps_price}}</text>
					</view>
				</template>

				<view class="content_text_location">
					<text>订单号</text>
					<text><text style="color: #282828;">{{content_list.order_no}}</text><text
							@tap="setClipboardData(content_list.order_no)" style="color: #A2A2A2 ;">｜复制</text></text>
				</view>
				<view class="content_text_time">
					<text>订单状态</text>
					<!-- 自提 -->
					<text v-if="content_list.order_type==1&&content_list.status==1">待付款</text>
					<text v-if="content_list.order_type==1&&content_list.status==2">待自提</text>
					<text v-if="content_list.order_type==1&&content_list.status==3">已完成</text>
					<text v-if="content_list.order_type==1&&content_list.status==4">待退款</text>
					<text v-if="content_list.order_type==1&&content_list.status==5">已取消</text>
					<text v-if="content_list.order_type==1&&content_list.status==6">备餐</text>
					<text v-if="content_list.order_type==1&&content_list.status==7">待取单</text>
					<text v-if="content_list.order_type==1&&content_list.status==8">待取单</text>
					<text v-if="content_list.order_type==1&&content_list.status==9">已退款</text>
					<!-- 外卖 -->
					<text v-if="content_list.order_type==2&&content_list.status==1">待付款</text>
					<text v-if="content_list.order_type==2&&content_list.status==2">待收货</text>
					<text v-if="content_list.order_type==2&&content_list.status==3">已完成</text>
					<text v-if="content_list.order_type==2&&content_list.status==4">待退款</text>
					<text v-if="content_list.order_type==2&&content_list.status==5">已取消</text>
					<text v-if="content_list.order_type==2&&content_list.status==6">备餐</text>
					<text v-if="content_list.order_type==2&&content_list.status==7">待取货</text>
					<text v-if="content_list.order_type==2&&content_list.status==8">待取单</text>
					<text v-if="content_list.order_type==2&&content_list.status==9">已退款</text>
					<!-- 堂食 -->
					<text v-if="content_list.order_type==3&&content_list.status==1">待付款</text>
					<text v-if="content_list.order_type==3&&content_list.status==2">待收货</text>
					<text v-if="content_list.order_type==3&&content_list.status==3">已完成</text>
					<text v-if="content_list.order_type==3&&content_list.status==4">待退款</text>
					<text v-if="content_list.order_type==3&&content_list.status==5">已取消</text>
					<text v-if="content_list.order_type==3&&content_list.status==6">备餐</text>
					<text v-if="content_list.order_type==3&&content_list.status==7">待取货</text>
					<text v-if="content_list.order_type==3&&content_list.status==8">待取单</text>
					<text v-if="content_list.order_type==3&&content_list.status==9">已退款</text>

					<!-- 预定 -->
					<text v-if="content_list.order_type==4&&content_list.status==1">待付款</text>
					<text v-if="content_list.order_type==4&&content_list.status==10">预约中</text>
					<text v-if="content_list.order_type==4&&content_list.status==3">已完成</text>
					<text v-if="content_list.order_type==4&&content_list.status==4">待退款</text>
					<text v-if="content_list.order_type==4&&content_list.status==5">已取消</text>
				</view>
				<view class="content_text_location" v-if='content_list.code'>
					<text>取餐码</text>
					<text>{{content_list.code}}</text>
				</view>
				<view class="content_text_location" v-if='content_list.status !==1'>
					<text>支付方式</text>
					<text>{{content_list.pay_type ==2?'余额':content_list.pay_type ==1?"微信":'未支付'}}</text>
				</view>
				<view class="content_text_location">
					<text>备注信息</text>
					<text>{{content_list.remark||'无'}}</text>
				</view>
				<view class="record_pay" v-if="content_list.status==2 && content_list.order_type==2">
					<view @tap="take(content_list.order_no)">
						确认收货
					</view>
				</view>
			</view>
		</view>

		<height :hg="20"></height>
	</view>
</template>

<script>
	import {
		confirm
	} from "@/api/comm.js"
	export default {
		name: "OrderInformation",

		props: {
			content_list: {
				type: Object
			}
		},
		data() {
			return {

			};
		},
		methods: {
			async take(order_id) {
				let data = await confirm({
					order_id
				})
				if (data.code == 1) {
					this.$emit('update')
				} else {
					uni.showToast({
						title: data.msg,
						icon: 'none'
					})
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 0rpx auto;
		margin-top: 20rpx;

		.content_text {
			padding: 20rpx 40rpx;

			.content_text_title {
				border-bottom: 1rpx solid #F7F7F7;
				font-size: 32rpx;
				font-weight: 600;
				color: #333333;
				padding-bottom: 20rpx;
			}

			.content_text_time {
				display: flex;
				align-items: center;
				justify-content: space-between;
				margin: 30rpx 0;

				text:nth-child(1) {
					font-size: 24rpx;
					font-weight: 400;
					color: #9D9D9D;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #282828;
				}
			}

			.content_text_location {
				display: flex;
				justify-content: space-between;
				margin: 30rpx 0;

				text:nth-child(1) {
					font-size: 24rpx;
					font-weight: 400;
					color: #9D9D9D;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #282828;
				}
			}

			.record_pay {
				display: flex;
				flex-direction: row-reverse;
				padding-top: 20rpx;
				border-top: 1rpx dashed #EFEFEF;

				view {
					width: 155rpx;
					height: 55rpx;
					background: #E40030;
					border-radius: 8rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #fff;
					line-height: 55rpx;
					text-align: center;
					margin-right: 10rpx;
				}
			}
		}
	}
</style>