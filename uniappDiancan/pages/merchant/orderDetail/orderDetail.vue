<template>
	<view style="padding-bottom: 120rpx;" v-if="pageShow">
		<view class="order_details">
			<!-- v-if="detail.order_type<4" -->
			<view class="" style="background: #fff;;border-radius: 20rpx;padding-bottom: 20rpx;"
				:class="detail.order_type==4?'pb0':''">
				<view class="card_four" :class="detail.order_type==4?'pb0':''">
					<view class="titleuser flexc flexs">
						<view class="left flexc flex ac">
							<text class="ordertypetag" v-if="detail.order_type==1">自提</text>
							<text class="ordertypetag" v-if="detail.order_type==2">外卖</text>
							<text class="ordertypetag" v-if="detail.order_type==3">堂食</text>
							<text class="ordertypetag" v-if="detail.order_type==4">预约</text>
							<view class="order_avatar  flex ac">
								<image class="order_avatara" :src="avatar" mode="aspectFill"></image>
								<view class="order_avatarname">
									{{username}}
								</view>
							</view>
						</view>
						<view class="right">
							<text v-if="detail.status==1">待付款 </text>
							<text v-if="detail.status==2">待收货 </text>
							<text v-if="detail.status==3">已完成 </text>
							<text v-if="detail.status==4">待退款 </text>

							<text v-if="detail.status==5">已取消 </text>
							<text v-if="detail.status==6">备餐 </text>
							<text v-if="detail.status==7">出餐 </text>
							<text v-if="detail.status==8">待取单 </text>
							<text v-if="detail.status==9">已退款 </text>
						</view>
					</view>
					<view class="pd20" v-if="detail.detail.length&&detail.detail">
						<view class="refund" v-if="[1,2,3].includes(detail.order_type*1)" style="position: relative;">
							<view class="codebackimgs  flex jc">
								<view class="codebackimgts">
									<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg">
									</image>
									<view class="w100h100 codebacktt flex jc ac">
										<text v-if="detail.order_type==1">取餐号</text>
										<text v-if="detail.order_type==2">取餐号</text>
										<text v-if="detail.order_type==3">餐座号</text>
									</view>
								</view>
							</view>
							<view class="refund_text2" v-if="detail.order_type==3">
								<span style="padding:10rpx 14rpx;" v-for="(ite,inde) in  detail.seat_info"
									:key="inde">{{ite.seat_title}} </span>
							</view>
							<view class="refund_text" v-else>
								{{detail.code}}
							</view>
						</view>
					</view>
					<view class="title" v-if="detail.detail.length&&detail.detail">
						商品明细
					</view>
					<view class="" :style="{'max-height': heg}" style="overflow: hidden;"
						v-if="detail.detail.length&&detail.detail">
						<view class="shop flexc" v-for="item in detail.detail" :key="item">
							<image :src="item.goods_img" mode="aspectFill"></image>
							<view class="shop_item flex jc">
								<view class="flexs one">
									<view class="">{{item.goods_name}}</view>
									<view class="">¥{{item.goods_price}}</view>
								</view>
								<view class="flex ac jsb">
									<view class="two">
										{{item.item_name}}
									</view>
									<view class="three">
										×{{item.goods_count}}
									</view>
								</view>

							</view>
						</view>
					</view>


					<view class="" :class="detail.order_type==4?'pb0':''" :style="{'max-height': heg}"
						style="overflow: hidden;" v-if="detail.order_type==4">
						<view class="shop flexc" v-for="item in detail.seat_info" :key="item">
							<image :src="item.seat_img" mode="aspectFill"></image>
							<view class="shop_item flex jc">
								<view class="flexs one">
									<view class="">{{item.seat_title}}</view>
									<view class="">¥{{item.goods_price||'0.00'}}</view>
								</view>
								<view class="flexs two">
									<view class="">{{item.seat_title}}</view>
									<view class="">×{{item.goods_count||1}}</view>
								</view>

							</view>


						</view>
					</view>

					<view class="flexc setheg" v-if="detail.detail.length&&detail.detail">
						<view class="" @click="setheg">
							{{poptext}}(共{{detail.detail.length}}件)
						</view>
						<view class="">
							<u-icon name="arrow-down" v-if="poptext=='展开'" size="28rpx"></u-icon>
							<u-icon name="arrow-up" v-else size="28rpx"></u-icon>
						</view>
					</view>



				</view>
				<view class="remarksOrder " v-if='detail.order_type<4'>
					备注：{{detail.remark ||'无'}}
				</view>

				<view class="orderbottomdetail flex jsb ac pd24 mt24" v-if='detail.order_type<4'>
					<view class="orderTime">
						{{detail.createtime | timerFormat}}
					</view>
					<view class="flex ae">
						<view class="orderDetailnum" v-if="detail.coupon_money!==0">
							已优惠￥{{detail.coupon_money}}
						</view>
						<view class="orderDetailnum">
							合计
						</view>
						<view class="flex ae">
							<view class="orderDetailPriceicon">
								￥
							</view>
							<view class="orderDetailPrice">
								{{detail.order_amount_total}}
							</view>
						</view>
					</view>
				</view>
			</view>

			<view class="">

				<view class="card_three" v-if='detail.order_type<4'>
					<view class="card_threetitle itemborder">
						订单信息
					</view>
					<view class="item flexs flexc item_br">
						<view class="left">
							订单编号：
						</view>
						<view class="right flex ac">
							{{detail.order_no || ''}}
						</view>
					</view>
					<view class="item flexs flexc item_br">
						<view class="left">
							餐具：
						</view>
						<view class="right  flex ac">
							{{detail.tableware_count ||'无需餐具'}}
						</view>
					</view>
					<view class="item flexs flexc item_br">
						<view class="left">
							支付方式:
						</view>
						<view class="right  flex ac">
							{{pay_type==1?'微信':'钱包'}}
						</view>
					</view>
					<view class="item flexs flexc item_br" v-if="detail.address">
						<view class="left">
							配送地址：
						</view>
						<view class="right">
							{{detail.address}}
						</view>
					</view>

					<view class="item flexs flexc item_br" v-if="detail.subscribe_tel">
						<view class="left">
							预留手机号：
						</view>
						<view class="right  flex ac">
							{{detail.subscribe_tel}}
						</view>
					</view>

					<view class="item flexs flexc item_br" v-if="detail.subscribe_tel">
						<view class="left">
							预约时间：
						</view>
						<view class="right  flex ac">
							{{detail.subscribe_time | timerFormat}}
						</view>
					</view>

					<view class="item flexs flexc ">
						<view class="left">
							下单时间：
						</view>
						<view class="right  flex ac">
							{{detail.createtime | timerFormat}}
						</view>
					</view>

				</view>




			</view>


		</view>
		<reserve2 :content_list='detail' v-if='detail.order_type==4'></reserve2>
		<view class="funbuts flex je ac" v-if="detail.status==4||detail.business_status==1 && detail.status==6">
			<view class="funbutsA flex jc ac" v-if="detail.status==4" @click="resold_order">
				拒绝
			</view>
			<view class="funbutsB  flex jc ac" v-if="detail.status==4" @click="resold_order">
				同意退款
			</view>
			<view class="funbutsB flex jc ac" v-if='detail.business_status==1 && detail.status==6' @click="setorders">
				接单
			</view>
		</view>

		<view class="funbuts flex je ac" v-if="detail.business_status==2">
			<view class="funbutsA flex jc ac" v-if="detail.dc_type==2" @click="getPrint(detail.id)">
				推送后台
			</view>
			<template v-else>
				<view class="funbutsA  flex jc ac" v-if="detail.status !='4' " @click="getPrint(detail.id)">
					打印小票
				</view>
			</template>

			<view class="funbutsB flex jc ac" v-if='detail.status==6' @click="send_out(detail.id)">
				确认送出
			</view>
		</view>


	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_detail,
		receiving_orders,
		refund,
		resold_order,
		print,
		send_out
	} from '@/api/merchant.js'
	export default {
		data() {
			return {
				Httpimg: config.HttpImg,
				id: "",
				heg: '280rpx',
				poptext: "展开",
				detail: {},
				shopList: [],
				avatar: '',
				username: "",
				tel: "",
				order_type: null,
				userShow: false,
				delshow: false,
				value: 1,
				pageShow: false
			};
		},
		onLoad(e) {
			this.id = e.id
			this.avatar = e.avatar
			this.username = e.username
			this.tel = e.tel
			this.order_type = e.order_type
			this.getDetail()
		},
		methods: {
			// 确认接单
			async send_out(id) {
				let res = await send_out({
					id
				})
				if (res.code == 1) {
					this.detail.status = 7;
				}
				uni.$showMsg(res.msg)
			},
			// 打印小票
			async getPrint(id) {
				let res = await print({
					id
				})
				if (res.code == 1) {
					uni.$showMsg('打印成功')
				}
			},
			// 退款
			async resold_order(item) {
				let res = await resold_order({
					id: this.detail.id,
				})
				if (res.code == 1) {
					this.getDetail()
					uni.$showMsg('退款成功')
				} else {
					this.getDetail()
					uni.$showMsg(res.msg)
				}
			},
			async refund(item) {
				let res = await refund({
					'order_no': this.detail.order_no,
					'tag': 1
				})
				if (res.code == 1) {
					this.getDetail()
					uni.$showMsg('退款成功')
				} else {
					this.getDetail()
					uni.$showMsg(res.msg)
				}
			},
			// 接单
			async setorders() {
				let res = await receiving_orders({
					id: this.detail.id
				})
				uni.$showMsg(res.msg)
			},
			async getDetail() {
				let res = await order_detail({
					id: this.id
				})
				if (res.code == 1) {
					this.detail = res.data
					res.data.detail.forEach(item => {
						this.shopList.push(item)
					})
					this.pageShow = true
				}

			},
			setheg() {
				if (this.poptext == '展开') {
					this.heg = ''
					this.poptext = '收起'
				} else {
					this.heg = '280rpx'
					this.poptext = '展开'
				}
			},
		}
	}
