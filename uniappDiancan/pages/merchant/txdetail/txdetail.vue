<template>
	<view>
		<view style="margin-top: 350rpx;" v-if="list.length<1">
			<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无记录'></u-empty>
		</view>

		<view class="" v-for="(item,i) in list" :key="i">
			<view class="title">
				{{item.date}}
			</view>
			<view class="card">
				<view class="card_item flexs" v-for="(item1,i1) in item.data" :key="i1">
					<view class="left">
						<view class="top" v-if="item1.type==1">银行卡提现</view>
						<view class="top" v-if="item1.type==2">微信提现</view>
						<view class="top" v-if="item1.type==3">支付宝提现</view>
						<view class="bom">提现：{{item1.createtime | Format}}</view>
					</view>
					<view class="right">
						<view class="top">
							{{item1.amount }}
						</view>
						<view class="bom" v-if="item1.status==0">
							未审核
						</view>
						<view class="bom" v-if="item1.status==1">
							待到账
						</view>
						<view class="bom" v-if="item1.status==2">
							审核拒绝
						</view>
						<view class="bom" v-if="item1.status==3">
							已到账
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		withdrawal_detail
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				list: [],
				page: 1
			}
		},
		onLoad() {
			this.getlist()
		},
		onReachBottom() {
			this.page++
			this.getlist()
		},
		methods: {
			async getlist() {
				let res = await withdrawal_detail({
					page: this.page
				})
				this.list = res.data
			}
		}
	}
</script>

<style lang="scss">
	.title {
		margin: 40rpx 30rpx;
		font-size: 30rpx;
		color: #2F3337;
	}

	.card {
		background-color: #fff;
		padding: 0 40rpx;
		box-sizing: border-box;

		.card_item {
			padding: 25rpx 0;
			box-sizing: border-box;
			border-bottom: 1rpx solid #EBEBEB;

			.left {

				.top {
					font-size: 26rpx;
					font-weight: 600;
					color: #333333;
					margin-bottom: 20rpx;
				}

				.bom {
					font-size: 24rpx;
					color: #777777;
				}
			}

			.right {
				.top {
					font-size: 38rpx;
					font-weight: 600;
					color: #333333;
					margin-bottom: 10rpx;
				}

				.bom {
					font-size: 24rpx;
					color: #777777;
					text-align: right;
				}
			}
		}
	}
</style>