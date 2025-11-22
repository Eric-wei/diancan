<template>
	<view>
		<view class="fixed">
			商家中心
		</view>
		<view class="flexc">
			<image class="headimg" :src="user.image" mode="aspectFill"></image>
			<view class="title" style="line-height: 62rpx;">
				{{user.title || ''}}
			</view>
		</view>
		<view class="yuer">
			<image class="bgcimg" src="../static/icon1.svg" mode=""></image>
			<view class="one">
				我的余额(元)
			</view>
			<view class="two">
				{{user.balance || ''}}
			</view>
			<view class="three" @click="gopage('/pages/merchant/wallet/wallet')">
				余额明细
			</view>
			<view class="four" v-if="user.user_type==1"
				@click="gopage('/pages/merchant/cash/cash?balance='+user.remaining_amount)">
				立即提现
			</view>
			<view class="yuer_bom flexs ">
				<view class="flexc" v-if="value3">
					<image src="../static/icon2.svg" mode=""></image>
					<text>营业中</text>
				</view>
				<view class="flexc" v-else>
					<image src="../static/icon17.svg" mode=""></image>
					<text>停业中</text>
				</view>
				<u-switch v-model="value3" size="26" @change="gettime"></u-switch>
			</view>
		</view>
		<!-- 分类 -->
		<view class="card flexw flexs">
			<view class="card_item" v-for='(item,i) in List' :key="i" @click="goorder(i)">
				<view class="type" v-if="item.type">
					{{item.type | nine}}
				</view>

				<image :src="item.url" mode=""></image>
				<view class="">
					{{item.name}}
				</view>
			</view>
		</view>
		<!-- 今日数据 -->
		<view class="day_card">
			<view class="title flexc flexs">
				<view class="flexc">
					<view class="one">
						今日数据
					</view>
				</view>
				<view class="flexc">
					<view class="two" style="line-height: 50rpx;margin-left: 20rpx;width: 370rpx;">
						{{timer | secFormat}} 实时更新
					</view>
					<view class="dayimg">
						<image style="margin-top: 10rpx;" src="../static/icon9.svg" mode="" @click="getloding"></image>
					</view>
				</view>
			</view>
			<!-- 数据 -->
			<view class="shuju">
				<u-grid :border="true" col="2">
					<u-grid-item v-for="(item,Index) in gridlist" :key="Index">
						<view class="grid">
							<view class="grid_one">
								{{item.title}}
							</view>
							<view class="grid_two">
								{{item.type}}
							</view>
						</view>

					</u-grid-item>
				</u-grid>
			</view>
		</view>
		<!-- 今日数据 -->
		<view class="day_card">
			<view class="title ">
				<view class="one">
					店铺数据
				</view>
			</view>
			<!-- 数据 -->
			<view class="shuju">
				<u-grid :border="true" col="2">
					<u-grid-item v-for="(item,Index) in gridlist2" :key="Index">
						<view class="grid">
							<view class="grid_one">
								{{item.title}}
							</view>
							<view class="grid_two">
								{{item.type}}
							</view>
						</view>

					</u-grid-item>
				</u-grid>
			</view>
		</view>
		<sjtabbar :indexShow='true' :pagesPath='pagesPath'></sjtabbar>
		<u-modal :show="popshow" :showCancelButton='true' @cancel='cancel' @confirm='confirm'>
			<view class="slot-content">
				营业时间已过，确定要修改时间吗？
			</view>
		</u-modal>
	</view>
</template>

<script>
	import {
		store_info,
		trade_switchs
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				value3: true,
				pagesPath: '/pages/merchant/index/index',
				gridlist: [],
				gridlist2: [],
				List: [],
				user: {},
				navbarHeight: '',
				inter: null,
				popshow: false,
				timer: new Date()
			};
		},
		onUnload() {
			clearInterval(this.inter)
		},
		onLoad() {
			this.getstore_info()
			this.inter = setInterval(() => {
				this.getstore_info()
			}, 30000)
		},
		methods: {
			cancel() {
				this.popshow = false
			},
			confirm() {
				this.popshow = false
				uni.navigateTo({
					url: '/pages/merchant/shopInfo/shopInfo'
				})
			},
			// 判读是否过了营业时间
			gettime() {
				//过滤器 用于格式化时间
				let user = uni.getStorageSync('shopuser')
				let date = new Date(); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				let hour = ("0" + date.getHours()).slice(-2);
				let end = user.end_times.substring(0, 2)
				let start = user.start_times.substring(0, 2)
				if (hour < start || hour > end) {
					this.popshow = true;
					this.value3 = false
				} else {
					this.popshow = false;
					this.change()
				}
			},
			goorder(i) {
				if (i == 6) {
					uni.setStorageSync('option', 3)
					uni.setStorageSync('tableNumber', true)
					uni.setStorageSync('addAdish', false)
					uni.navigateTo({
						url: '/pagesB/merchantOrder/merchantOrder?id=' + this.user.id
					})

				} else if (i == 7) {
					uni.navigateTo({
						url: 'pages/merchant/tableNumber/tableNumber?is_show=1'
					})

				} else {
					uni.navigateTo({
						url: '/pages/merchant/order/order?idx=' + (i + 1)
					})
				}

			},
			async change() {
				let res = await trade_switchs({
					switch: this.value3
				})
			},
			gopage(url) {
				uni.navigateTo({
					url
				})
			},
			async getloding() {
				uni.showLoading({
					title: '数据加载中...'
				})
				this.getstore_info()
				uni.hideLoading()
			},
			async getstore_info() {
				let res = await store_info()
				if (res.code !== 1) return
				this.user = res.data
				uni.setStorageSync('shopuser', res.data)
				this.timer = new Date()
				if (this.user.trade_switch == 1) {
					this.value3 = true
				} else {
					this.value3 = false
				}
				this.List = [{
					url: '../static/icon4.svg',
					name: '待接单',
					type: this.user.to_pending
				}, {
					url: '../static/icon5.svg',
					name: '已接单',
					type: this.user.already_pending
				}, {
					url: '../static/icon6.svg',
					name: '已完成',
					type: this.user.already_over
				}, {
					url: '../static/icon7.svg',
					name: '待退款',
					type: this.user.to_refund
				}, {
					url: '../static/icon3.svg',
					name: '待支付',
					type: this.user.to_paid
				}, {
					url: '../static/icon8.svg',
					name: '已退款',
					type: this.user.already_refund
				}, {
					url: '../static/icon20.svg',
					name: '点餐',
					type: null
				}, {
					url: '../static/icon22.png',
					name: '桌号',
					type: null
				}]
				this.gridlist = [{
					title: '今日营业额(元)',
					type: this.user.today.money_total
				}, {
					title: '今日订单(单)',
					type: this.user.today.order_counts
				}, {
					title: '今日访客(人)',
					type: this.user.today.visitor_counts
				}, {
					title: '今日退单(单)',
					type: this.user.today.refund_counts
				}]
				this.gridlist2 = [{
					title: '店铺收藏数',
					type: this.user.store.collect_counts
				}, {
					title: '商品点击数',
					type: this.user.store.cleck_goods_counts
				}, {
					title: '成交人数',
					type: this.user.store.people_number
				}, {
					title: '退款金额',
					type: this.user.store.refund_money
				}]
			}
		}
	}
