<template>
	<view>
		<!-- 店家位置信息 -->
		<view class="content" :class="addShow?'line':'pad1'">
			<view class="content_left">
				<view class="content_left_1 flex ac">
					<text class="ordertypetag" v-if="content_list.order_type==1">自提</text>
					<text class="ordertypetag" v-if="content_list.order_type==2">外卖</text>
					<text class="ordertypetag" v-if="content_list.order_type==3">堂食</text>
					<text class="ordertypetag" v-if="content_list.order_type==4">预约</text>
					{{content_list.store || ''}}
				</view>
				<view class="content_left_2 me-text-beyond-multi1">
					{{content_list.address || ''}}
				</view>
			</view>
			<view class="content_right">
				<view class="content_right_1" @tap="openTel(content_list.tel)">
					<image src="../../static/Project_drawing 33.png" mode=""></image>
				</view>
				<view class="content_right_2" @tap="getopenLocation(content_list.lat,content_list.lon)">
					<image src="../../static/Project_drawing 2.png" mode=""></image>
				</view>
			</view>
		</view>
		<!-- <view class="label" :class="addShow?'':'magleft'">
			<text v-if="content_list.order_type==1">自提</text>
			<text v-if="content_list.order_type==2">外卖</text>
			<text v-if="content_list.order_type==3">堂食</text>

		</view> -->
	</view>
</template>

<script>
	export default {
		name: "storelocation",
		props: {
			content_list: {
				type: Object
			},
			addShow: {
				default: false
			}
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss" scoped>
	.pad1 {
		padding: 20rpx 20rpx;
	}

	.content {
		display: flex;
		align-items: center;
		justify-content: space-between;

		.content_left {
			max-width: 250px;
			.content_left_1 {
				font-size: 32rpx;
				font-weight: 400;
				color: #393939;
			}

			.content_left_2 {
				font-size: 24rpx;
				font-weight: 400;
				color: #979797;
				margin-top: 14rpx;
			}
		}

		.content_right {
			width: 150rpx;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.content_right_1 {
				width: 66rpx;
				height: 66rpx;
			}

			.content_right_2 {
				width: 66rpx;
				height: 66rpx;
			}
		}

	}

	.line {
		padding: 0rpx 0 8rpx;
		box-sizing: border-box;
		margin-bottom: 12rpx;
		border-bottom: 1rpx solid #eee;
	}

	.label {
		width: 66rpx;
		height: 38rpx;
		border-radius: 2rpx;
		border: 1rpx solid #00C8FF;
		font-size: 24rpx;
		font-weight: 400;
		color: #00C8FF;
		line-height: 38rpx;
		text-align: center;
	}

	.magleft {
		margin-left: 20rpx;
	}
</style>