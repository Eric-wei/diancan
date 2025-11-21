<template>
	<view>
		<view class="box">
			<view class="text_1">
				我的余额
			</view>
			<view class="text_2">
				{{userinfo.money}}
			</view>
			<view class="text_3 flexc" @tap="routerTo('/pages/me_all/recharge/recharge')">
				充值记录
				<view class="" style="margin-left: 10rpx;margin-top: 10rpx;">
					<u-icon name="arrow-right" color="#fff" size="24"></u-icon>
				</view>
			</view>
		</view>

		<view class="price">
			<view class="price_title flexc flexs">
				<text class="price_title_text1">选择套餐</text>
				<text style="margin-top: 8rpx;">一经充值，概不退换，不兑现</text>
			</view>
			<scroll-view scroll-x="true" style="width: 100%;  white-space: nowrap;">
				<view class="price_box"
					:style="{background:select_index==index?'#F7DAB0':'',	border: select_index==index?'4rpx solid #E0B373':''}"
					v-for="(item,index) in list" :key="item.id" @tap="select(index)">
					<view class="price_box_2" :style="{color:select_index==index?'#59380D ':''}">
						充{{item.amount}}
					</view>
					<view class="" style="font-size: 36rpx ;margin-top: 20rpx;"
						:style="{color:select_index==index?'#59380D ':''}">
						到账
					</view>
					<view class="price_box_3" :style="{color:select_index==index?'#59380D ':''}">
						<text>{{item.amount_over}}</text>
					</view>
				</view>
			</scroll-view>
		</view>
		<view class="price">
			<view class="price_title flexc flexs">
				<text class="price_title_text1">支付方式</text>
			</view>
			<radio-group>
				<label class="uni-list-cell uni-list-cell-pd">
					<view style="width: 750rpx;" class="flexc popBox_content_item" @click="checked=!checked">
						<view class="flexs" style="width: 90%;">
							<view class="flexc" style="width: 20%;">
								<image class="wximg" src="../../static/wx.svg" mode=""></image>
								微信
							</view>
							<radio :checked="checked" />
						</view>
					</view>

				</label>
			</radio-group>
		</view>
		<view class="fixedBtn flexc flexs">
			<view class="fixedBtn_price">
				￥{{price || '0.00'}}
			</view>
			<view class="btn" @click="pay">
				立即支付
			</view>
		</view>

	</view>
</template>

<script>
	import {
		store_info,
	} from '@/api/merchant.js'
	import {
		recharge,
		order_create,
		order_pay
	} from '@/api/comm.js'
	import {
		userInfo
	} from "@/api/public.js"
	import wxparApi from "@/utils/wxApi.js"
	export default {
		data() {
			return {
				price: "0.00",
				checked: true,
				user: {},
				list: [],
				select_index: 0,
				userinfo: uni.getStorageSync('user') || null,
				openid: ""
			};
		},

		onLoad() {
			this.recharge()
			this.getUser()
			let openid = uni.getStorageSync('openid')
			if (openid) {
				this.openid = openid
			}
		},
		methods: {
			async getUser() {
				let user = await userInfo()
				if (user.code == 1) {
					this.userinfo = user.data
					uni.setStorageSync('user', user.data)
				}
			},
			async pay() {
				if (!this.checked) return uni.$showMsg('请选择支付方式')
				let that = this
				let data = await order_create({
					type: 2,
					recharge_id: this.list[this.select_index].id,
				})
				if (data.code == 1) {
					let data_seed = await order_pay({
						orderNo: data.data.orderNo,
						openid: this.openid
					})
					let orderInfo = data_seed.data
					if (data_seed.code == 1) {
						wxparApi.wxparApi(data_seed.data, '', uni.getStorageSync(
							'public').message, '', 2)
						setTimeout(() => {
							that.getUser()
						}, 500)
					} else {
						uni.showToast({
							title: data_seed.msg,
							icon: 'none'
						})
					}
				} else {
					uni.showToast({
						title: data.msg,
						icon: 'none'
					})
				}

			},
			select(e) {
				this.select_index = e
				this.price = this.list[e].amount
			},
			async recharge() {
				let res = await recharge()
				if (res.code == 1) {
					this.list = res.data
					this.price = this.list[0].amount
				}
			},
			// async getstore_info() {
			// 	let res = await store_info()
			// 	if (res.code !== 1) return
			// 	this.user = res.data
			// },
			gopage(url) {
				uni.navigateTo({
					url
				})
			},
		}
	}
</script>

<style lang="scss">
	page {
		min-height: 100vh;
		padding: 26rpx 0;
		box-sizing: border-box;
		background: #FAFAFA;
	}

	.wximg {
		width: 35rpx;
		height: 35rpx;
		margin-right: 10rpx;
	}

	.fixedBtn {
		position: fixed;
		background-color: #fff;
		bottom: 0;
		left: 0;
		right: 0;

		.fixedBtn_price {
			font-size: 46rpx;
			color: #E40030;
			line-height: 100rpx;
			margin: 0 0 0 50rpx;
		}
	}

	.btn {
		width: 240rpx;
		height: 100rpx;
		background-color: #E40030;
		color: #fff;
		text-align: center;
		line-height: 100rpx;
		font-size: 28rpx;
	}

	.box {
		width: 94%;
		border-radius: 10rpx;
		background-color: #E40030;
		margin: 20rpx auto;
		padding: 40rpx;
		box-sizing: border-box;
		color: #fff;
		text-align: center;

		.text_3 {
			text-align: right;
			display: flex;
			justify-content: flex-end;
		}

		.text_1 {
			font-size: 30rpx;
		}

		.text_2 {
			margin-top: 36rpx;
			font-size: 44rpx;
		}
	}

	.price {
		margin: 20rpx 0;
		background-color: #fff;
		padding: 30rpx;
		box-sizing: border-box;

		.price_title {
			font-size: 26rpx;
			color: #868B96;
			margin-bottom: 20rpx;

			.price_title_text1 {
				font-size: 36rpx;
				color: #000;
			}
		}

		.price_box {
			width: 212rpx;
			height: 267rpx;
			background: #FDF6EC;
			border-radius: 15rpx;
			text-align: center;
			margin-right: 25rpx;
			position: relative;
			display: inline-block;
			margin-top: 20rpx;

			.price_box_1 {
				height: 47rpx;
				background: #7F4700;
				border-radius: 16rpx 0rpx 14rpx 0rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #FFF9F6;
				line-height: 47rpx;
				text-align: center;
				position: absolute;
				top: -20rpx;
				left: -1rpx;
				display: flex;
				align-items: center;
				padding-right: 10rpx;

				image {
					width: 30rpx;
					height: 30rpx;
					margin: 0 10rpx;
				}
			}

			.price_box_2 {
				font-size: 30rpx;
				font-weight: 400;
				color: #868B96;
				margin-top: 40rpx;
			}

			.price_box_3 {
				font-size: 48rpx;
				font-weight: 600;
				color: #835814;
				margin-top: 20rpx;
			}

			.price_box_4 {
				font-size: 22rpx;
				font-weight: 400;
				color: #A16421;
				margin-top: 20rpx;
			}
		}
	}
</style>