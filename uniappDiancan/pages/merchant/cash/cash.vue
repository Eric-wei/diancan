<template>
	<view>
		<view class="Withdrawal">
			<view class="flex je">

				<view class="withdrawalHistory" @click="gopage('/pages/merchant/txdetail/txdetail')">
					提现记录
				</view>
			</view>

			<view class="withdrawalTitle">
				账户余额(元)
			</view>
			<view class="canWithdrawal">
				￥{{user.remaining_amount||0}}
			</view>
		</view>
		<view class="card_one">
			<view class="top">
				提现金额
			</view>
			<view class="center flexc flexs">
				<view class="flexc">
					<text class="one">￥</text>
					<input class="two" v-model="type" type="number" placeholder="请输入金额">
				</view>
				<view class="thr" @click="settype">
					全部提现
				</view>
			</view>
			<!-- <view class="bom">
				可提现金额 ￥ {{user.remaining_amount}}
			</view> -->
		</view>
		<view class="card">
			<view class="withdrawalTitle">
				提现方式
			</view>
			<!-- 		<view class=" flexc flexs">
				<view class="">
					提现方式
				</view>
				<view class="flexc " style="padding: 10rpx 0 0;box-sizing: border-box;" @click="setshow">
					<image src="../static/icon13.png" mode=""></image>
					<view class="" style="line-height: 30rpx;margin: 0 10rpx;" v-if='list.length!==0'>
						{{bank_name}}({{bank_code | lastfour}})
					</view>
					<view class="" style="line-height: 30rpx;margin: 0 10rpx;font-size: 26rpx;" v-else>
						请选择
					</view>
					<view class="">
						<u-icon name="arrow-right" size="28"></u-icon>
					</view>
				</view>
			</view> -->
			<view class="">
				<view class="withdrawaltype flex jsb ac">
					<view class=" flex ac">
						<image class="withdrawaltypeimg" src="/static/images/withdrawal.png" mode=""></image>
						<view class="withdrawaltypetitle">
							微信
						</view>
					</view>
					<view class="checkboxs flex jc ac" @click="chooseType('wx')">
						<image class="checkboxselected" src="/static/images/couponc.png" mode=""
							v-if="withdrawaltype=='wx'">
						</image>
						<view class="checkboxnotselected" v-else>

						</view>
					</view>
				</view>
				<view class="withdrawaltype flex jsb ac">
					<view class=" flex ac" @click="setshow">
						<image class="withdrawaltypeimg" src="/static/images/yhk.png" mode=""></image>
						<view class="">
							<view class="withdrawaltypetitle">
								银行卡
							</view>
							<view class="withdrawaltypetitlecode">
								{{bank_name}}({{bank_code | lastfour}})
							</view>
						</view>
					</view>
					<view class="checkboxs flex jc ac" @click="chooseType('yhk')">
						<image class="checkboxselected" src="/static/images/couponc.png" mode=""
							v-if="withdrawaltype=='yhk'">
						</image>
						<view class="checkboxnotselected" v-else>

						</view>
					</view>
				</view>
			</view>
			<view class="qrBtn flex jc ac" @click="getdrawal">
				确认提现
			</view>
		</view>

		<view class="content">
			温馨提示: 提现申请发起后,预计在3个工作日内到账。
		</view>

		<u-popup :show="show" :round="20" @close="close">
			<view>
				<view class="popTitle flexc flexs">
					<view class=""></view>
					<view class="">请选择提现账号</view>
					<view class="" @click="gopage('pages/merchant/addCard/addCard')">添加</view>
				</view>
				<scroll-view scroll-y="true" style="height: 650rpx;">
					<view class="" v-if="list.length!==0">
						<view class="popItem flexc flexs ac" v-for="(item,i) in list" :key="i"
							@click="setaddIdx(i,item.id,item.type,item.bank_name,item.bank_code,item.wx_code,zfb_code)">
							<view class="left flexc">
								<!-- v-if="item.type==1" -->
								<image class='img' src="/static/images/yhk.png" mode="aspectFill"></image>
								<!-- <image class='img' v-if="item.type==2" src="../static/wx.svg" mode="aspectFill"></image>
								<image class='img' v-if="item.type==3" src="../static/zfb.svg" mode="aspectFill">
								</image> -->
								<view class="">
									<view class="name" v-if="item.type==2">
										微信({{item.wx_code | lastfour}})
									</view>
									<view class="name" v-if="item.type==3">
										支付宝({{item.zfb_code | lastfour}})
									</view>
									<view class="name" v-if="item.type==1">
										{{item.bank_name}}({{item.bank_code | lastfour}})
									</view>
								</view>
							</view>
							<!-- right -->
							<view class=" checkboxs">
								<image class="checkboxselected" src="/static/images/couponc.png" mode=""
									v-if='addIdx==i'>
								</image>
								<view class="checkboxnotselected" v-else>

								</view>
								<!-- <image v-if='addIdx==i' src="../static/icon15.svg" mode=""></image>
								<image src="../static/icon16.svg" mode="" v-else></image> -->
							</view>
						</view>
					</view>
					<view class="" v-else>
						<emptyPage></emptyPage>
					</view>
				</scroll-view>

			</view>
		</u-popup>
	</view>
