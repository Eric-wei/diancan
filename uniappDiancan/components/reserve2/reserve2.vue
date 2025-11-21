<template>
	<!--  预留 -->
	<view>
		<view class="content">
			<view class="content_text">
				<view class="content_text_title">
					预约信息
				</view>
				<view class="content_text_time">
					<text>订单号</text>
					<text>{{content_list.order_no}}</text>
				</view>
				<view class="content_text_time">
					<text>预约人</text>
					<text>{{content_list.subscribe_name}}</text>
				</view>
				<view class="content_text_time">
					<text>预约时间</text>
					<text v-if="content_list.subscribe_time">{{content_list.subscribe_time | timerFormat}}</text>
					<text v-else>---</text>
				</view>

				<view class="content_text_time">
					<text>到店时间</text>
					<text>{{content_list.dd_time||'--'}}</text>
				</view>
				<view class="content_text_time">
					<text>预约桌号</text>
					<view class="flexc">
						<view class="content_text_right" v-for="(item,index) in content_list.seat_info" :key="index">
							{{item.seat_title}}
						</view>

					</view>

				</view>
				<view class="content_text_location">
					<text>预留电话</text>
					<text>{{content_list.subscribe_tel}}</text>
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		name: "distribution",
		props: {
			content_list: {
				type: Object
			}
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 20rpx auto;

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
					width: 140rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #9D9D9D;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #282828;
				}

				.content_text_right {
					font-size: 24rpx;
					font-weight: 400;
					color: #282828;
					margin-left: 10rpx;
				}
			}

			.content_text_location {
				display: flex;
				justify-content: space-between;

				text:nth-child(1) {
					width: 140rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #9D9D9D;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #282828;
					text-align: right;
					-webkit-line-clamp: 2; //设置几行
					display: -webkit-box; //设置为伸缩盒弹性盒子展示
					overflow: hidden; //超出隐藏
					text-overflow: ellipsis; //设置超出部分以省略号展示
					-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
				}
			}
		}
	}
</style>