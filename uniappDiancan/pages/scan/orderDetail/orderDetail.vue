<template>
	<view style="padding-bottom: 120rpx;">
		<!-- 		<view class="card" v-if='detail.dc_type==1'>
			<view class="user flexc flexs">
				<view class="left flexc">
					<image :src="avatar" mode="aspectFill"></image>
					<view class="" style="line-height: 64rpx;">
						{{username || ''}} {{tel || ''}}
					</view>
				</view>
				<view class="right">
					<image @click="openTel(tel)" src="@/static/icon11.svg" mode=""></image>
				</view>
			</view>

		</view> -->
		<reserve2 :content_list='detail' v-if='detail.order_type==4'></reserve2>

		<view class="order_details">
			<!-- <view class="card_one flexs flexc" v-if='detail.order_type<4'>
				<view class="" v-if="detail.order_type==3" style="width: 80%;">
					餐座号： <span style="margin-right: 16rpx;" v-for="(item,index) in  detail.seat_info"
						:key="index">{{item.seat_title }}</span>
				</view>
				<view class="left" v-else>
					订单号：{{detail.code || ''}}
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
					<text v-if="detail.status==10">预约中 </text>
				</view>
			</view> -->

			<!-- <view class="card_two flexs flexc" v-if='detail.order_type<4'>
				<view class="left">配送方式：</view>
				<view class="right" v-if="detail.order_type==1">自取</view>
				<view class="right" v-if="detail.order_type==2">外卖</view>
				<view class="right" v-if="detail.order_type==3">堂食</view>
			</view> -->



			<view class="" style="background-color: #fff;border-radius: 20rpx;padding-bottom: 20rpx;">
				<view class="titleuser flexc flexs">
					<view class="left flexc flex ac">
						<text class="ordertypetag" v-if="detail.order_type==1">自提</text>
						<text class="ordertypetag" v-if="detail.order_type==2">外卖</text>
						<text class="ordertypetag" v-if="detail.order_type==3">堂食</text>
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
				<view class="pd20">
					<view class="refund" v-if="[1,2,3].includes(detail.order_type*1)" style="position: relative;">
						<view class="codebackimgs  flex jc">
							<view class="codebackimgts">
								<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg"></image>
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


				<view class="card_four" v-if='detail.order_type<4'>
					<view class="title">
						商品明细
					</view>
					<view class="" :style="{'max-height': heg1}" style="overflow: hidden;">
						<view class="shop flexc" v-for="item in detail.detail" :key="item">
							<image :src="item.goods_img" mode="aspectFill"></image>
							<view class="shop_item flex jc">
								<view class="flexs one">
									<view class="">{{item.goods_name}}</view>
									<view class="">¥{{item.goods_price}}</view>
								</view>
								<view class="flex jsb ac">
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
					<template v-if="detail">
						<view class="flexc setheg" v-if="detail.detail>2">
							<view class="" @click="setheg1">
								{{poptext1}}(共{{detail.detail.length}}件)
							</view>
							<view class="">
								<u-icon name="arrow-down" v-if="poptext1=='展开'" size="28rpx"></u-icon>
								<u-icon name="arrow-up" v-else size="28rpx"></u-icon>
							</view>
						</view>
					</template>
				</view>
				<view class="" v-if="detail.order_type==3">
					<view class="card_four" v-for="(item1,index) in detail.else_detail">
						<view class="title flexc flexs line">
							<view class="blod size18">
								加餐菜品{{index+1}}
							</view>
							<view class="flexc title_right" @click="setuserShow(item1.username,item1.mobile)">
								<span class="size14 hue1">加餐人员：</span>
								<image style="margin: 0 11rpx;" :src="item1.avatar" mode="aspectFill"></image>
								<span class="size14 blod">{{item1.username}}</span>
							</view>
						</view>
						<view class="" :style="{'max-height': heg}" style="overflow: hidden;">
							<view class="shop flexc" v-for="item in item1.detail" :key="item">
								<image :src="item.goods_img" mode="aspectFill"></image>
								<view class="shop_item flex jc">
									<view class="flex ac jsb">
										<view class="flexs flexc one">
											<view class="me-text-beyond blod">
												{{item.goods_name }}
											</view>
										</view>
										<view class="size14 hue4">
											￥{{item.goods_price}}

										</view>
									</view>


									<view class="three flex_end flexs">
										<view class="two  me-text-beyond">
											{{item.item_name}}
										</view>
										<view class="size14 hue4">
											<span class="hue1 size12"
												style="margin-left: 6rpx;">x{{item.goods_count}}</span>

										</view>
									</view>
								</view>
							</view>
						</view>

						<view class="flexc setheg" v-if="item1.count>2">
							<!-- v-if="shopList.length>1" -->
							<view class="" @click="setheg">
								{{poptext}}(共{{item1.count}}件)
							</view>
							<view class="">
								<u-icon name="arrow-down" v-if="poptext=='展开'" size="28rpx"></u-icon>
								<u-icon name="arrow-up" v-else size="28rpx"></u-icon>
							</view>
						</view>
						<!-- <view class="flex je">
							<view class="scBtn" v-if="item1.is_sc==0 && detail.status==6"
								@click="scelse(item1.numbers)">
								确认送出
							</view>
						</view> -->
					</view>
				</view>
				<!-- <u-line dashed></u-line> -->
				<view class="remarksOrder ">
					备注：{{detail.remark ||'无'}}
				</view>
				<!-- <u-line dashed></u-line> -->
				<view class="orderbottomdetail flex jsb ac">
					<view class="orderTime">
						{{detail.createtime | timerFormat}}
					</view>
					<view class="flex ae">
						<!-- 	<view class="orderDetailnum">
							共 {{detail.detail.length*1 + detail.else_detail.length*1}} 件
						</view> -->
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



			<!-- <view class="flexs card_five">
				<view class="left">备注：</view>
				<view class="right">{{detail.remark ||'无'}}</view>
			</view> -->

			<!-- 	<view class="card_three" v-if='detail.order_type<4'>
				<view class="item1 flexs flexc" style="border-bottom: 1rpx solid #EBEBEB;"
					v-if="detail.coupon_money!==0">
					<view class="flexc left">
						<image class="quna" src="../static/icon12.png" mode=""></image>红包/抵用券：
					</view>
					<view class="right">
						-￥{{detail.coupon_money}}
					</view>
				</view>
				<view class="item1 flexs flexc">
					<view class="one">
						共计：
					</view>
					<view class="right">
						<text class="two" v-if="detail.coupon_money!==0">已优惠￥{{detail.coupon_money}}</text><text
							class="thr">￥{{detail.order_amount_total}}</text>
					</view>
				</view>
			</view> -->

			<view class="card_three">
				<view class="card_threetitle itemborder">
					订单信息
				</view>
				<view class="item flexs flexc ">
					<view class="left">
						下单时间：
					</view>
					<view class="right">
						{{detail.createtime | timerFormat}}
					</view>
				</view>
				<view class="item flexs flexc" v-if="detail.cwf_fee*1>0">
					<view class="left">
						餐位费：
					</view>
					<view class="right">
						￥{{detail.cwf_fee}}
					</view>
				</view>
				<view class="item flexs flexc " v-if="detail.order_no">
					<view class="left">
						订单编号：
					</view>
					<view class="right">
						{{detail.order_no || ''}}
					</view>
				</view>
				<view class="item flexs flexc " v-if="detail.code">
					<view class="left">
						订单号：
					</view>
					<view class="right">
						{{detail.code || ''}}
					</view>
				</view>
				<view class="item flexs flexc ">
					<view class="left">
						支付方式：
					</view>
					<view class="right">
						{{detail.pay_type ==2?'余额':detail.pay_type ==1?"微信":'未支付'}}
					</view>
				</view>
			</view>
			<!-- <view class="card_two flexs flexc" v-if="detail.dc_type == 1">
				<view class="left">
					:
				</view>
				<view class="right">
					微信
				</view>
			</view> -->
			<view class="" v-if="detail.dc_type==2 && detail.status==1">
				<view class="content_Bom flexc flexs">
					<view class="btm1" @click="addAdish(detail.id,detail.seat_id,detail.people_count)">
						去加餐
					</view>
					<view class="btm2" @click="over(detail.id)">
						确认完成
					</view>
					<view class="btm2" @click="accounts(detail.order_no)">
						扫码结账
					</view>
				</view>
			</view>

			<view class="" v-else>
				<view class="fixed flexc flexs" v-if="detail.status==4">
					<view class="btn1">
						拒绝
					</view>
					<view class="btn2">
						同意退款
					</view>
				</view>
				<view class="fixed flexc flexs" v-if='detail.business_status==1 && detail.status==6'>
					<view class="btn" @click="setorders">
						接单
					</view>
				</view>


				<!-- v-if="detail.business_status==2 && detail.status==6" -->
				<view class="funbuts flex je ac" v-if="detail.business_status==2 ">
					<view class="funbutsA flex jc ac" @click="getPrint()" v-if="item.status !='4' ">
						打印小票
					</view>
					<view class="funbutsB flex jc ac" @click="send_out()" v-if="detail.status==6">
						确认送出
					</view>
				</view>
			</view>



		</view>

		<u-popup :show="userShow" :round="40">
			<view class="userPop">
				<view class="userPop_title flexc flexs">
					<view class="size18 blod">
						用户信息
					</view>
					<view class="" @click="userShow=false">
						<u-icon name='close'></u-icon>
					</view>
				</view>
				<view class="userPop_ipts flexc flexs">
					<view class="userPop_ipt flexc">
						姓名：<input :disabled='true' type="text" v-model="user" />
					</view>
					<view class="userPop_ipt flexc">
						电话：<input :disabled='true' type="text" v-model="mobile" />
					</view>
				</view>
			</view>
		</u-popup>




		<u-popup :show="delshow" :safeAreaInsetBottom='false' :round="40" mode="center">
			<view class="delPop">
				<view class="delPop_title">你是否确定删除此菜品</view>
				<view class="delpop_btns flexc flexs">
					<view class="delpop_qx_btn" @click="delshow=false">
						取消
					</view>
					<view class="delpop_qd_btn" @click="setDelShow">
						确定
					</view>
				</view>
			</view>
		</u-popup>



	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_detail,
		receiving_orders,
		print,
		send_out,
		add_reduce_menu,
		over,
		pay_code
	} from '@/api/merchant.js'

	export default {
		data() {
			return {
				Httpimg: config.HttpImg,
				id: "",
				heg: '280rpx',
				poptext: "展开",
				heg1: '280rpx',
				poptext1: "展开",
				detail: {},
				shopList: [],
				shopList2: [],
				avatar: '',
				username: "",
				tel: "",
				order_type: null,
				userShow: false,
				delshow: false,
				value: 1,
				user: '',
				mobile: '',
				menuid: '',
				userId: ''
			};
		},
		onLoad(e) {
			this.id = e.id
			this.getDetail(e)
		},
		methods: {
			accounts(order_no) {
				this.scanCode().then(res => {
					let a = JSON.parse(res.result)
					pay_code({
						order_no,
						user_id: a.user_id
					}).then(res1 => {
						setTimeout(() => {
							this.unloadpage()
						}, 500)
					})

				})
			},
			async over(id) {
				let res = await over({
					id
				})
				if (res.code == 1) {
					this.getDetail()
				} else {
					uni.$showMsg(res.msg)
				}
			},
			addAdish(id, seat_id, peopleCount) {
				uni.setStorageSync('option', 3)
				uni.setStorageSync('tableNumber', true)
				uni.setStorageSync('scene', seat_id)
				uni.setStorageSync('addAdish', true)
				uni.setStorageSync('orderId', id)
				uni.setStorageSync('peopleCount', peopleCount)
				uni.navigateTo({
					url: '/pagesB/merchantOrder/merchantOrder'
				})
			},
			setDelShow() {
				this.delshow = false
				this.menu(this.menuid, 2)
			},
			add_reduce_menuApi(detail_id, type, count, userId) {
				this.userId = userId
				if (type == 2) {
					if (count == 1) {
						this.menuid = detail_id
						this.delshow = true
					} else {
						this.menu(detail_id, type)
					}
				} else {
					this.menu(detail_id, type)
				}

			},
			async menu(detail_id, type) {
				let res = await add_reduce_menu({
					id: this.detail.id,
					detail_id,
					type
				})
				if (res.code == 1) {
					this.getDetail()
				}
			},

			setuserShow(name, mobile) {
				this.user = name
				this.mobile = mobile
				this.userShow = true
			},
			// 打印小票
			async getPrint() {
				let res = await print({
					id: this.id
				})
				if (res.code == 1) {
					uni.$showMsg('打印成功')
				}
			},
			async scelse(num) {
				let res = await send_out({
					id: this.id,
					numbers: num
				})
				if (res.code == 1) {
					this.getDetail()
				}
				uni.$showMsg(res.msg)
			},

			// 确认接单
			async send_out(id) {
				let res = await send_out({
					id: this.id
				})
				if (res.code == 1) {
					this.getDetail()
				}
				uni.$showMsg(res.msg)
			},
			// 接单
			async setorders() {
				let res = await receiving_orders({
					id: this.detail.id
				})
				if (res.code == 1) {
					this.getDetail()
				}
				uni.$showMsg(res.msg)
			},
			async getDetail(e) {
				let res = await order_detail({
					id: this.id
				})
				if (res.code == 1) {
					this.detail = res.data
					if (res.data.dc_type == 1) {
						this.avatar = e.avatar;
						this.username = e.username;
						this.tel = e.tel
						this.order_type = e.order_type
					} else {
						this.avatar = this.detail.avatar;
						this.username = this.detail.username;
					}

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
			setheg1() {
				if (this.poptext1 == '展开') {
					this.heg1 = ''
					this.poptext1 = '收起'
				} else {
					this.heg1 = '280rpx'
					this.poptext1 = '展开'
				}
			},

		}
	}
</script>

<style lang="scss">
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

	.scBtn {
		background-color: #E40030;
		color: #fff;
		border-radius: 8rpx;
		text-align: center;
		padding: 20rpx 44rpx;
		box-sizing: border-box;


		margin: 10rpx;
	}

	.pd20 {
		padding: 0 20rpx;
		box-sizing: border-box;
	}

	.itemborder {
		border-bottom: 2rpx solid #EBEBEB;
	}

	.mb30 {
		margin-bottom: 30rpx;
	}


	.orderbottomdetail {
		line-height: 1;
		padding: 32rpx 20rpx 10rpx 20rpx;
		box-sizing: border-box;
		// margin-top: 30rpx;
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

	.titleuser {
		// border-bottom: 1rpx solid #EBEBEB;
		padding: 28rpx 20rpx;
		box-sizing: border-box;

		.left {
			.left_num {
				padding: 4rpx 10rpx;
				box-sizing: border-box;
				background: #E40030;
				border-radius: 10rpx;
				font-size: 40rpx;
				font-weight: 500;
				color: #FFFFFF;
				text-align: center;
				line-height: 64rpx;
				margin-right: 20rpx;
			}

			.left_text {
				font-size: 36rpx;
				font-weight: 600;
				color: #333333;
				text-align: center;
				line-height: 64rpx;
			}
		}

		.right {
			font-weight: bold;
			font-size: 28rpx;
			color: #FA5151;
		}
	}

	.minus {
		border: 1rpx solid #FF9C2E;
		width: 34rpx;
		height: 34rpx;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}

	.content_Bom {
		margin-top: 50rpx;

		.btm1 {
			width: 200rpx;
			height: 76rpx;
			border-radius: 8rpx;
			border: 1rpx solid #FF9C2E;
			font-size: 34rpx;
			color: #FF9C2E;
			text-align: center;
			line-height: 76rpx;
		}

		.btm2 {
			width: 200rpx;
			height: 76rpx;
			background: #FF9C2E;
			border-radius: 8rpx;
			font-size: 34rpx;
			color: #FFFFFF;
			text-align: center;
			line-height: 76rpx;
			border: 1rpx solid #FF9C2E;
		}


	}

	.input {
		padding: 0 10px;
	}

	.plus {
		width: 34rpx;
		height: 34rpx;
		background-color: #FF9C2E;
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
			background: #E40030;
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
			background: #FF9C2E;
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
		padding: 25rpx 25rpx 200rpx;

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
				height: 80rpx;
				line-height: 80rpx;

				.left {
					font-size: 28rpx;
					color: #777777;
				}

				.right {
					font-size: 28rpx;
					color: #333333;
				}
			}

		}

		.card_four {
			background: #FFFFFF;
			border-radius: 10rpx;
			padding: 24rpx 30rpx 0;
			margin: 20rpx 0;

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
						margin-bottom: 8rpx;
					}

					.two {
						font-size: 24rpx;
						color: #AAAAAA;
						margin: 6rpx 0;
					}

					.three {
						font-size: 24rpx;
						color: #333;
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
				background: #FF9C2E;
				border-radius: 8rpx;
				font-size: 32rpx;
				color: #FFFFFF;
				text-align: center;
				line-height: 64rpx;
			}

		}
	}
</style>