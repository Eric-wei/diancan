<template>
	<!--  配送信息 -->
	<view>
		<view class="content">
			<view class="content_text">
				<view class="content_text_title">
					配送信息
				</view>
				<view class="content_text_time">
					<text>预计送达</text>
					<text>{{content_list.subscribe_time}}</text>
				</view>
				<view class="content_text_location">
					<text>配送地址</text>
					<text>{{content_list.address}}</text>
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
		height: 269rpx;
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