</template>

<script>
	import {
		withdrawal_account_list,
		withdrawal
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				withdrawaltype: 'wx',
				show: false,
				addIdx: 0,
				balance: 0,
				type: '',
				bank_id: '',
				list: [],
				bank_name: '',
				bank_code: "",
				user: uni.getStorageSync('shopuser')
			};
		},
		onLoad(e) {
			this.balance = e.balance
			this.getList()
		},
		methods: {
			chooseType(type) {
				this.withdrawaltype = type;
			},
			gopage(url) {
				this.show = false
				uni.navigateTo({
					url
				})
			},
			// 提现
			async getdrawal() {
				if (this.user.user_type == 1) {
					let obj = null;
					if (this.withdrawaltype == 'yhk') {
						obj = {
							money: this.type,
							bank_id: this.bank_id
						}
					}
					if (this.withdrawaltype == 'wx') {
						obj = {
							money: this.type,
						}
					}

					if (this.type == '' || this.type <= 0.00) return uni.$showMsg('没有可提现金额')
					let res = await withdrawal(obj)
					if (res.code == 1) {
						uni.$showMsg(res.msg)
						setTimeout(() => {
							uni.navigateBack()
						}, 1000)
					} else {
						uni.$showMsg(res.msg)
					}
				} else {
					uni.$showMsg('您没有提现权限')
				}

			},
			settype() {
				if (this.user.user_type == 1) {
					this.type = this.user.remaining_amount
				} else {
					uni.$showMsg('您没有提现权限')
				}
			},
			async getList() {
				let res = await withdrawal_account_list()
				if (res.code == 1) {
					this.list = res.data
					if (this.list[this.addIdx].type == 1) {
						this.bank_name = this.list[this.addIdx].bank_name
						this.bank_code = this.list[this.addIdx].bank_code
						this.bank_id = this.list[this.addIdx].id
					} else if (this.list[this.addIdx].type == 2) {
						this.bank_name = '微信'
						this.bank_code = this.list[this.addIdx].wx_code
						this.bank_id = this.list[this.addIdx].id
					} else {
						this.bank_name = '支付宝'
						this.bank_code = this.list[this.addIdx].zfb_code
						this.bank_id = this.list[this.addIdx].id
					}

				} else {
					uni.$showMsg(res.msg)
				}


			},

			goback() {
				uni.navigateBack()
			},
			setaddIdx(i, id, type, name, code, wxcode, zfbcode) {
				this.addIdx = i //单选
				this.bank_id = id //账户id
				this.show = false
				if (type == 2) {
					this.bank_name = '微信'
					this.bank_code = wxcode
				} else if (type == 3) {
					this.bank_name = '支付宝'
					this.bank_code = zfbcode
				} else {
					this.bank_name = name
					this.bank_code = code
				}
			},
			close() {
				this.show = false
			},
			setshow() {
				if (this.user.user_type == 1) {
					this.show = true
					this.getList()
				} else {
					uni.$showMsg('您没有提现权限')
				}

			}
		}
	}
</script>

