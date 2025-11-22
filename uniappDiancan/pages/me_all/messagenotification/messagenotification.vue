<template>
	<view>
		<view style="margin-top: 350rpx;" v-if="content.length<1">
			<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无消息通知'></u-empty>
		</view>
		<view class="content" v-for="(item,index) in content" :key="item"
			@tap="routerTo('/pages/me_all/particulars/particulars?id='+item.id)">
			<view class="content_left">
				<view class="">
					{{item.title}}
				</view>
				<view class="">
					{{item.createtime}}
				</view>
			</view>
			<view class="content_right" v-html="item.description">
			</view>
		</view>
	</view>
</template>

<script>
	import {
		message
	} from "@/api/user.js"
	import {
		toDate
	} from "@/utils/time.js"
	import {
		forEach
	} from "lodash";
	export default {
		data() {
			return {
				content: [],
				page: 1
			};
		},
		onLoad() {
			this.messageApi()
		},
		methods: {
			async messageApi() {
				let data = await message({
					page: this.page,
					limit: 30
				})
				data.data.data.forEach(res => {
					res.createtime = toDate(res.createtime * 1000, 2)
				})
				this.content.push(...data.data.data)
			}
		},
		onReachBottom() {
			this.page++
			this.messageApi()
		}
	}
</script>

<style lang="scss">
	.content {
		margin: 20rpx auto;
		background: #FFFFFF;
		padding: 20rpx 30rpx;

		.content_left {
			display: flex;
			align-items: center;
			justify-content: space-between;

			view:nth-child(1) {
				font-size: 28rpx;
				font-weight: 400;
				color: #333333;
			}

			view:nth-child(2) {
				font-size: 24rpx;
				font-weight: 400;
				color: #737373;
			}
		}

		.content_right {
			margin-top: 20rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #737373;
		}
	}
</style>