</script>

<style lang="scss">
	.minus {
		border: 1rpx solid #00ABFF;
		width: 34rpx;
		height: 34rpx;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}

	.pb0 {
		padding-bottom: 0 !important;
	}


	.order_avatar {
		.order_avatara {
			width: 32rpx;
			height: 32rpx;
			border-radius: 999%;
			margin-right: 6rpx;
			border: 2rpx solid #333;
		}

		.order_avatarname {
			font-size: 24rpx;
			color: #777777;
		}
	}

	.funbuts {
		width: 100%;
		padding: 24rpx 32px 30rpx 32rpx;
		box-sizing: border-box;
		position: fixed;
		bottom: 0;
		left: 0;
		background: #fff;


		.funbutsA {
			width: 206rpx;
			height: 72rpx;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			border: 2rpx solid #777777;
			font-size: 30rpx;
			color: #777777;
			margin-left: 32rpx;
		}

		.funbutsB {
			width: 206rpx;
			height: 72rpx;
			background: #E40030;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			font-size: 30rpx;
			color: #FFFFFF;
			margin-left: 32rpx;
		}
	}




	.orderbottomdetail {
		line-height: 1;
		padding: 32rpx 20rpx 10rpx 20rpx;
		box-sizing: border-box;
		// margin-top: 30rpx;
	}

	.pd24 {
		padding: 0 24rpx;
		box-sizing: border-box;
	}

	.mt24 {
		margin-top: 24rpx;
	}

	.pd20 {
		padding: 24rpx 0;
		box-sizing: border-box;
	}

	.input {
		padding: 0 10px;
	}

	.plus {
		width: 34rpx;
		height: 34rpx;
		background-color: #00ABFF;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}

	.fixed {
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
		background-color: #fff;

		.btn {
			width: 750rpx;
			height: 120rpx;
			background: #00ABFF;
			text-align: center;
			line-height: 120rpx;
			color: #fff;
			font-size: 28rpx;
		}

		.btn1 {
			width: 375rpx;
			height: 120rpx;
			background: #FFFFFF;
			font-size: 34rpx;
			color: #777777;
			text-align: center;
			line-height: 120rpx;
		}

		.btn2 {
			width: 375rpx;
			height: 120rpx;
			background: #E40030;
			font-size: 34rpx;
			color: #ffffff;
			text-align: center;
			line-height: 120rpx;
		}
	}

	.card {
		width: 100vw;
		background-color: #fff;
		padding: 25rpx 34rpx;
		box-sizing: border-box;

		.black {
			font-size: 32rpx;
			color: #333333;
		}

		.orange {
			width: 520rpx;
			font-size: 30rpx;
			color: #FF9C2E;
		}

		.user {

			.left {
				image {
					width: 60rpx;
					height: 60rpx;
					border-radius: 50%;
					margin-right: 20rpx;
				}

				font-size: 32rpx;
				color: #333333;
			}

			.right {
				image {
					width: 60rpx;
					height: 60rpx;
					border-radius: 50%;
				}
			}


		}
	}

	.order_details {
		padding: 25rpx 25rpx 0 25rpx;


		.card_one {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 20rpx 30rpx;

			.left {
				font-size: 40rpx;
				color: #333333;
			}

			.right {
				font-size: 34rpx;
				color: #FF9C2E;
				line-height: 50rpx;
			}
		}

		.card_two {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 20rpx 30rpx;
			margin: 20rpx 0;

			.left {
				font-size: 32rpx;
				color: #333333;
			}

			.right {
				font-size: 32rpx;
				color: #333333;
			}
		}


		.itemborder {
			border-bottom: 2rpx solid #EBEBEB;
		}

		.card_three {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 14rpx 30rpx;
			margin: 20rpx 0;

			.card_threetitle {
				font-weight: bold;
				font-size: 28rpx;
				color: #333333;
				padding: 15rpx 0 24rpx 0;
				box-sizing: border-box;
			}

			.item1 {
				padding: 20rpx 0;
				box-sizing: border-box;
				text-align: center;

				.quna {
					width: 35rpx;
					height: 35rpx;
					margin-right: 12rpx;
				}

				.left {
					font-size: 24rpx;
					color: #333333;
				}

				.right {
					font-size: 34rpx;
					color: #FB2A57;
				}

				.one {
					font-size: 30rpx;
					color: #333333;
				}

				.two {
					font-size: 24rpx;
					color: #777777;
				}

				.thr {
					font-size: 42rpx;
					color: #333333;
				}
			}

			.item {
				min-height: 80rpx;
				padding: 24rpx 0;
				box-sizing: border-box;
				// line-height: 1;

				.left {
					font-size: 28rpx;
					color: #777777;

				}

				.right {
					font-size: 26rpx;
					color: #333333;
					max-width: 230px;
					text-align: right;

				}
			}

		}

		.card_four {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 24rpx 30rpx;
			// margin: 20rpx 0;

			.title {
				font-size: 32rpx;
				border-bottom: 1rpx solid #F2F2F2;
				color: #232323;
				padding: 0 0 12rpx;
				box-sizing: border-box;

				.title_right {
					padding-top: 6rpx;
					box-sizing: border-box;

					image {
						width: 38rpx;
						height: 38rpx;
						border-radius: 50%;
					}
				}
			}

			.setheg {
				width: 150rpx;
				font-size: 20rpx;
				color: #777777;
				text-align: center;
				margin: 34rpx auto 0;
			}

			.shop {
				margin: 20rpx 0;

				image {
					width: 122rpx;
					height: 120rpx;
					border-radius: 10rpx;
					margin-right: 20rpx;
				}

				.shop_item {
					width: 500rpx;
					flex-direction: column;

					.one {
						font-size: 28rpx;
						color: #333333;
					}

					.two {
						font-size: 24rpx;
						color: #AAAAAA;
						margin: 6rpx 0;
					}

					.three {
						font-size: 24rpx;
						color: #AAAAAA;
					}
				}
			}
		}

		.card_five {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 14rpx 30rpx;
			margin: 20rpx 0;
			font-size: 32rpx;
			color: #333333;

			.right {
				width: 530rpx;
			}

		}

	}

	.userPop {
		padding: 33rpx 40rpx 170rpx;
		box-sizing: border-box;

		.userPop_title {}

		.userPop_ipts {
			margin-top: 38rpx;
			font-size: 32rpx;
		}

		.userPop_ipt {
			border-bottom: 1rpx solid #EBEBEB;
			padding-bottom: 6rpx;

			input {
				width: 221rpx;
			}
		}
	}

	.delPop {
		padding: 70rpx 98rpx;
		box-sizing: border-box;

		.delPop_title {
			font-size: 40rpx;
			color: #232323;
			font-weight: 700;
		}

		.delpop_btns {
			margin-top: 76rpx;

			.delpop_qx_btn {
				width: 183rpx;
				height: 64rpx;
				background: #D8D8D8;
				border-radius: 8rpx;
				font-size: 32rpx;
				color: #FFFFFF;
				text-align: center;
				line-height: 64rpx;
			}

			.delpop_qd_btn {
				width: 183rpx;
				height: 64rpx;
				background: #00ABFF;
				border-radius: 8rpx;
				font-size: 32rpx;
				color: #FFFFFF;
				text-align: center;
				line-height: 64rpx;
			}

		}
	}
</style>