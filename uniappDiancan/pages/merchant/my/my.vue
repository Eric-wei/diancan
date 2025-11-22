<template>
	<view class="my">
		<u-navbar title="个人中心" leftIconSize="0" :placeholder="true" :fixed="false" :autoBack="false">
		</u-navbar>
		<view class="backimg">

		</view>
		<view class="usercard flex jsb ac">
			<view class="flex ac">
				<image class="usercardAvatar" :src="user.image" mode="aspectFill"></image>
				<view class="usercarDetail">
					<view class="usercarDetailts flex ac">
						<view class="usercarDetailt">
							{{user.title||'请登录'}}
						</view>
						<view class="storebusiness flex ac">
							<view class="storeCircular" v-if="user.trade_switch==1"></view>
							<view class="storeCircularb" v-else></view>
							{{user.trade_switch==1?'营业中':'歇业中'}}
						</view>
					</view>
					<view class="storeintroduction flex  ac" v-if="user.user_type">
						<view class="storebusinessb flex ac">
							{{user.user_type==1?'店长':'店员'}}
						</view>
					</view>
				</view>

			</view>

		</view>
		<view class="income flex ac jsb">
			<view class="incomel incomeli">
				<view class="incomelinum">
					{{user.today.order_counts||0}}
				</view>
				<view class="incomelit">
					今日订单数(单)
				</view>
			</view>
			<view class="incomer incomeli">
				<view class="incomelinum">
					￥{{user.today.money_total||0}}
				</view>
				<view class="incomelit">
					今日销售额(元)
				</view>
			</view>
		</view>
		<view class="storewallet">
			<view class="storewallett">
				商家钱包
			</view>
			<view class="flex jsb ac mt24">
				<view class="storetotals flex ae" @click.stop="gopage('/pages/merchant/wallet/wallet')">
					<view class="wallettotal">
						{{user.balance||0}}
					</view>
					<view class="wallett">
						总金额（元）
					</view>
				</view>
				<view class="Withdrawal flex jc ac"
					@click.stop="gopage('/pages/merchant/cash/cash?balance='+user.remaining_amount)">
					提现
				</view>
			</view>

		</view>
		<view class="">
			<view class="order-wrapper" :class="userInfo.svip_open?'':'height'">
				<view class="order-hd flex">
					<view class="left">我的服务</view>
				</view>
				<view class="order-bd">
					<block v-for="(item,index) in orderMenu" :key="index">
						<view class="order-item" @click.stop="routerGo(index,item.url)">
							<view class="pic flex jc ac">
								<!-- <image class="iconfont" :src="item.img" mode="aspectFit" v-if="item.local"></image> -->
								<image class="iconfont" :src="Httpimg+item.img" mode="aspectFit"></image>
							</view>
							<view class="txt">{{item.title}}</view>
						</view>
					</block>
				</view>
			</view>
		</view>
		<view class="storewallet">
			<view class="storewallett">
				店铺开关
			</view>
			<view class="storeliSwitchplank"></view>
			<view class="storeliSwitch flex ac jsb">
				<view class="storeliSwitcht">
					店铺状态
				</view>
				<view class="storeliSwitchr  flex ac">
					<view class="storeliSwitchrt">
						{{user.trade_switch==1?'营业中':'歇业中'}}
					</view>
					<u-switch v-model="business" size="32" activeColor="#E40030" @change="change"></u-switch>
				</view>
			</view>
			<view class="storeliSwitch flex ac jsb">
				<view class="storeliSwitcht">
					自动接单
				</view>
				<view class="storeliSwitchr  flex ac">
					<view class="storeliSwitchrt">
						{{automatic?'自动接单':'手动接单'}}
					</view>
					<u-switch v-model="automatic" size="32" activeColor="#E40030" @change="changeautomatic"></u-switch>
				</view>
			</view>
		</view>


		<u-modal :show="popshow" :showCancelButton='true' @cancel='cancel' @confirm='confirm'>
			<view class="slot-content">
				营业时间已过，确定要修改时间吗？
			</view>
		</u-modal>



		<sjtabbar :myShow='true' :pagesPath='pagesPath'></sjtabbar>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		store_info,
		trade_switchs
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				automatic: false,
				Httpimg: config.HttpImg,
				popshow: false,
				business: false,
				pagesPath: '',
				user: uni.getStorageSync('shopuser'),
				orderMenu: [{
						img: 'myfunli1.png',
						title: '配送管理',
						url: '/pages/merchant/manageDrive/manageDrive'
					},
					{
						img: 'myfunli2.png',
						title: '店铺管理',
						url: '/pages/merchant/manageStore/manageStore'
					},
					// {
					// 	img: 'myfunli3.png',
					// 	title: '会员管理',
					// 	url: '/pagesB/myAddress/myAddress?go_back=true'
					// },
					{
						img: 'myfunli4.png',
						title: '商品管理',
						url: '/pages/merchant/manageProduct/manageProduct'
					},
					{
						img: 'myfunli5.png',
						title: '店铺认证',
						url: '/pages/merchant/storeAC/storeAC'
					},
					{
						img: 'myfunli6.png',
						title: '专属店铺码',
						url: '/pages/merchant/storeCode/storeCode'
					},
					{
						img: 'myfunli7.png',
						title: '点餐',
						url: '/pagesE/addCarNum/addCarNum',
						local: true
					},
					{
						img: 'myfunli8.png',
						title: '桌号',
						url: '/pagesE/addCarNum/addCarNum',
						local: true
					},
					{
						img: 'myfunli9.png',
						title: '规格模板',
						url: '/pages/merchant/AddMB/List',
						local: true
					},
				],
			}
		},
		onLoad() {},
		onShow() {
			this.getinfo()
		},
		methods: {
			routerGo(i, page) {
				if (i == 5) {
					uni.setStorageSync('option', 3)
					uni.setStorageSync('tableNumber', true)
					uni.setStorageSync('addAdish', false)
					uni.removeStorageSync('scene')
					uni.removeStorageSync('firstPay')
					uni.removeStorageSync('peopleCount')
					uni.navigateTo({
						url: '/pagesB/merchantOrder/merchantOrder?id=' + this.user.id
					})

				} else if (i == 6) {
					uni.navigateTo({
						url: 'pages/merchant/tableNumber/tableNumber?is_show=1'
					})

				} else {
					uni.navigateTo({
						url: page
					})
				}

			},
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
					this.business = false
				} else {
					this.popshow = false;
					this.change()
				}
			},
			async change() {
				let res = await trade_switchs({
					store_id: this.user.id,
					switch: this.business ? 1 : 0
				})
				if (res.code == 1) {
					this.getinfo()
				}
			},
			async changeautomatic() {
				let res = await trade_switchs({
					store_id: this.user.id,
					auto_order: this.automatic ? 1 : 0
				})
				if (res.code == 1) {
					this.getinfo()
				}
			},
			gopage(url) {
				uni.navigateTo({
					url
				})
			},
			async getinfo() {
				let res = await store_info()
				this.user = res.data
				this.business = this.user.trade_switch == 1 ? true : false;
				this.automatic = this.user.auto_order == 1 ? true : false;
				uni.setStorageSync('shopuser', this.user)
			},
			goidx() {
				uni.reLaunch({
					url: '/pages/index/index'
				})
			}

		}
	}
