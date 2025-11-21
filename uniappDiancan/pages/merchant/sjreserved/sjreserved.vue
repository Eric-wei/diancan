<template>
	<view>
		<view class="sjreserved flex jsb">
			<u-navbar title="预约列表" leftIconSize="0" :placeholder="true" :fixed="false" :autoBack="false">
			</u-navbar>
			<view class="navbar">
				<!-- <view class="fixed">
					预约列表
				</view> -->
				<view class="card_top">
					<view class="search_card flexs">
						<view class="search">
							<u-search searchIconSize='30' shape="square" height='70rpx' placeholder="请输入订单号/手机号"
								:showAction="false" v-model="keyword">
							</u-search>
						</view>
						<view class="search_btn" @click="getlist">
							查询订单
						</view>
					</view>

					<view class="tabs">
						<u-tabs @click='tabclick' :is-scroll="false" lineColor="#E40030" :current='current'
							lineHeight='6rpx' lineWidth='30rpx' :list="list1"
							:activeStyle="{color: '#303133',fontWeight: '600',fontSize:'34rpx'}">
						</u-tabs>
					</view>
				</view>
			</view>
			<view class="f1 flex lists" style="height: 0;">
				<scroll-view :scroll-y="content_list.length>2" class="f1 scroll" @scrolltolower='loadmore'>
					<block>
						<!-- <view class="recordhei"></view> -->
						<view style="margin-top: 350rpx;" v-if="content_list.length<1">
							<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
								text='暂无订单'></u-empty>
						</view>
						<view class="record" v-for="(item,index) in content_list" :key="item"
							@click="gopage(item.id,item.avatar,item.username,item.subscribe_tel)">
							<view class="record_content">
								<view class="record_content_left">
									<view class="record_content_left_1">
										<!-- {{item.store}} -->
										{{item.order_no}}
									</view>
									<view class="record_content_left_2">
										{{item.createtime}}
									</view>
								</view>
								<view class="record_content_right">
									<!-- 预约 -->
									<text v-if="item.order_type==4&&item.status==1">待付款</text>
									<text v-if="item.order_type==4&&item.status==10">预约中</text>
									<text v-if="item.order_type==4&&item.status==3">已完成</text>
									<text v-if="item.order_type==4&&item.status==4">待退款</text>
									<text v-if="item.order_type==4&&item.status==5">已取消</text>
								</view>
							</view>
							<expensedetail2 :item='item' :sign='"merchat"'></expensedetail2>
							<view class="record_pay">
								<view @tap.stop="getcancelReservation(item.order_no,item.id)"
									v-if="item.order_type==4&&( item.status==10 || item.status==1) ">
									取消预约
								</view>
								<view @tap.stop="completeOrder(item.order_no)"
									v-if="item.order_type==4&&( item.status==10) ">
									完成预约
								</view>
							</view>
						</view>
					</block>
				</scroll-view>
			</view>
			<sjtabbar :yyShow='true' :pagesPath='pagesPath'></sjtabbar>
		</view>
	</view>
</template>