<style lang="scss">
	.withdrawalTitle {
		font-weight: bold;
		font-size: 34rpx;
		color: #333333;
	}

	.withdrawaltype {
		margin-top: 48rpx;

		.withdrawaltypeimg {
			width: 48rpx;
			height: 48rpx;
			margin-right: 20rpx;
		}

		.withdrawaltypetitle {
			font-weight: 400;
			font-size: 34rpx;
			color: #333333;
		}

		.withdrawaltypetitlecode {
			font-size: 24rpx;
			color: #777777;
			margin-top: 5rpx;
		}
	}


	.checkboxs {
		width: 48rpx;
		height: 48rpx;

		.checkboxnotselected {
			width: 34rpx;
			height: 34rpx;
			border: 2rpx solid #999;
			border-radius: 999%;
		}

		.checkboxselected {
			width: 40rpx;
			height: 40rpx;
			border: 2rpx solid #fff;
			border-radius: 999%;
		}
	}

	.Withdrawal {
		width: 686rpx;
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

	.add {
		padding: 34rpx 40rpx;
		box-sizing: border-box;
		margin-right: 20rpx;
		border-top: 1rpx solid #E4E4E4;

		.left {
			line-height: 90rpx;

			image {
				width: 90rpx;
				height: 90rpx;
			}
		}
	}

	.popItem {
		padding: 24rpx 40rpx;
		box-sizing: border-box;
		margin-right: 20rpx;
		border-top: 1rpx solid #f3f3f3;

		.head {
			width: 90rpx;
			height: 90rpx;
			border-radius: 50%;
			margin-right: 14rpx;
		}

		.left {
			.name {
				font-size: 32rpx;
				color: #333333;
				margin: 0 0 10rpx 20rpx;
				line-height: 80rpx;
			}

			.img {
				width: 80rpx;
				height: 80rpx;
				border-radius: 50%;
				background-color: #f5f5f5;
				text-align: center;
				line-height: 80rpx;
			}

			.phone {
				font-size: 28rpx;
				color: #777777;
			}
		}

		.right {
			line-height: 100rpx;

			image {
				width: 40rpx;
				height: 40rpx;
			}
		}
	}

	.popTitle {
		text-align: center;
		margin: 40rpx 0;
		padding: 0 24rpx;
		box-sizing: border-box;
	}

	page {
		background: #FAFAFA;
	}

	.qrBtn {
		width: 646rpx;
		height: 80rpx;
		background: #E40030;
		border-radius: 8rpx 8rpx 8rpx 8rpx;
		font-size: 30rpx;
		color: #FFFFFF;
		margin: auto;
		margin-top: 100rpx;
	}

	.content {
		width: 100%;
		text-align: center;
		height: 33rpx;
		font-size: 24rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: #AAAAAA;
		line-height: 33rpx;
		letter-spacing: 1px;
		margin: 0 auto;
	}

	.navbar {
		padding: 122rpx 50rpx 30rpx;
		box-sizing: border-box;
		background-color: #fff;
	}

	.card {
		width: 700rpx;
		// height: 122rpx;
		background: #FFFFFF;
		border-radius: 14rpx;
		margin: 20rpx auto;
		padding: 30rpx 24rpx;
		box-sizing: border-box;

		image {
			width: 30rpx;
			height: 30rpx;
		}

	}

	.card_one {
		width: 700rpx;
		// height: 296rpx;
		background: #FFFFFF;
		border-radius: 14rpx;
		margin: 0 auto;
		padding: 32rpx 25rpx;
		box-sizing: border-box;

		.top {
			font-size: 28rpx;
			color: #2F3337;
		}

		.center {
			// border-bottom: 1rpx solid #EBEBEB;
			padding: 22rpx 0;
			box-sizing: border-box;
			margin-top: 48rpx;

			.one {
				font-size: 40rpx;
				color: #2F3337;
				margin-right: 16rpx;
			}

			.two {
				width: 300rpx;
				height: 50rpx;
				font-size: 48rpx;
				color: #2F3337;
			}

			.thr {
				font-size: 26rpx;
				color: #E40030;
				margin-top: 10rpx;
			}
		}

		.bom {
			font-size: 24rpx;
			color: #777777;
		}
	}
</style>