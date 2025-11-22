<template>
	<view>
		<view class="" v-if='list.length!==0'>
			<view class="card" :style="{'backgroundColor':item.rgb}" v-for="item in list" :key='item.id'>
				<view class=" flexs">
					<view class="left">
						<view class="top" v-if="item.bank_name">
							{{item.bank_name}}
						</view>
						<view class="top" v-if="item.wx_code">
							微信
						</view>
						<view class="top" v-if="item.zfb_code">
							支付宝
						</view>
						<view class="bom" v-if='item.bank_code&&item.bank_type'>
							{{item.bank_type}}
						</view>
					</view>
					<view class="flex " style="z-index: 9;">
						<view class="right" @click="del(item.id)">
							解绑
						</view>
						<view class="right ml20" @click="edit(item.id)">
							编辑
						</view>
					</view>

				</view>
				<view class="num" v-if='item.bank_code'>
					{{item.bank_code| maskBankCardNumber}}
				</view>
				<view class="num" v-if='item.wx_code'>
					{{item.wx_code| maskBankCardNumber}}
				</view>
				<view class="num" v-if='item.zfb_code'>
					{{item.zfb_code| maskBankCardNumber}}
				</view>
				<image class="img" v-if="item.bank_icon" :src="item.bank_icon" mode="aspectFill"></image>
				<image class="img" v-if="item.type==2" src="../../merchant/static/wx.svg" mode="aspectFill">
				</image>
				<image class="img" v-if="item.type==3" src="../../merchant/static/zfb.svg" mode="aspectFill">
				</image>
			</view>
		</view>
		<view class="" v-else>
			<emptyPage></emptyPage>
		</view>


		<view class="addcard flexc" @click="routerTo('/pages/merchant/addCard/addCard')">
			<view class="">
				<u-icon name="plus" size="40"></u-icon>
			</view>
			<view class="text">
				添加账号
			</view>
		</view>
	</view>


</template>

<script>
	import {
		withdrawal_account_list,
		withdrawal,
		del_withdrawal_account
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				list: [],
				aa: '1234567891124567',
				bgc: "#6C80FF",
				user: uni.getStorageSync('shopuser')
			};

		},
		onShow() {
			this.getList()
		},
		methods: {
			edit(id) {
				uni.navigateTo({
					url: '/pages/merchant/addCard/addCard?id=' + id
				})
			},
			async del(id) {
				let that = this;
				uni.showModal({
					title: '解绑',
					content: '解绑后将不可提现到此账户，确认解绑？',
					async success(ress) {
						if (ress.confirm) {
							if (that.user.user_type == 1) {
								let res = await del_withdrawal_account({
									id
								})
								if (res.code == 1) {
									uni.$showMsg(res.msg)
									that.getList()
								} else {
									uni.$showMsg(res.msg)
								}
							} else {
								uni.$showMsg('您没有权限')
							}
						}

					}
				})

			},
			async getList() {
				let res = await withdrawal_account_list()
				if (res.code !== 1) return uni.$showMsg(res.msg)
				this.list = res.data

			},
		}
	}
</script>

<style lang="scss">
	page {
		padding: 24rpx 24rpx 200rpx;
		box-sizing: border-box;
		background: #FAFAFA;
	}

	.mt20 {
		margin-top: 20rpx;
	}
	
	.ml20 {
		margin-left: 20rpx;
	}

	.addcard {
		width: 700rpx;
		height: 129rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		padding: 44rpx 0rpx 44rpx 250rpx;
		box-sizing: border-box;
		position: fixed;
		bottom: 40rpx;
		text-align: center;
		z-index: 999;

		.text {
			font-size: 28rpx;
			color: #333333;
			margin-left: 16rpx;
		}
	}

	.card {
		margin-bottom: 20rpx;
		width: 700rpx;
		height: 258rpx;
		border-radius: 20rpx;
		padding: 40rpx;
		box-sizing: border-box;
		position: relative;
		z-index: 9;

		.img {
			width: 240rpx;
			height: 200rpx;
			position: absolute;
			bottom: 0;
			right: 0;
			z-index: 0;
		}

		.num {
			font-size: 46rpx;
			color: #FFFFFF;
			margin-top: 24rpx;
		}

		.left {
			.top {
				font-size: 32rpx;
				font-weight: 500;
				color: #FFFFFF;
			}

			.bom {
				font-size: 24rpx;
				color: rgba(255, 255, 255, 0.7);
				margin-top: 10rpx;
			}
		}

		.right {
			font-size: 28rpx;
			width: 125rpx;
			height: 50rpx;
			background-color: rgba(255, 255, 255, 0.5);
			border-radius: 25rpx;
			text-align: center;
			line-height: 50rpx;
			color: #FFFFFF;
			z-index: 9;
		}
	}
</style>