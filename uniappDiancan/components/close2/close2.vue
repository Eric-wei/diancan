<template>
	<!-- 悬浮结算 -->
	<view>
		<view class="box">
			<view class="box_content">
				<view class="box_img">
					<image :src="Httpimg+'orderb.png'" mode="" v-if="Httpimg"></image>
				</view>
				<view class="box_price">
					<text>预约时间</text>
					<text v-if="timeDate">{{timeDate}}</text>
					<text v-else>--</text>
					<!-- <text style="font-size: 34rpx;">¥</text><text> {{shopping_trolley_list.deposit ||0}}</text> -->
				</view>
			</view>
			<view class="box_close" @tap="setshow">
				去预约
			</view>
		</view>
		<u-popup :show="show" :round="10" zIndex='10071' @close="show=false">
			<view class="popup_box">
				<view class="popup_box_title flexc flexs">
					<view class=""></view>
					<view class="">预约</view>
					<view class="" style="padding-top: 10rpx;box-sizing: border-box;" @click="show=false">
						<u-icon name="close" size="28"></u-icon>
					</view>
				</view>
				<view class="popup_box_ipt flexc flexs">
					<text style="width: 120rpx;text-align: left;">到店时间:</text>
					<view style="padding-left: 8rpx;" class="popup_box_ipt_right" @click="timeshow=true">
						<text v-if="ddtime">{{ddtime}}</text>
						<text v-else>请选择到店时间</text>
					</view>
				</view>
				<view class="popup_box_ipt flexc flexs">
					<text style="width: 120rpx;text-align: left;">姓名:</text>
					<input type="text" class="popup_box_ipt_right" placeholder="请输入姓名" v-model="subscribe_name" />
				</view>
				<view class="popup_box_ipt flexc flexs">
					<text style="width: 120rpx;text-align: left;">手机号:</text>
					<input type="text" class="popup_box_ipt_right" placeholder="请输入手机号" v-model="subscribe_tel"
						maxlength="11" />
				</view>
				<view class="popup_box_btns flexs">
					<view class="popup_box_btnc flex jc ac" @click="show=false">
						取消
					</view>
					<view class="popup_box_btn flex jc ac" @click="getreservation">
						确定
					</view>
				</view>
			</view>
		</u-popup>
		<u-datetime-picker :show="timeshow" v-model="value1" @cancel='timeshow=false' @confirm='timeConfirm'
			mode="time"></u-datetime-picker>
		<login :show="enter" @closepage='enter=false'></login>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_joinCar,
		order_reduce,
		order_empty,
		reservation,
	} from "@/api/comm.js"
	import wxparApi from "@/utils/wxApi.js"
	export default {
		name: "close",
		props: {
			shopping_trolley_list: {
				type: Object,
			},
			subscribe_time: {},
			subscribe_id: {},
			timeDate: {},
			price: {},
			pageurl: '',

		},
		data() {
			return {
				enter: false,
				show: false,
				value: [],
				trolley_list: {},
				capsule_button: 2,
				subscribe_name: null,
				subscribe_tel: null,
				timeshow: false,
				value1: '',
				ddtime: null,
				qdbtn: true
			};
		},
		computed: {
			Httpimg() {
				return config.HttpImg
			}
		},
		methods: {
			timeConfirm(e) {
				this.ddtime = e.value
				this.timeshow = false
			},
			setshow() {
				if (!this.subscribe_time) return uni.$showMsg('请选择预约时间')
				this.show = true
			},
			// 预约
			async getreservation() {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true

					return
				}


				if (this.qdbtn) {
					var reg = /^1[3-9]\d{9}$/;
					if (!this.subscribe_name) return uni.$showMsg('请输入姓名')
					if (!this.subscribe_tel) return uni.$showMsg('请输入手机号')
					if (!reg.test(this.subscribe_tel)) return uni.$showMsg('请输入正确手机号')
					if (!this.subscribe_time) return uni.$showMsg('请选择预约时间')
					if (this.price == '0.00') {
						let res = await reservation({
							subscribe_name: this.subscribe_name,
							subscribe_tel: this.subscribe_tel,
							subscribe_time: this.subscribe_time,
							id: this.subscribe_id,
							dd_time: this.ddtime
						})
						if (res.code == 1) {
							this.qdbtn = false
							uni.showToast({
								title: '预约成功',
								icon: 'none',
								duration: 2000,
								success() {
									setTimeout(() => {
										uni.reLaunch({
											url: '/pages/order_form/order_form?idx=3&tabidx=1'
										})
									}, 2000)
								}
							})
						} else {
							uni.$showMsg(res.msg)
						}
					} else {
						let obj = {
							subscribe_name: this.subscribe_name,
							subscribe_tel: this.subscribe_tel,
							subscribe_time: this.subscribe_time,
							id: this.subscribe_id,
							dd_time: this.ddtime
						}
						this.$emit('confrim', obj)
					}
					this.show = false
				}

			},
			reduce(item, index, count) {
				this.order_reduce(item, index, count)
			},
			add(item, index, count) {
				this.add_joinCar(item, index, count)
			},
			valChange(e) {},
			async add_joinCar(item, index, count) {
				let data = await order_joinCar({
					store_id: item.store_id,
					goods_id: item.goods_id,
					spu_id: item.item_id,
					count: 1
				})
				this.$emit('update')
			},
			async order_reduce(item, index, count) {
				let data = await order_reduce({
					store_id: item.store_id,
					goods_id: item.goods_id,
					spu_id: item.item_id,
					count: 1
				})
				this.$emit('update')
			},

			// 清空购物车
			async remove() {
				let data = await order_empty()
				this.$emit('update')
				this.show = false
			},
			goback(url) {
				if (!uni.getStorageSync('userinfo')) {
					this.$emit('register')
					// uni.showToast({
					// 	title: '请登录',
					// 	icon: 'none'
					// })
					return
				}
				if (this.trolley_list.data.length) {
					uni.navigateTo({
						url
					})
				} else {
					uni.showToast({
						title: '无商品可结算',
						icon: 'none'
					})
				}

			}
		}
	}