</script>

<style lang="scss">
	page {
		// min-height: 100vh;
		// padding: 10vh 26rpx 26rpx;
		// box-sizing: border-box;
		// background: #FAFAFA;
	}

	.mt24 {
		margin-top: 24rpx;
	}


	.storeliSwitchplank {
		width: 100%;
		height: 24rpx;
	}

	.storeliSwitch {
		padding: 24rpx 0;
		box-sizing: border-box;

		.storeliSwitcht {
			font-size: 28rpx;
			color: #000000;
		}

		.storeliSwitchr {}

		.storeliSwitchrt {
			font-size: 24rpx;
			color: #777777;
			margin-right: 14rpx;
		}
	}


	.order-wrapper {
		background: #fff;
		margin: 0 30rpx;
		border-radius: 20rpx;
		position: relative;
		margin-top: 26rpx;

		.order-hd {
			justify-content: space-between;
			padding: 24rpx 20rpx 24rpx 30rpx;
			margin-top: 25rpx;
			font-size: 32rpx;
			color: #282828;


			.left {
				font-weight: bold;
			}

			.right {
				display: flex;
				align-items: center;
				color: #666666;
				font-size: 26rpx;

				.icon-xiangyou {
					margin-left: 5rpx;
					font-size: 26rpx;
				}
			}
		}

		.order-bd {
			display: flex;
			flex-wrap: wrap;

			.order-item {
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
				width: 25%;
				height: 140rpx;
				margin-bottom: 24rpx;

				.pic {
					width: 88rpx;
					height: 88rpx;
					// background: #F9FAFB;
					position: relative;
					text-align: center;
					border-radius: 12rpx;

					.iconfont {
						width: 100%;
						height: 100%;
					}

				}

				.picb {
					width: 86rpx !important;
					height: 86rpx !important;
					background: #F9FAFB;
					position: relative;
					text-align: center;

					.iconfont {
						width: 100%;
						height: 100%;
					}

				}


				.txt {
					margin-top: 10rpx;
					font-size: 26rpx;
					color: #333;
					font-family: 'Roboto' !important;
				}
			}
		}
	}

	.storewallet {
		width: 686rpx;
		background: #FFFFFF;
		border-radius: 20rpx 20rpx 20rpx 20rpx;
		margin: auto;
		margin-top: 24rpx;
		padding: 40rpx 32rpx;
		box-sizing: border-box;

		.storewallett {
			font-weight: bold;
			font-size: 34rpx;
			color: #333333;
		}

		.storetotals {
			line-height: 1;
		}

		.wallettotal {
			font-weight: bold;
			font-size: 44rpx;
			color: #333333;
			margin-right: 10rpx;
		}

		.wallett {
			font-size: 28rpx;
			color: #777777;
		}

		.Withdrawal {
			width: 124rpx;
			height: 60rpx;
			background: rgba(250, 81, 81, 0.2);
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			font-size: 28rpx;
			color: #FA5151;
		}
	}


	.income {
		width: 686rpx;
		padding: 20rpx 74rpx;
		box-sizing: border-box;
		background: #fff;
		background: #FFFFFF;
		border-radius: 20rpx;
		position: relative;
		margin: auto;
		margin-top: 24rpx;

		.incomeli {}

		.incomelinum {
			font-weight: bold;
			font-size: 30rpx;
			color: #333333;
			text-align: center;
		}

		.incomelit {
			font-size: 24rpx;
			color: #777777;
			text-align: center;
			margin-top: 16rpx;
		}


	}


	::v-deep {
		.u-status-bar {
			background-color: transparent !important;
		}

		.u-navbar__content {
			background-color: transparent !important;
		}
	}



	.usercard {
		padding: 24rpx 32rpx;
		box-sizing: border-box;
		position: relative;

		.usercardAvatar {
			width: 104rpx;
			height: 104rpx;
			background: #DEDEDE;
			border-radius: 0rpx 0rpx 0rpx 0rpx;
			border: 2rpx solid #FFFFFF;
			border-radius: 999%;
			overflow: hidden;
		}

		.usercarDetail {
			margin-left: 20rpx;

			.usercarDetailts {}

			.usercarDetailt {
				font-weight: bold;
				font-size: 34rpx;
				color: #333333;
			}

			.storebusiness {
				padding: 4rpx 12rpx;
				box-sizing: border-box;
				font-size: 20rpx;
				color: #333333;
				background: #fff;
				border-radius: 20rpx;
				margin-left: 15rpx;

				.storeCircular {
					width: 12rpx;
					height: 12rpx;
					background: #07C160;
					border-radius: 999%;
					margin-right: 6rpx;
				}

				.storeCircularb {
					width: 12rpx;
					height: 12rpx;
					background: #999;
					border-radius: 999%;
					margin-right: 6rpx;
				}
			}

			.storebusinessb {
				padding: 4rpx 12rpx;
				box-sizing: border-box;
				font-size: 20rpx;
				color: #333333;
				background: #fff;
				border-radius: 4rpx;
				// margin-left: 15rpx;

				.storeCircular {
					width: 12rpx;
					height: 12rpx;
					background: #07C160;
					border-radius: 999%;
					margin-right: 6rpx;
				}

				.storeCircularb {
					width: 12rpx;
					height: 12rpx;
					background: #999;
					border-radius: 999%;
					margin-right: 6rpx;
				}
			}

			.storeintroduction {
				font-size: 28rpx;
				color: #777777;
				margin-top: 16rpx;
			}
		}



		.storefun {
			.storefunli {
				width: 48rpx;
				height: 48rpx;
			}
		}
	}



	.my {
		position: relative;

		.backimg {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 400rpx;
			background: #FFE8B5;
			border-radius: 0 0 60rpx 60rpx;
		}
	}

	.line {
		border-bottom: 1rpx solid #f3f3f3;
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

	.card {
		// position: relative;

		.bgc {
			width: 700rpx;
			height: 300rpx;
		}

		.headimg {
			width: 124rpx;
			height: 124rpx;
			border-radius: 50%;
			// position: absolute;
			// top: 38rpx;
			// left: 40rpx;
		}

		.left {
			// position: absolute;
			// top: 38rpx;
			// left: 194rpx;

			.one {
				font-size: 40rpx;
				font-weight: 500;
				color: #FFFFFF;
				line-height: 56rpx;

				image {
					width: 42rpx;
					height: 42rpx;
					margin: 4rpx 0 0 10rpx;
				}
			}

			.two {
				font-size: 24rpx;
				color: #FFFFFF;
				margin: 16rpx 0;

				image {
					width: 30rpx;
					height: 30rpx;
					margin-right: 6rpx;
				}
			}

			.three {
				margin: 20rpx 0;

				.three_item {
					width: 114rpx;
					height: 44rpx;
					background: #93DBFF;
					border-radius: 4rpx;
					font-size: 24rpx;
					color: #0075AF;
					text-align: center;
					line-height: 44rpx;
				}
			}

			.four {
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}
	}

	.card_one {
		border-radius: 14rpx;
		width: 700rpx;
		background-color: #fff;
		padding: 0rpx 40rpx;
		box-sizing: border-box;
		margin: 40rpx 0;

		.card_one_item {
			height: 110rpx;
			line-height: 110rpx;
			display: flex;
			align-items: center;

			image {
				width: 50rpx;
				height: 50rpx;
				margin-right: 20rpx;
			}

			.text {
				font-size: 28rpx;
				color: #333333;
				line-height: 50rpx;
				font-weight: 600;

			}
		}
	}
</style>