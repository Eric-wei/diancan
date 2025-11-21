<template>
	<view class="wallet_box">
		
		<view class="Withdrawal">
			
			<view class="withdrawalTitle">
				账户余额(元)
			</view>
			<view class="canWithdrawal">
				￥{{user.remaining_amount||0}}
			</view>

			<view class="flexc flexs shouyi">
				<view class="flexc yield">
					<view class="one">
						今日收益
					</view>
					<view class="two">
						¥{{user.day_money}}
					</view>
				</view>
				<view class="flexc yield">
					<view class="one">
						昨日收益
					</view>
					<view class="two">
						¥{{user.y_day_money}}
					</view>
				</view>
			</view>
		</view>

		<view class="detail">
			<view class="title flexc flexs">
				<view class="">
					收支明细
				</view>
				<view class="btn" @click="show = true">
					{{timeText}}
				</view>
			</view>
			<scroll-view scroll-y="true" style="height: calc(100% - 146rpx);" @scrolltolower='scrolltolower'>
				<view class="content" v-if="list.length!==0">
					<view class="item flexc flexs" v-for="(item,i) in list" :key="i">
						<view class="item_left flexc">
							<view class="">
								<view class="top" v-if="item.type==1">收款成功</view>
								<view class="top" v-if="item.type==2">退款成功</view>
								<view class="bom">{{item.createtime | Format}} {{item.username}}</view>
							</view>
						</view>
						<view class="right" v-if="item.type==1"> +{{item.amount}}</view>
						<view class="colorred" v-if="item.type==2"> -{{item.amount}}</view>
					</view>
				</view>
				<view class="" v-else>
					<emptyPage :mag='mag'></emptyPage>
				</view>
			</scroll-view>

		</view>
		<u-datetime-picker closeOnClickOverlay='true' :show="show" @cancel='cancel' @confirm='confirm' v-model="value1"
			mode="year-month">
		</u-datetime-picker>
	</view>
</template>

<script>
	import {
		revenue
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				show: false,
				value1: Number(new Date()),
				user: uni.getStorageSync('shopuser'),
				list: [],
				date: '',
				mag: '100rpx 0rpx',
				timeText: '日期选择',
				page: 1,
				height: '100rpx'
			};
		},
		onLoad() {
			this.revenue()
			this.getHeg()
		},
		methods: {
			getHeg() {},
			async scrolltolower() {
				this.page++
				let res = await revenue({
					date: this.date,
					page: this.page
				})
				this.list = [...this.list, ...res.data.data]
			},
			cancel() {
				this.show = false
			},
			confirm(e) {
				this.show = false
				this.date = this.$way.timerFormat(e.value)
				this.timeText = this.Format(e.value)
				this.revenue()
			},
			Format(num) {
				//过滤器 用于格式化时间
				let date = new Date(num); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				let year = date.getFullYear();
				let month = ("0" + (date.getMonth() + 1)).slice(-2);
				let sdate = ("0" + date.getDate()).slice(-2);
				let hour = ("0" + date.getHours()).slice(-2);
				let minute = ("0" + date.getMinutes()).slice(-2);
				let second = ("0" + date.getSeconds()).slice(-2);
				// 拼接
				let result = year + "-" + month
				// 返回
				return result;
			},
			async revenue() {
				let res = await revenue({
					date: this.date,
					page: this.page
				})
				this.list = res.data.data
			},
			gopage() {
				uni.navigateTo({
					url: '/pages/merchant/cash/cash'
				})
			}
		}
	}
</script>

<style lang="scss">
	page {
		background: #FAFAFA;
	}

	.Withdrawal {
		width: 100%;
		padding: 24rpx 20rpx;
		box-sizing: border-box;
		background: #FFFFFF;
		border-radius: 20rpx 20rpx 20rpx 20rpx;
		margin: auto;
		margin-bottom: 24rpx;

		.withdrawalHistory {
			font-size: 24rpx;
			color: #AAAAAA;
		}

		.withdrawalTitle {
			font-weight: 400;
			font-size: 28rpx;
			text-align: center;
			margin-top: 40rpx;
		}

		.canWithdrawal {
			margin-top: 20rpx;
			font-weight: bold;
			font-size: 64rpx;
			color: #333333;
			text-align: center;
		}
	}

	.shouyi {
		padding: 20rpx 60rpx 0;
		box-sizing: border-box;
	}

	.yield {
		.one {
			font-size: 24rpx;
			color: #777777;
			margin-right: 20rpx;
		}

		.two {
			font-size: 24rpx;
			color: #E40030;
		}
	}

	.wallet_box {
		height: 100vh;
		display: flex;
		flex-direction: column;

		.card {
			background: #F1F2F2;
			padding: 24rpx 0;
			box-sizing: border-box;
			width: 700rpx;
			margin: 0 auto;
			border-radius: 0rpx 0rpx 30rpx 30rpx;
			height: 446rpx;

			.tix {
				width: 130rpx;
				height: 50rpx;
				border-radius: 20rpx 0 0 20rpx;
				position: absolute;
				top: 300rpx;
				right: 20rpx;
				background-color: #fff;
				font-size: 24rpx;
				color: #00ABFF;
				text-align: center;
				line-height: 50rpx;
			}

			.head {
				position: absolute;
				top: 60rpx;
				left: 80rpx;

				image {
					width: 60rpx;
					height: 60rpx;
					margin-right: 20rpx;
					border-radius: 50%;
				}

				color: #fff;
				font-size: 34rpx;
				line-height: 60rpx;

			}

			.text {
				font-size: 28rpx;
				color: #fff;
				position: absolute;
				top: 290rpx;
				left: 80rpx;
			}

			.money {
				font-size: 50rpx;
				color: #fff;
				position: absolute;
				top: 180rpx;
				left: 80rpx;
			}

			.wellet {
				width: 700rpx;
				height: 350rpx;
			}


		}

		.detail {
			background-color: #fff;
			border-radius: 40rpx 40rpx 0 0;
			flex-grow: 1;
			overflow: hidden;

			.title {
				font-size: 36rpx;
				color: #333333;
				font-weight: 600;
				padding: 40rpx;
				box-sizing: border-box;
			}

			.btn {
				width: 169rpx;
				background: #F1F2F2;
				border-radius: 34rpx;
				font-size: 24rpx;
				color: #777777;
				text-align: center;
				line-height: 67rpx;
			}

			.content {
				padding: 0 40rpx 40rpx;
				box-sizing: border-box;

				.item {
					padding: 24rpx 0;
					border-bottom: 1rpx solid #EBEBEB;

					.colorred {
						color: red;
					}

					.right {
						font-size: 30rpx;
						color: #E40030;
					}

					.item_left {

						image {
							width: 80rpx;
							height: 80rpx;
							border-radius: 50%;
							margin-right: 20rpx;
						}

						.top {
							font-size: 32rpx;
							color: #333333;
						}

						.bom {
							font-size: 24rpx;
							color: #777777;
						}


					}
				}
			}
		}


	}
</style>