</script>

<style lang="scss">
	::v-deep .u-picker__view {
		.u-picker__view__column {
			text {
				height: 68rpx !important;
				line-height: 68rpx !important;
			}
		}
	}

	// ::v-deep .u-picker__view__column__item {
	// 	color: red !important;
	// 	height: 68rpx !important;
	// 	line-height: 68rpx !important;
	// }

	// .u-datetime-picker {
	// 	height: 34rpx !important;
	// 	line-height: 34rpx !important;
	// }

	.box {
		width: 700rpx;
		height: 101rpx;
		background: #FFFFFF;
		box-shadow: 0rpx 3rpx 15rpx 0rpx rgba(191, 202, 211, 0.46);
		border-radius: 8rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: fixed;
		bottom: 20rpx;
		left: 50%;
		transform: translateX(-50%);
		z-index: 12;

		.box_content {
			display: flex;
			align-items: center;
		}

		.box_img {
			width: 64rpx;
			height: 64rpx;
			margin: 0 30rpx;
			margin-left: 50rpx;
			position: relative;

			text {
				width: 40rpx;
				height: 40rpx;
				background-color: #05B6F6;
				border-radius: 50%;
				text-align: center;
				line-height: 40rpx;
				color: #fff;
				position: absolute;
				right: -20rpx;
				font-size: 24rpx;
			}
		}

		.box_price {
			text:nth-child(1) {
				width: 124rpx;
				font-size: 32rpx;
				font-weight: 400;
				color: #101010;
				line-height: 101rpx;
			}

			text:nth-child(2) {
				font-size: 32rpx;
				font-weight: 500;
				color: #E40030;
				line-height: 101rpx;
				margin-left: 10rpx;
			}

			text:nth-child(3) {
				font-size: 30rpx;
				font-weight: 500;
				color: #E40030;
				line-height: 101rpx;
				margin-left: 10rpx;
			}
		}

		.box_close {
			width: 173rpx;
			height: 100%;
			background: #E40030;
			border-radius: 8rpx;
			line-height: 101rpx;
			text-align: center;
			font-size: 32rpx;
			font-weight: bold;
			color: #FDFEFF;
		}
	}

	.upcart {
		width: 100%;
		height: 700rpx;

		.upcart_top {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 20rpx 30rpx;

			.upcart_top_left {
				font-size: 28rpx;
				font-weight: 400;
				color: #333333;
			}

			.upcart_top_right {
				font-size: 24rpx;
				font-weight: 400;
				color: #676767;
				display: flex;
				align-items: center;
			}
		}

		.upcart_order_content {
			display: flex;
			align-items: center;
			padding: 0rpx 20rpx;
			height: 135rpx;
			margin-bottom: 30rpx;

			.upcart_order_content_img {
				width: 136rpx;
				height: 135rpx;
				border-radius: 7rpx;
				overflow: hidden;
			}

			.upcart_order_content_title {
				width: 540rpx;
				margin-left: 20rpx;

				view:nth-child(1) {
					font-size: 28rpx;
					font-weight: 400;
					color: #353535;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #676767;
					padding: 15rpx 0;
				}

				view:nth-child(3) {
					height: 45rpx;
					font-size: 28rpx;
					font-weight: 500;
					color: #E45F3A;
					display: flex;
					align-items: center;
					justify-content: space-between;
				}
			}
		}
	}

	.minus {
		width: 22px;
		height: 22px;
		border-width: 1px;
		border-color: #05B6F6;
		border-style: solid;
		border-top-left-radius: 100px;
		border-top-right-radius: 100px;
		border-bottom-left-radius: 100px;
		border-bottom-right-radius: 100px;
		@include flex;
		justify-content: center;
		align-items: center;
	}

	.input {
		// padding: 0 10px;
	}

	.plus {
		width: 22px;
		height: 22px;
		background-color: #05B6F6;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}

	.popup_box {
		padding: 32rpx;
		box-sizing: border-box;
		background-color: #fff;
		border-radius: 20rpx;

		.popup_box_title {
			font-size: 32rpx;
			text-align: center;
		}

		.popup_box_ipt {
			font-size: 26rpx;
			padding: 32rpx 0;
			border-bottom: 1rpx solid #f8f8f8;

			.popup_box_ipt_right {
				width: 80%;
				font-size: 24rpx;
				color: #707070;
			}
		}

		.popup_box_btns {
			margin-top: 30rpx;

			.popup_box_btn {
				width: 48%;
				height: 80rpx;
				border-radius: 8rpx;
				box-sizing: border-box;
				background-color: #333;
				color: #fff;
				font-size: 30rpx;
				text-align: center;
				line-height: 80rpx;
			}

			.popup_box_btnc {
				width: 48%;
				height: 76rpx;
				border-radius: 8rpx;
				box-sizing: border-box;
				background-color: #fff;
				border: 2rpx solid #333;
				color: #333;
				font-size: 30rpx;
				text-align: center;
				line-height: 80rpx;
			}
		}
	}
</style>