</script>

<style lang="scss">
	page {
		min-height: 100vh;
		padding: 10vh 26rpx 26rpx;
		box-sizing: border-box;
		background: #FAFAFA;
	}

	.fixed {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		height: 10vh;
		z-index: 999;
		text-align: center;
		line-height: 13vh;
		font-size: 26rpx;
	}

	.headimg {
		width: 62rpx;
		height: 62rpx;
		margin-right: 20rpx;
		border-radius: 50%;
	}

	.title {
		font-size: 34rpx;
		font-weight: 600;
		color: #333333;
	}

	.yuer {
		position: relative;
		width: 700rpx;
		height: 340rpx;
		background-color: #fff;
		margin: 28rpx 0 0rpx;

		.bgcimg {
			width: 700rpx;
			height: 250rpx;
			border-radius: 10rpx;

		}

		.yuer_bom {
			height: 100rpx;
			padding: 20rpx 26rpx 0;
			box-sizing: border-box;

			image {
				width: 36rpx;
				height: 36rpx;
				margin-right: 16rpx;
			}

			font-size: 24rpx;
			color: #000000;
		}

		.one {
			font-size: 30rpx;
			color: #FFFFFF;
			position: absolute;
			top: 56rpx;
			left: 50rpx;
		}

		.two {
			font-size: 56rpx;
			font-weight: 600;
			color: #FFFFFF;
			position: absolute;
			top: 120rpx;
			left: 50rpx;
		}

		.three {
			font-size: 22rpx;
			color: #FFFFFF;
			position: absolute;
			top: 68rpx;
			right: 88rpx;
		}

		.four {
			width: 188rpx;
			height: 52rpx;
			background: #FFFFFF;
			border-radius: 26rpx;
			font-size: 22rpx;
			color: #00ABFF;
			line-height: 52rpx;
			text-align: center;
			position: absolute;
			top: 132rpx;
			right: 64rpx;
		}
	}

	.card {
		width: 700rpx;
		background: #FFFFFF;
		border-radius: 10rpx;
		padding: 10rpx 0rpx;
		box-sizing: border-box;
		margin: 40rpx 0;

		.card_item {
			position: relative;
			width: 150rpx;
			text-align: center;
			font-size: 24rpx;
			color: #777777;
			margin: 16rpx 0;

			image {
				width: 74rpx;
				height: 74rpx;
			}

			.type {
				font-size: 14rpx;
				color: #00ABFF;
				line-height: 26rpx;
				text-align: center;
				width: 26rpx;
				height: 26rpx;
				background: #FFFFFF;
				border: 1rpx solid #00ABFF;
				border-radius: 50%;
				position: absolute;
				right: 36rpx;
				top: 4rpx;
				z-index: 99;
			}
		}
	}

	.day_card {
		background-color: #fff;
		margin-bottom: 40rpx;

		.dayimg {
			text-align: right;
			width: 40rpx;
		}

		.title {
			padding: 18rpx 30rpx;
			box-sizing: border-box;
			border-bottom: 1rpx solid #EBEBEB;

			.one {
				font-size: 34rpx;
				color: #000000;
			}

			.two {
				font-size: 24rpx;
				color: #777777;
				width: 350rpx;
				height: 34rpx;
			}

			image {
				width: 27rpx;
				height: 34rpx;
			}
		}
	}

	.grid {
		padding: 20rpx 80rpx;
		box-sizing: border-box;
		text-align: center;

		.grid_one {
			font-size: 26rpx;
			color: #777777;
			margin-bottom: 10rpx;
		}

		.grid_two {
			font-size: 34rpx;
			color: #000000;
		}
	}
</style>