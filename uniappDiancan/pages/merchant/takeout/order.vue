<template>
	<view class="takeoutOrder flex">
		<u-navbar title="外卖订单" leftIconSize="0" :placeholder="true" :fixed="true" :autoBack="false">
		</u-navbar>


		<view class="ordertopbar flex jsb ac">
			<view class="flex ac">
				<view class="ordertopbarl flex ac">
					<view class="ordertopbarlli" :class="'ordertopbarllic'">
						订单
						<view class="ordertopbarllilines flex jc ac">
							<view class="ordertopbarlliline">

							</view>
						</view>
					</view>
					<!-- 	<view class="ordertopbarlli">
						售后
					</view> -->
				</view>
				<view class="automatic flex ac">
					<view class="round">

					</view>
					<view class="automatict">
						{{shopuser.auto_order==1?'自动接单中':'手动接单中'}}
					</view>
					<u-icon name="arrow-right" color="#333" size="22"></u-icon>
					<!-- <view class="automaticgo"></view> -->
				</view>
			</view>


			<view class="flex ac">
				<view class="iconicon" @click.stop="issearch=!issearch">
					<u-icon name="search" color="#333" size="48"></u-icon>
				</view>
				<view class="iconicon" @click="go('/pages/me_all/messagenotification/messagenotification')">
					<u-icon name="bell" color="#333" size="48"></u-icon>
				</view>
			</view>
		</view>

		<view class="daytype flex ac">
			<view class="day flex ac" @click.stop="showTime=true">
				<text>{{Texttime}}</text>
				<u-icon name="arrow-down" color="#333" size="22"></u-icon>
			</view>
			<view class="daytypeli" :class="!sxtime&&[0,1,2,3].includes(current*1)?'daytypelic':''"
				@click.stop="viewallorder">
				全部
			</view>
			<view class="daytypeli" :class="!sxtime&&current==5?'daytypelic':''" @click="tabbutclick(5)">
				已取消
			</view>
		</view>
		<view class="tabsbut flex jsb ac" v-if="current<4">
			<view class="tabsbutli flex jc ac" :class="current==0?'tabsbutlic':''" @click="tabbutclick(0)">
				全部订单
			</view>
			<view class="tabsbutli flex jc ac" :class="current==1?'tabsbutlic':''" @click="tabbutclick(1)">
				待确认
			</view>
			<view class="tabsbutli flex jc ac" :class="current==2?'tabsbutlic':''" @click="tabbutclick(2)">
				已出餐
			</view>
			<view class="tabsbutli flex jc ac" :class="current==3?'tabsbutlic':''" @click="tabbutclick(3)">
				已完成
			</view>
		</view>
		<view class="ordertopbar flex jsb ac" v-if="issearch">
			<view class="search_card flex jsb ac">
				<view class="search f1">
					<u-search searchIconSize='30' shape="square" height='50rpx' placeholder="请输入订单号/手机号"
						:showAction="false" v-model="keyword" @blur="blur">
					</u-search>
				</view>
				<view class="search_btn flex jc ac" @click="searchInp">
					查询订单
				</view>
			</view>
		</view>

		<view class="flex orderlist f1" style="position: relative;">
			<scroll-view style="width: 100%;height: 100%;" :scroll-y="list.length>2">
				<view class="" v-if="list.length==0">
					<emptyPage></emptyPage>
				</view>
				<view class="pad" v-else>
					<view class="card_item" v-for="item in list" :key="item.id"
						@click.stop="gopage(item.id,item.avatar,item.username,item.subscribe_tel,item.order_type)">
						<view class="title flexc flexs">
							<view class="left flexc flex ac">
								<text class="ordertypetag" v-if="item.order_type==1">自提</text>
								<text class="ordertypetag" v-if="item.order_type==2">外卖</text>
								<text class="ordertypetag" v-if="item.order_type==3">堂食</text>
								<view class="order_avatar  flex ac">
									<image class="order_avatara" :src="item.avatar" mode="aspectFill"></image>
									<view class="order_avatarname">
										{{item.username}}
									</view>
								</view>
								<!-- 	<view class="left_num" v-if="item.code">
							{{item.code}}
						</view>
						<view class="left_text" v-if="item.order_type==1">自取订单</view>
						<view class="left_text" v-if="item.order_type==2">外卖订单</view>
						<view class="left_text" v-if="item.order_type==3">
							堂食订单
						</view> -->
							</view>
							<view class="right">
								<text v-if="item.status==1">待付款 </text>
								<text v-if="item.status==2">待收货 </text>
								<text v-if="item.status==3">已完成 </text>
								<text v-if="item.status==4">待退款 </text>

								<text v-if="item.status==5">已取消 </text>
								<text v-if="item.status==6">备餐 </text>
								<text v-if="item.status==7">出餐 </text>
								<text v-if="item.status==8">待取单 </text>
								<text v-if="item.status==9">已退款 </text>
							</view>
						</view>

						<view class="content">
							<view class="refund" style="position: relative;">
								<view class="codebackimgs  flex jc">
									<view class="codebackimgts">
										<image class="codebackimg" :src="Httpimg+'qccode.png'" mode=""></image>
										<view class="w100h100 codebacktt flex jc ac">
											<text v-if="item.order_type==3">餐座号</text>
											<text v-if="item.order_type==1">取餐号</text>
											<text v-if="item.order_type==2">取餐码</text>
											<!-- <text v-if="item.order_type==4">取餐码</text> -->
										</view>
									</view>
								</view>
								<view class="refund_text2" v-if="item.order_type==3">
									<text v-for="(ite,inde) in item.seat_info">{{ite.seat_title}}</text>
								</view>
								<view class="refund_text" v-if="[1,2].includes(item.order_type*1) ">
									{{item.code || ''}}
								</view>
							</view>


							<!-- 	<view class="content_head flexs" v-if="item.dc_type==1">
						<view class="left flexc">
							<image :src="item.avatar" mode="aspectFill"></image>
							<view class="">
								{{item.username}}
							</view>
						</view>
						<image class='rightimg' v-if="item.subscribe_tel" @click="openTel(item.subscribe_tel)"
							src="../static/icon11.svg" mode="">
						</image>
					</view> -->

							<view class="shops" :style="{'max-height': item.heg}">
								<view class="gray">
									商品明细:
								</view>
								<view class="shop flexc" v-for="item1 in item.detail" :key="item1.goods_id">
									<image :src="item1.goods_img" mode="aspectFill"></image>
									<view class="shop_item flex ac flexwrap">
										<view class="flexs one w100">
											<view class="">{{item1.goods_name}}</view>
											<view class="">¥{{item1.goods_price}}</view>
										</view>
										<view class="flex jsb ac w100">
											<view class="two">
												{{item1.item_name}}
											</view>
											<view class="three">
												×{{item1.goods_count}}
											</view>
										</view>

									</view>
								</view>
							</view>
							<view class="flexc-s setheg " v-if="item.detail.length>2">
								<view class="" @click.stop="setheg(item.id)" v-if="item.heg=='320rpx'">
									展开(共{{item.detail.length}}件)
								</view>
								<view class="" @click.stop="addheg(item.id)" v-else>
									收起(共{{item.detail.length}}件)
								</view>
								<view class="">
									<u-icon name="arrow-down" v-if="poptext=='展开'" size="28rpx"></u-icon>
									<u-icon name="arrow-up" v-else size="28rpx"></u-icon>
								</view>
							</view>
							<view class="orderDetailtimeprice flex jsb ac">
								<view class="orderDetailtime">
									{{item.createtime | timerFormat}}
								</view>
								<view class="flex ae">
									<view class="orderDetailtime">
										共{{item.detail.length}}件
									</view>
									<view class="orderDetailprice">
										¥{{item.order_amount_total}}
									</view>
								</view>
							</view>
							<u-line dashed></u-line>
							<!-- <view class="content_item" v-if="item.order_type==3">
						<text class="gray">订单类型：</text><span v-if="item.p_type==1">先付后餐</span><span
							v-if="item.p_type==2">先餐后付</span>
					</view> -->

							<!-- 					<view class="content_item">
						<text class="gray">下单时间：</text>{{item.createtime | timerFormat}}
					</view>

					<view class="content_item">
						<text class="gray">订单编号：</text>{{item.order_no}}
					</view>

					<view class="content_item flexs flexc"
						@click="gopage(item.id,item.avatar,item.username,item.subscribe_tel,item.order_type)">
						<view class="">
							<text class="gray">订单金额：</text><text class="black">¥{{item.order_amount_total}}</text>
						</view>
						<view class="" style="padding-top: 16rpx;box-sizing: border-box;">
							<u-icon name="arrow-right" size="28rpx"></u-icon>
						</view>
					</view> -->
							<view class="content_item" v-if="item.status==4">
								<text class="gray">退款原因：</text><text class="orange">{{item.apply||'无'}}</text>
							</view>


							<view class="" v-if="item.dc_type==2 && item.status==1">
								<view class="content_Bom flex je ac">
									<view class="btm3" @click.stop="getPrint(item.id)">
										推送后台
									</view>
									<view class="btm4" @click.stop="accounts(item.order_no)">
										扫码结账
									</view>

									<view class="btm3" @click.stop="addAdish(item.id,item.seat_id)">
										去加餐
									</view>
									<view class="btm4" @click.stop="over(item.id)">
										确认完成
									</view>
								</view>
							</view>
							<view class="" v-else>
								<view class="content_Bom flex ac jsb" v-if="item.business_status==1 && item.status==6">
									<view class="">
										<view class="content_item" v-if="item.order_type==3">
											<!-- <text class="gray">订单类型：</text> -->
											<span v-if="item.p_type==1">先付后餐</span>
											<span v-if="item.p_type==2">先餐后付</span>
										</view>
									</view>
									<view class="jdbtn flex jc ac" @click.stop="receiving_orders(item.id)">
										接单
									</view>
								</view>
								<view class="content_Bom flex je ac" v-if="item.business_status==2">
									<view class="btm3" @click.stop="getPrint(item.id)" v-if="item.dc_type==2">
										推送后台
									</view>
									<template v-else>
										<view class="btm3" @click.stop="getPrint(item.id)" v-if="item.status !=='4' ">
											打印小票
										</view>
									</template>
									<view class="btm4" @click.stop="send_out(item.id)" v-if="item.status==6">
										确认送出
									</view>
								</view>

								<view class="content_Bom flex je ac" v-if="item.status==4 ">
									<view class="btm3" @click.stop="resold_order(item)">
										拒绝退款
									</view>
									<view class="btm4" @click.stop="refund(item)">
										同意退款
									</view>
								</view>

							</view>


						</view>
					</view>
				</view>
				<!-- <view class="Bench">
					
				</view> -->
				<u-datetime-picker :show="showTime" v-model="times" :maxDate="maxData" mode="date" @confirm='confirmA()'
					@cancel='showTime=false'></u-datetime-picker>
			</scroll-view>
		</view>
		<sjtabbar :indexShow='true' :pagesPath='pagesPath'></sjtabbar>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_list,
		send_out,
		print,
		refund,
		receiving_orders,
		over,
		pay_code,
		resold_order
	} from '@/api/merchant.js'
	import _ from 'lodash';
	export default {
		data() {
			return {
				showTime: false,
				Texttime: '全部',
				sxtime: '',
				times: Number(new Date()), // 获取的时间未处理
				maxData: Number(new Date()),
				issearch: false,
				Httpimg: config.HttpImg,
				current: 0,
				poptext: "展开",
				pagesPath: '/pages/merchant/takeout/order',
				keyword: '',
				list1: [{
					name: '全部',
				}, {
					name: '待接单',
				}, {
					name: '已接单',
				}, {
					name: '已完成'
				}, {
					name: '待退款'
				}, {
					name: '待支付'
				}, {
					name: '已退款'
				}],
				heg: '320rpx',
				status: 2,
				list: [],
				page: 1,
				last_page: '',
				mark: true
			};
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		onLoad(e) {
			if (e.idx) {
				this.current = e.idx
			}
			this.getlist()
		},
		onShow() {
			this.page = 1
			this.getlist()
		},
		onReachBottom() {
			if (this.page < this.last_page) {
				this.page++
				this.getlist(1)
			} else {
				if (this.mark) {
					this.mark = false
					uni.$showMsg('已经到达最底部了')
				}
			}
		},
		methods: {
			viewallorder() {
				this.current = 0;
				this.Texttime = '全部';
				this.sxtime = '';
				this.page = 1;
				this.getlist()
			},
			returntime(time) {
				let times = "";
				if (time) {
					times = this.$way.yearFormats(time)
				} else {
					times = ''
				}
				return times;
			},
			confirmA(e) {
				this.showTime = false;
				this.sxtime = e;
				if (this.returntime(Number(new Date())) == this.returntime(e.value)) {
					this.Texttime = '今日'
					this.page = 1;
					this.getlist()
				} else {
					this.Texttime = this.returntime(this.times)
					this.page = 1;
					this.getlist()
				}
				console.log(e)
			},
			searchInp() {
				this.page = 1;
				this.getlist()
			},
			// 退款
			async resold_order(item) {
				let res = await resold_order({
					id: item.id,
				})
				if (res.code == 1) {
					this.list.forEach(res => {
						if (res.id == item.id) {
							item.status = 6
						}
					})
					this.$forceUpdate()
				} else {
					this.getDetail()
					uni.$showMsg(res.msg)
				}
			},
			accounts(order_no) {
				this.scanCode().then(res => {
					let a = JSON.parse(res.result)
					pay_code({
						order_no,
						user_id: a.user_id
					}).then(res1 => {
						if (res1.code == 1) {
							uni.$showMsg('支付成功')
							setTimeout(() => {
								this.unloadpage()
							}, 500)
						} else {
							uni.$showMsg(res1.msg)
						}
					}).catch(err => {})

				})
			},
			addAdish(id, seat_id) {
				uni.setStorageSync('option', 3)
				uni.setStorageSync('tableNumber', true)
				uni.setStorageSync('scene', seat_id)
				uni.setStorageSync('addAdish', true)
				uni.setStorageSync('orderId', id)
				uni.navigateTo({
					url: '/pagesB/merchantOrder/merchantOrder'
				})
			},
			// 接单
			async receiving_orders(id) {
				let res = await receiving_orders({
					id
				})
				if (res.code == 1) {
					uni.$showMsg(res.msg)
					this.getlist()
				}
			},
			// 退款
			async refund(item) {
				let res = await refund({
					'order_no': item.order_no,
					'tag': 1
				})
				if (res.code == 1) {
					this.list.forEach(res => {
						if (res.id == item.id) {
							item.status = 9
						}
					})
					this.$forceUpdate()
					uni.$showMsg('退款成功')
				} else {
					this.getlist()
					uni.$showMsg(res.msg)
				}
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
			async over(id) {
				let res = await over({
					id
				})
				if (res.code == 1) {
					this.list.forEach(item => {
						if (item.id == id) {
							item.status = 3
						}
					})
				} else {
					uni.$showMsg(res.msg)
				}
			},
			// 确认接单
			async send_out(id) {
				let res = await send_out({
					id
				})
				if (res.code == 1) {
					this.list.forEach(item => {
						if (item.id == id) {
							item.status = 7
						}
					})
				}
				uni.$showMsg(res.msg)
			},
			// 打电话
			getphone(e) {
				this.openTel(e)
			},
			tabbutclick(status) {
				this.current = status;
				this.keyword = '';
				this.page = 1;
				this.getlist()
			},
			tabclick(e) {
				this.page = 1
				this.current = e.index
				this.keyword = ''
				this.getlist()
			},
			async getlist(e) {
				let res = await order_list({
					status: this.current,
					search: this.keyword,
					page: this.page,
					type: 0,
					order_type: 2,
					date: this.returntime(this.sxtime)
				})
				if (e == 1) {
					this.list = [...this.list, ...res.data.data]
				} else {
					this.list = res.data.data
				}
				this.last_page = res.data.last_page
			},
			addheg(id) {
				this.list.forEach(item => {
					if (item.id == id) {
						item.heg = '320rpx'
					}
				})
			},
			setheg(id) {
				this.list.forEach(item => {
					if (item.id == id) {
						item.heg = ''
					}
				})
			},
			go(page) {
				uni.navigateTo({
					url: page
				})
			},
			gopage(id, avatar, username, tel, order_type) {
				if (order_type == 3) {
					uni.navigateTo({
						url: `/pages/scan/orderDetail/orderDetail?id=${id}&avatar=${avatar}&username=${username}&tel=${tel}&order_type=${order_type}`
					})
				} else {
					uni.navigateTo({
						url: `/pages/merchant/orderDetail/orderDetail?id=${id}&avatar=${avatar}&username=${username}&tel=${tel}&order_type=${order_type}`
					})
				}

			}
		}
	}
</script>

<style lang="scss">
	page {
		min-height: 100vh;
		background: #FAFAFA;
		// padding: ;
		box-sizing: border-box;
		background: #FAFAFA;
	}

	.orderlist {
		flex-direction: column;
		flex: 1;
		overflow: hidden;
	}

	.daytype {
		padding: 24rpx 32rpx;
		box-sizing: border-box;
		background: #fff;

		.day {
			font-weight: 500;
			font-size: 30rpx;
			color: #333333;
		}

		.daytypeli {
			font-size: 30rpx;
			color: #AAAAAA;
			margin-left: 42rpx;
		}

		.daytypelic {
			font-weight: bold;
			font-size: 30rpx;
			color: #333333;
		}

	}

	.tabsbut {
		padding: 24rpx 32rpx;
		box-sizing: border-box;
		background: #fff;

		.tabsbutli {
			width: 128rpx;
			height: 56rpx;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			border: 2rpx solid #777777;
			font-size: 24rpx;
		}

		.tabsbutlic {
		background: #E40030;
		border: 2rpx solid #E40030;
			color: #fff;
		}
	}


	.iconicon {
		margin-left: 48rpx;
	}

	.ordertopbar {
		width: 750rpx;
		// height: 86rpx;
		background: #FFFFFF;
		border-radius: 0rpx 0rpx 0rpx 0rpx;
		border-bottom: 2rpx solid #F2F2F2;
		padding: 0rpx 32rpx 24rpx 32rpx;
		box-sizing: border-box;

		.ordertopbarl {}

		.ordertopbarllic {
			font-weight: bold;
			color: #333 !important;
		}

		.ordertopbarlli {
			width: 120rpx;
			height: 58rpx;
			font-size: 30rpx;
			color: #AAAAAA;
			margin-right: 12rpx;
			text-align: center;
			position: relative;

			.ordertopbarllilines {
				position: absolute;
				bottom: 0;
				left: 0;
				width: 120rpx;
			}

			.ordertopbarlliline {
				width: 58rpx;
				height: 6rpx;
				background: #E40030;
				border-radius: 28rpx 28rpx 28rpx 28rpx;
			}

		}
	}


	.automatic {
		padding: 8rpx 20rpx;
		box-sizing: border-box;
		background: #FFF9ED;
		border-radius: 46rpx;
		line-height: 1;

		.round {
			width: 16rpx;
			height: 16rpx;
			border-radius: 999%;
			background: #E40030;

		}

		.automatict {
			font-size: 24rpx;
			color: #333333;
			margin-left: 8rpx;
			margin-right: 5rpx;
		}

		.automaticgo {}
	}


	.takeoutOrder {
		width: 100%;
		height: 100vh;
		flex-direction: column;
	}

	.orderDetailtimeprice {
		margin-bottom: 24rpx;

		.orderDetailtime {
			font-size: 24rpx;
			color: #777777;
		}

		.orderDetailprice {
			font-size: 28rpx;
			color: #333333;
			font-weight: bold;
			margin-left: 10rpx;
		}
	}


	.navbar {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		z-index: 999;
	}

	.fixed {
		height: 10vh;
		text-align: center;
		line-height: 13vh;
		font-size: 26rpx;
	}

	.search_card {
		width: 100%;
		margin-bottom: 10rpx;

		.search {
			width: 400rpx;
		}

		.search_btn {
			width: 180rpx;
			height: 50rpx;
			background: #E40030;
			border-radius: 8rpx;
			text-align: center;
			font-size: 24rpx;
			color: #FFFFFF;
			margin-left: 24rpx;
		}
	}

	.card_top {
		// height: 200rpx;
		background-color: #fff;
		padding: 0rpx 35rpx 10rpx;
		box-sizing: border-box;


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


	.pad {
		// margin-top: 3vh;
		padding: 24rpx 24rpx 0rpx 24rpx;
		box-sizing: border-box;

		.card_item {
			width: 700rpx;
			background: #FFFFFF;
			border-radius: 20rpx;
			margin-bottom: 24rpx;

			box-sizing: border-box;

			.title {
				border-bottom: 1rpx solid #EBEBEB;
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
					color: #333333;
				}
			}

			.content {
				padding: 24rpx;
				box-sizing: border-box;

				.content_Bom {
					margin: 20rpx 0 0;

					.jdbtn {
						width: 128rpx;
						height: 56rpx;
						background: #E40030;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						font-size: 24rpx;
						color: #FFFFFF;

						// // width: 572rpx;
						// // height: 76rpx;
						// background: #DDB45C;
						// border-radius: 8rpx;
						// text-align: center;
						// // line-height: 76rpx;
						// font-size: 34rpx;
						// color: #FFFFFF;
						// padding: 14rpx 40rpx;
						// box-sizing: border-box;
					}

					.btm1 {
						width: 200rpx;
						height: 76rpx;
						border-radius: 10rpx;
						border: 1rpx solid #FF9C2E;
						font-size: 34rpx;
						color: #FF9C2E;
						text-align: center;
						line-height: 76rpx;
					}

					.btm2 {
						width: 200rpx;
						height: 76rpx;
						background: #E40030;
						border-radius: 8rpx;
						font-size: 34rpx;
						color: #FFFFFF;
						text-align: center;
						line-height: 76rpx;
						border: 1rpx solid #E40030;
					}

					.btm3 {
						// width: 300rpx;
						// height: 76rpx;
						padding: 14rpx 16rpx;
						box-sizing: border-box;
						border-radius: 10rpx;
						border: 2rpx solid #777777;
						font-size: 24rpx;
						color: #777777;
						text-align: center;
						// line-height: 76rpx;
						margin-left: 30rpx;
					}

					.btm4 {
						padding: 14rpx 16rpx;
						box-sizing: border-box;

						// width: 300rpx;
						// height: 76rpx;
						background: #E40030;
						border-radius: 8rpx;
						font-size: 24rpx;
						color: #FFFFFF;
						text-align: center;
						// line-height: 76rpx;
						border: 2rpx solid #E40030;
						margin-left: 30rpx;
					}
				}

				.content_item {
					font-size: 30rpx;
					font-weight: 400;
					color: #333333;
					margin: 20rpx 0;
				}

				.orange {
					font-size: 30rpx;
					color: #FF9C2E;
				}

				.gray {
					font-size: 30rpx;
					color: #777777;
				}

				.black {
					font-size: 36rpx;
					font-weight: 600;
					color: #333333;
				}

				.content_head {
					.left {
						image {
							width: 60rpx;
							height: 60rpx;
							border-radius: 50%;
							margin-right: 20rpx;
						}

						font-size: 32rpx;
						font-weight: 600;
						color: #333333;
						line-height: 60rpx;
					}

					.rightimg {
						width: 60rpx;
						height: 60rpx;
						border-radius: 50%;
					}
				}

				.setheg {
					padding: 30rpx 0 16px;
					box-sizing: border-box;
					width: 690rpx;
					text-align: center;
					font-size: 24rpx;
					color: #777777;
					text-align: center;
					margin: 0 auto;
				}

				.shops {
					overflow: hidden;
					margin-top: 24rpx;

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

							.one {
								font-size: 30rpx;
								color: #333333;
							}

							.two {
								font-size: 24rpx;
								color: #AAAAAA;
								margin: 6rpx 0;
							}

							.three {
								font-size: 24rpx;
								color: #777777;
							}
						}
					}
				}

			}


		}
	}
</style>