<script>
	import {
		order_order,
		order_pay,
		confirm,
		cancel_reservation
	} from "@/api/comm.js"
	import {
		order_list,
		send_out,
		print,
		refund,
		receiving_orders,
		complete_appointment
	} from "@/api/merchant.js"
	import {
		toDate
	} from "@/utils/time.js"
	import {
		forEach
	} from "lodash";
	import wxApi from "@/utils/wxApi.js"
	import {
		cellphone
	} from "@/utils/type_height.js"
	export default {
		data() {
			return {
				current: 0,
				pagesPath: '/pages/merchant/sjreserved/sjreserved',
				newDate: +new Date(),
				barindex: 0, //自提/外卖
				time: [], //筛选的时间
				content_list: [],
				enter: false,
				page: 1,
				show: false,
				min: new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000,
				show_type: false, //选择类型
				classify: 0,
				keyword: '',
				System_height: cellphone(), //系统高度
				showcomm: true,
				order_no: '',
				orderstatus: 0,
				modalshow: false,
				list1: [{
					name: '全部',
				}, {
					name: '待支付'
				}, {
					name: '预约中',
				}, {
					name: '待退款'
				}, {
					name: '已取消',
				}, {
					name: '已完成',
				}],
				modalshowvalue: ''
			};
		},
		onLoad() {
			this.newDate = +(new Date() / 1000).toFixed(0)
			this.order_orderApi()
		},
		onShow() {
			this.min = new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000
			this.page = 1
			this.order_orderApi()
		},
		methods: {
			setlist() {
				let common = uni.getStorageSync('public')
				if (common.iszq == 1) {
					this.list1.push({
						name: '自提订单'
					})

				} else if (common.iswm == 1) {
					this.list1.push({
						name: '外卖订单'
					})
				} else if (common.isyy == 1) {
					this.list1.push({
						name: '预约订单'
					})
				}

			},

			tabclick(e) {
				if (e.index == 1) {
					this.orderstatus = 1
				} else if (e.index == 2) {
					this.orderstatus = 10
				} else if (e.index == 3) {
					this.orderstatus = 4
				} else if (e.index == 4) {
					this.orderstatus = 5
				} else if (e.index == 5) {
					this.orderstatus = 3
				} else {
					this.orderstatus = 0
				}
				this.page = 1
				this.current = e.index
				this.keyword = ''
				this.getlist()
			},
			// 取消预约
			async getcancelReservation(order_no, id) {
				let res = await cancel_reservation({
					order_no
				})
				if (res.code == 1) {
					this.content_list.forEach((item, index) => {
						if (item.id == id) {
							item.status = 4
						}
					})
					this.order_orderApi()
					this.$forceUpdate()
				}
			},

			getlist() {
				this.page = 1
				this.order_orderApi()
			},
			async order_orderApi() {
				let data = await order_list({
					limit: 10,
					search: this.keyword,
					status: this.orderstatus,
					page: 1,
					type: 4,
				})
				data.data.data.forEach(res => {
					res.createtime = toDate(res.createtime * 1000, 1)
				})
				this.content_list = []
				this.content_list.push(...data.data.data)
			},
			closepage() {
				this.enter = false
			},
			// 触底加载
			async order_orderApi_down() {
				let data = await order_list({
					limit: 10,
					search: this.keyword,
					page: this.page,
					status: this.orderstatus,
					type: 4,
				})
				data.data.data.forEach(res => {
					res.createtime = toDate(res.createtime * 1000, 1)
				})
				this.content_list.push(...data.data.data)
			},
			btnBClick(order_no, id) {
				this.order_no = order_no
				uni.$u.throttle(this.again, 1000)
			},

			gopage(id, avatar, username, tel) {
				uni.navigateTo({
					url: `/pages/merchant/orderDetail/orderDetail?id=${id}&avatar=${avatar}&username=${username}&tel=${tel}`
				})
			},
			async again(order_no, id) {
				let openid = uni.getStorageSync('openid') || ''
				let data = await order_pay({
					orderNo: this.order_no,
					openid
				})
				if (data.code == 1) {
					let orderInfo = data.data
					let that = this
					// wxparApi.wxparApi(data_seed.data, '/pages/order_form/order_form?idx=3')
					wx.requestPayment({
						timeStamp: orderInfo.timeStamp,
						nonceStr: orderInfo.nonceStr,
						package: orderInfo.package,
						signType: orderInfo.signType,
						paySign: orderInfo.paySign,
						success(res) {
							// that.content_list.forEach(item => {
							// 	if (item.id == id) {
							// 		item.status = 10
							// 	}
							// })
							// that.$forceUpdate()
							that.order_orderApi()
							uni.showToast({
								title: '支付成功',
								icon: 'none'
							})
						},
						fail(res) {
							uni.showToast({
								title: '支付失败',
								icon: 'none'
							})
						}
					})
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			async take(order_id) {
				let data = await confirm({
					order_id
				})
				if (data.code == 1) {
					this.order_orderApi()
				} else {
					uni.showToast({
						title: data.msg,
						icon: 'none'
					})
				}
			},
			async refundconfirm() {
				let data = await refund({
					order_no: this.order_no,
					apply: this.modalshowvalue
				})
				if (data.code == 1) {
					this.order_orderApi()
				} else {
					uni.showToast({
						title: data.msg,
						icon: 'none'
					})
				}
				this.modalshow = false
			},
			async userrefund(order_no) {
				this.order_no = order_no
				this.modalshow = true
			},
			confirm(e) {
				this.show = false
				this.time = []
				this.time.push(...e)
				this.order_orderApi()
			},
			selectClick(e) {
				this.classify = e.index
				this.page = 1
				this.order_orderApi()

			},
			//完成预约
			completeOrder(order_no) {
				complete_appointment({ order_no }).then(res => {
					if (res.code == 1) {
						this.getlist()
					}
				})
			},
			loadmore() {
				this.page++
				this.order_orderApi_down()
			}
		},
		onReachBottom() {
			this.page++
			this.order_orderApi_down()
		}
	}
</script>

<style lang="scss" scoped>
	page {
		padding: 12vh 0 200rpx;
	}

	.sjreserved {
		width: 100%;
		height: 100vh;
		flex-direction: column;
	}

	.lists {
		flex-direction: column;
		overflow: hidden;
		height: 0;

		.scroll {
			flex: 1;
			height: 0;
		}
	}

	.time {
		::v-deep .u-icon__icon {
			font-size: 18px !important;
		}
	}


	.navbar {
		// position: fixed;
		top: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		z-index: 999;
	}

	.fixed {
		// height: 10vh;
		text-align: center;
		// line-height: 13vh;
		font-size: 26rpx;
	}

	.u-tabs__wrapper__nav__item {
		// width:130rpx !important;
	}

	.hade {
		background-color: #fff;

		.title {
			font-size: 32rpx;
			font-weight: 600;
			color: #000000;
			text-indent: 1em;
			padding-bottom: 20rpx;
		}
	}

	.recordhei {
		height: 12vh;
	}

	.box_content {
		width: 702rpx;
		margin: 0 auto;
		// background-color: #9A9A9A;

		.hadea {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 30rpx 20rpx;

			.hade_left {
				display: flex;
				align-items: center;

				view:nth-child(1) {
					width: 29rpx;
					height: 32rpx;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #000000;
				}
			}

			.hade_right {
				display: flex;
				align-items: center;

				.hade_right_left {
					display: flex;
					align-items: center;
					margin-right: 30rpx;

					view:nth-child(1) {
						width: 29rpx;
						height: 32rpx;
						padding: 0 7rpx;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #000000;
					}
				}

				.hade_right_right {
					display: flex;
					align-items: center;

					view:nth-child(1) {
						width: 29rpx;
						height: 32rpx;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 400;
						color: #000000;
					}
				}
			}
		}

		.record {
			// min-height: 900rpx;
			height: auto !important;
			// height: 900rpx;
			background: #FFFFFF;
			padding: 30rpx 0;
			border-radius: 16rpx;

			.record_title {

				padding: 20rpx 30rpx;
				font-size: 32rpx;
				font-weight: 500;
				color: #000101;
				margin-bottom: 30rpx;

			}

			.record_content {
				width: 90%;
				margin: 0 auto;
				display: flex;
				justify-content: space-between;
				padding: 20rpx 0;
				border-bottom: 1rpx solid #EBEBEB;
				margin-bottom: 30rpx;

				.record_content_left {
					view:nth-child(1) {
						font-size: 24rpx;
						font-weight: 400;
						color: #000101;
					}

					view:nth-child(2) {
						font-size: 20rpx;
						font-weight: 400;
						color: #9A9A9A;
					}
				}

				.record_content_right {
					font-size: 32rpx;
					font-weight: 500;
					color: #313131;
				}
			}
		}
	}

	.store {
		width: 703rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 0 auto;

		.store_down {
			width: 95%;
			margin: 0 auto;
			padding: 30rpx 0;
			text-align: right;
			border-top: 1rpx solid #E1E1E1;
		}
	}

	.record {
		width: 703rpx;
		margin: 0 30rpx 30rpx 30rpx;
		background: #FFFFFF;
		border-radius: 24rpx;
		padding: 16rpx 0;

		.record_content {
			display: flex;
			justify-content: space-between;
			padding: 20rpx 15rpx;

			.record_content_left {
				.record_content_left_1 {
					font-size: 32rpx;
					font-weight: 400;
					color: #343434;
				}

				.record_content_left_2 {
					font-size: 24rpx;
					font-weight: 400;
					color: #666666;
					margin-top: 14rpx;
				}

				.record_content_left_3 {
					font-size: 24rpx;
					font-weight: 400;
					color: #666666;
					margin-top: 14rpx;
				}

				.record_content_left_4 {
					color: red;
				}
			}

			.record_content_right {
				height: 33rpx;
				display: flex;
				font-size: 24rpx;
				font-weight: 400;
				color: #383838;
			}
		}

		.record_pay {
			display: flex;
			flex-direction: row-reverse;
			padding-top: 20rpx;
			border-top: 1rpx dashed #EFEFEF;

			view {
				width: 155rpx;
				height: 55rpx;
				background: #E40030;
				border-radius: 8rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #F9FDFF;
				line-height: 55rpx;
				text-align: center;
				margin-right: 30rpx;
			}
		}

		.record_pay:empty {
			display: none;
		}
	}

	::v-deep .u-modal__content {
		padding-top: 0 !important;
	}

	.slot-content {
		padding-top: 20rpx;
	}

	.card_top {
		// height: 200rpx;
		background-color: #fff;
		padding: 0rpx 35rpx 10rpx;
		box-sizing: border-box;

		.search_card {
			margin-bottom: 10rpx;

			.search {
				width: 440rpx;
			}

			.search_btn {
				width: 209rpx;
				height: 70rpx;
				background: #E40030;
				border-radius: 10rpx;
				text-align: center;
				line-height: 70rpx;
				font-size: 30rpx;
				color: #FFFFFF;
			}
		}
	}
</style>