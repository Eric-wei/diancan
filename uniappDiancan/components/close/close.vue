<template>
	<!-- 悬浮结算 -->
	<view>
		<view class="box" :style="{paddingBottom:pb}">
			<view class="box_content" @tap="show=true">
				<view class="box_img">
					<image :src="Httpimg+'orderb.png'" mode="" v-if="Httpimg"></image>
					<text v-if="shopping_trolley_list.count>0">{{shopping_trolley_list.count || 0}}</text>
				</view>

			</view>
			<view class="flex ac">

				<view class="box_price">
					<text class="box_price1">合计¥</text>
					<text class="box_price2">{{shopping_trolley_list.after_total||0}}</text>
				</view>
				<view class="box_close flex jc ac" v-if="addAdish" @click="jiacai">
					确认加菜
				</view>
				<view class="box_close  flex jc ac" v-else @tap="gojs()">
					去结算
				</view>
			</view>
		</view>
		<u-popup :show="show" :round="10" zIndex='10071' mode="bottom" @close="show=false">
			<view class="upcart">
				<view class="upcart_top">
					<view class="upcart_top_left">
						已选商品
					</view>
					<view class="upcart_top_right" @tap="remove()">
						<u-icon name="trash" color="#676767 " size="30"></u-icon>
						<text>清空</text>
					</view>
				</view>
				<view class="upcart_order">
					<view style="margin-top: 150rpx;"
						v-if="!shopping_trolley_list.data ||shopping_trolley_list.data.length==0 ">
						<u-empty mode="data" :iconSize='150' :textSize='24' text='购物车空空如也'
							icon="/static/dc-empty.png"></u-empty>
					</view>
					<scroll-view scroll-y="true" style="height: 570rpx;">
						<view class="upcart_order_content" v-for="(item,index) in shopping_trolley_list.data"
							:key="item">
							<view class="upcart_order_content_img">
								<image :src="item.image" mode="aspectFill"></image>
							</view>
							<view class="upcart_order_content_title">
								<view>{{item.title}}</view>
								<view class="flexc" style="height: 40rpx;" @click="specification(item)">
									<view>{{item.item_name}}</view>
									<view v-if=" item.item_name" class="" style="padding: 8rpx 0 0rpx 10rpx;">
										<u-icon name='arrow-right' size='24'></u-icon>
									</view>
								</view>
								<view class="">
									<text>￥{{item.price}}</text>
									<view class="">
										<u-number-box v-model="value[index]" @change="valChange">
											<view slot="minus" class="minus" @tap="reduce(item,index,item.count)">
												<u-icon name="minus" labelSize='20px' color="#333" size="24"></u-icon>
											</view>
											<text slot="input" style="width: 50px;text-align: center;"
												class="input">{{value[index]||item.count}}</text>
											<view slot="plus" class="plus" @tap="add(item,index,item.count,1)">
												<u-icon name="plus" labelSize='20px' color="#FFFFFF" size="24"></u-icon>
											</view>
										</u-number-box>
									</view>
								</view>
							</view>
						</view>
						<view class="" style="height: 80rpx;"></view>
					</scroll-view>
				</view>
			</view>
		</u-popup>

		<specificationCard @carList='carList' :cart_id='cart_id' :editList='editList' ref='specificationCard'
			:order_id='order_id' :type='order_type' @order='carList' :show='specificationShow'
			@close='specificationShow=false'>
		</specificationCard>

	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_joinCar,
		order_reduce,
		order_empty,
		jiac,
	} from "@/api/comm.js"
	export default {
		name: "close",
		props: {
			shopping_trolley_list: {},
			seatShow: {
				type: Boolean
			},
			addAdish: {
				default: false
			},
			pb: {
				default: '10rpx'
			}
		},
		computed: {
			Httpimg() {
				return config.HttpImg
			}
		},
		data() {
			return {

				show: false,
				value: [],
				trolley_list: {},
				capsule_button: 2,
				tableNumber: uni.getStorageSync('tableNumber') || false,
				specificationShow: false,
				order_id: "",
				order_type: '',
				editList: [],
				cart_id: ''
			};
		},
		watch: {
			shopping_trolley_list: function(newval, oldval) {
				console.log(this.shopping_trolley_list)
				this.trolley_list = newval
				this.inputlenght()
			},
		},
		methods: {
			gojs(type) {
				if (this.tableNumber) {
					console.log(1)
					this.gotable(type)

				} else {
					console.log(2)
					this.goback(type)
				}
			},
			carList() {
				this.$emit('update')
				this.specificationShow = false
			},
			specification(item) {
				if (item.item_name) {
					this.show = false
					this.order_id = item.goods_id
					this.cart_id = item.id
					this.order_type = item.type
					if (item.type == 2) {
						let arr = item.item_id.split('_')
						this.editList = arr
					} else {
						this.editList = item.spu_info_ys
					}
					setTimeout(() => {
						this.specificationShow = true
						this.$refs.specificationCard.specification()
					}, 500)
				}


			},
			async jiacai(type) {
				if (type) {
					let id = uni.getStorageSync('orderId')
					let res = await jiac({
						id,
						c_type: type || 1
					})
					if (res.code == 1) {
						if (this.tableNumber) {
							uni.setStorageSync('tableNumber', false)
							uni.setStorageSync('option', 1)
							uni.reLaunch({
								url: '/pages/merchant/order/order'
							})
						} else {
							uni.reLaunch({
								url: '/pages/order_form/order_form'
							})
						}

					}
					return
				}
				if (this.shopping_trolley_list.count > 0) {
					let id = uni.getStorageSync('orderId')
					let res = await jiac({
						id,
						c_type: type || 1
					})
					if (res.code == 1) {
						if (this.tableNumber) {
							uni.setStorageSync('tableNumber', false)
							uni.setStorageSync('option', 1)
							uni.reLaunch({
								url: '/pages/merchant/order/order'
							})
						} else {
							uni.reLaunch({
								url: '/pages/order_form/order_form'
							})
						}

					}
				} else {
					uni.showToast({
						title: '请添加菜品',
						icon: 'none'
					})
				}

			},
			inputlenght() {
				this.value = []
				if (this.trolley_list.data) {
					this.trolley_list.data.forEach((res, index) => {
						this.value.push(res.count)
					})
				}
			},
			reduce(item, index, count) {
				if (item.is_cw == 1) {
					if (count > 1) {
						let num = count - 1
						uni.setStorageSync('peopleCount', num)
						this.$emit('update')
					}
				} else {
					this.order_reduce(item, item.spu_info_ys)
				}
			},
			add(item, index, count) {
				if (item.is_cw == 1) {
					let num = count - 0 + 1
					uni.setStorageSync('peopleCount', num)
					this.$emit('update')
				} else {
					this.add_joinCar(item, item.spu_info_ys)
				}
			},
			valChange(e) {},
			async add_joinCar(item, info) {
				let seat_id = uni.getStorageSync('scene')
				let data = await order_joinCar({
					store_id: item.store_id,
					goods_id: item.goods_id,
					spu_id: item.item_id,
					count: 1,
					spu_info: info,
					seat_id
				})
				this.$emit('update')
			},
			async order_reduce(item, info) {
				let data = await order_reduce({
					store_id: item.store_id,
					goods_id: item.goods_id,
					spu_id: item.item_id,
					count: 1,
					spu_info: info,
					cart_id: item.id
				})
				this.$emit('update')
			},

			// 清空购物车
			async remove() {
				let data = await order_empty()
				this.$emit('update')
				this.show = false
			},
			// 员工点餐去结算
			gotable(type) {
				if (type) {
					uni.navigateTo({
						url: '/pages/merchant/tableNumber/tableNumber?ctype=2'
					})
					return
				}
				if (this.shopping_trolley_list.count > 0) {
					if (this.shopping_trolley_list.data[0].is_cw == 1) {
						uni.showToast({
							title: '请至少选择一个商品',
							icon: 'none'
						})
					} else {
						uni.navigateTo({
							url: '/pages/merchant/tableNumber/tableNumber'
						})
					}
				} else {
					uni.showToast({
						title: '请至少选择一个商品',
						icon: 'none'
					})
				}
			},


			// 去结算
			goback(type) { //type 是否是立即购买
				let user = uni.getStorageSync('user')
				let peopleCount = uni.getStorageSync('peopleCount') || 0
				if (user.order_num == 1) {
					uni.showModal({
						title: '提示',
						content: '你有未支付订单,请支付过后重新提交',
						success: function(res) {
							if (res.confirm) {
								//未登录
								uni.reLaunch({
									url: '/pages/order_form/order_form'
								})
							} else if (res.cancel) {}
						}
					});
				} else {
					if (type) {
						let url;
						if (this.seatShow) {

							url = '/pages/order_all/affirm_order/affirm_order?ctype=2' + `&peopleCount=${peopleCount}`

						} else {
							let e = uni.getStorageSync('firstPay')
							if (e == 1) {
								url = '/pages/order_all/affirm_order/affirm_order?ctype=2' + `&peopleCount=${peopleCount}`
							} else {
								url = '/pages/scan/peopleOrder/peopleOrder?ctype=2' + `&peopleCount=${peopleCount}`
							}

						}
						if (!uni.getStorageSync('userinfo')) {
							this.$emit('register')
							return
						}
						uni.navigateTo({
							url
						})
						return
					}
					if (this.shopping_trolley_list.count > 0) {
						if (this.shopping_trolley_list.data[0].is_cw == 1) {
							uni.showToast({
								title: '请至少选择一个商品',
								icon: 'none'
							})
						} else {
							let url;
							if (this.seatShow) {
								if (type) {
									url = '/pages/order_all/affirm_order/affirm_order?ctype=2' +
										`&peopleCount=${peopleCount}`
								} else {
									url = '/pages/order_all/affirm_order/affirm_order' + `?peopleCount=${peopleCount}`
								}
							} else {
								let e = uni.getStorageSync('firstPay')
								if (e == 1) {
									if (type) {
										url = '/pages/order_all/affirm_order/affirm_order?ctype=2' +
											`&peopleCount=${peopleCount}`
									} else {
										url = '/pages/order_all/affirm_order/affirm_order' + `?peopleCount=${peopleCount}`
									}
								} else {
									url = '/pages/scan/peopleOrder/peopleOrder' + `?peopleCount=${peopleCount}`
								}

							}
							if (!uni.getStorageSync('userinfo')) {
								this.$emit('register')
								return
							}
							if (this.shopping_trolley_list.count > 0) {
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
					} else {
						console.log('进入了')
						uni.showToast({
							title: '请至少选择一个商品',
							icon: 'none'
						})
					}


				}
			}
		}
	}
</script>

<style lang="scss">
	.box {
		width: 100%;
		// height: 101rpx;
		background: #FFFFFF;
		box-shadow: 0rpx 3rpx 15rpx 0rpx rgba(191, 202, 211, 0.46);
		// border-radius: 51rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: fixed;
		bottom: 0rpx;
		left: 50%;
		transform: translateX(-50%);
		z-index: 10072;
		padding: 20rpx 30rpx 10rpx 30rpx;
		box-sizing: border-box;

		.box_content {
			display: flex;
			align-items: center;
		}

		.box_img {
			width: 64rpx;
			height: 64rpx;
			// margin: 0 30rpx;
			// margin-left: 50rpx;
			position: relative;

			text {
				width: 40rpx;
				height: 40rpx;
				background-color: #E40030;
				border-radius: 50%;
				text-align: center;
				line-height: 40rpx;
				color: #fff;
				position: absolute;
				right: -20rpx;
				top: -15rpx;
				font-size: 24rpx;
			}
		}

		.box_price {
			margin-right: 48rpx;

			.box_price1 {
				font-size: 28rpx;
				color: #333;
				margin-right: 6rpx;
			}

			.box_price2 {
				font-size: 42rpx;
				color: #333;
				font-weight: bold;
			}

		}

		.box_close {
			width: 173rpx;
			height: 72rpx;
			background: #E40030;
			border-radius: 8rpx;
			// border-radius: 0rpx 89rpx 89rpx 0rpx;
			// line-height: 101rpx;
			text-align: center;
			font-size: 30rpx;
			font-weight: bold;
			color: #fff;
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
		border-color: #E40030;
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
		background-color: #E40030;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}
</style>