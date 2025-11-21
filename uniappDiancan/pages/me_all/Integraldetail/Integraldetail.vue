<template>
	<view class="box" v-if='display'>
		<view class="box_content">
			<view class="hade">
				<view class="hade_left">
					<text>{{content_list.point}}</text>
					<text>当前积分</text>
				</view>
				<view class="hade_right" @tap="open">
					<view>
						<image src="../../../static/Project_drawing 37.png" mode=""></image>
					</view>
					<view v-if="time.length>0">
						{{time[0]}}---{{time[time.length - 1]}}
					</view>
					<view class="" v-else>
						时间
					</view>
				</view>
			</view>
			<view class="record">
				<view class="record_title">
					积分记录
				</view>

				<view style="margin-top: 250rpx;" v-if="content_list.rows.length<1">
					<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
						text='无积分记录'></u-empty>
				</view>

				<view class="record_content" v-for="(item,index) in content_list.rows" :key="item">
					<view class="record_content_left">
						<view>{{item.memo}}</view>
						<view>{{item.createtime}}</view>
					</view>
					<view class="record_content_right">
						<text v-if="item.type=='add'">{{item.score}}</text>
						<text v-if="item.type=='reduce'">-{{item.score}}</text>
					</view>
				</view>
			</view>
		</view>

		<uni-calendar ref="calendar" :range='true' :insert="false" @confirm="confirm" />
	</view>
</template>

<script>
	import {
		point_detailed
	} from "@/api/comm.js"
	import {
		toDate
	} from "@/utils/time.js"
	import {
		forEach
	} from "lodash";
	export default {
		data() {
			return {
				show: false,
				mode: 'range',
				time: [],
				content_list: {},
				min: new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000,
				display: false
			};
		},
		onLoad() {
			this.point_detailedApi()
		},
		methods: {
			open() {
				this.$refs.calendar.open();
			},
			confirm(e) {
				this.time = []
				e.range.data.forEach(item => {
					this.time.push(item)
				})
				this.point_detailedApi()
			},
			async point_detailedApi() {
				let data = await point_detailed({
					start_time: this.time[0] || '',
					end_time: this.time[this.time.length - 1] || ''
				})
				data.data.rows.forEach(res => {
					res.createtime = toDate(res.createtime * 1000, 1)
				})
				this.content_list = {}
				this.content_list = data.data
				this.display = true
			}
		}
	}
</script>

<style lang="scss">
	.box_content {
		width: 702rpx;
		margin: 0 auto;

		.hade {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 30rpx 0;

			.hade_left {
				text:nth-child(1) {
					font-size: 60rpx;
					font-weight: 600;
					color: #9B551A;
				}

				text:nth-child(2) {
					font-size: 26rpx;
					font-weight: 400;
					color: #9B551A;
				}
			}

			.hade_right {
				display: flex;
				align-items: center;

				view:nth-child(1) {
					width: 29rpx;
					height: 32rpx;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #000000;
				}
			}
		}

		.record {
			min-height: 900rpx;
			height: auto !important;
			height: 900rpx;
			background: #FFFFFF;
			padding: 30rpx 0;
			border-radius: 16rpx;

			.record_title {

				padding: 20rpx 30rpx;
				font-size: 32rpx;
				font-weight: 500;
				color: #000101;
				margin-bottom: 30rpx;

			}

			.record_content {
				width: 90%;
				margin: 0 auto;
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 20rpx 0;
				border-bottom: 1rpx solid #EBEBEB;
				margin-bottom: 30rpx;

				.record_content_left {
					view:nth-child(1) {
						font-size: 24rpx;
						font-weight: 400;
						color: #000101;
					}

					view:nth-child(2) {
						font-size: 20rpx;
						font-weight: 400;
						color: #9A9A9A;
						margin-top: 10rpx;
					}
				}

				.record_content_right {
					font-size: 32rpx;
					font-weight: 500;
					color: #313131;
				}
			}
		}
	}
</style>