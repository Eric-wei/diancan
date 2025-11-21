<template>
	<view v-if="display">
		<block v-if="barindex==1 ">
			<countdown :str="content_list" @call='call' v-if="content_list.end_time">
			</countdown>
			<!--  -->
			<orderblock :code="content_list.code" :str="content_list.status"
				v-if="content_list.status!=1&&content_list.status!=3&&content_list.status!=5"></orderblock>

			<view class="gifts flex jc ac" @click.stop="gopages">
				<image class="gifticon" src="/static/images/gift.png" mode=""></image>
				<view class="giftt">
					本单可获得
					<text class="giftnum">{{content_list.default_points}}</text>
					个积分，去兑换好礼
				</view>
				<u-icon name="arrow-right" color="#333" size="28"></u-icon>
			</view>
			<view class="store">
				<storelocation :content_list='content_list'></storelocation>
				<u-line dashed></u-line>
				<expensedetail v-for="(item,index) in content_list.detail" :key="item" :item='item'></expensedetail>
				<u-line dashed></u-line>
				<view class="store_down ">
					<view class="">

					</view>
					<!-- <button open-type="contact" @contact="contact">
						<view class="store_down_left">
							联系客服
						</view>
					</button> -->
					<view class="flex jc ae">
						<text class="goodsnum">共 {{content_list.detail.length||0}} 件商品</text>
						<view class="store_down_right">
							<text>合计￥</text>
							<text>{{content_list.order_amount_total}}</text>
						</view>
					</view>

				</view>
			</view>

			<reserve :content_list='content_list'></reserve>
			<OrderInformation :content_list='content_list'></OrderInformation>
		</block>
		<block v-if="barindex==2">
			<countdown :str="content_list" @call='call' v-if="content_list.end_time">
			</countdown>
			<u-line dashed></u-line>
			<!-- <orderblock :code="content_list.code " :str="content_list.status"></orderblock> -->
			<view class="gifts flex jc ac" @click.stop="gopages">
				<image class="gifticon" src="/static/images/gift.png" mode=""></image>
				<view class="giftt">
					本单可获得
					<text class="giftnum">{{content_list.default_points}}</text>
					个积分，去兑换好礼
				</view>
				<u-icon name="arrow-right" color="#333" size="28"></u-icon>
			</view>
			<view class="store">
				<storelocation :content_list='content_list'></storelocation>

				<u-line dashed></u-line>
				<expensedetail v-for="(item,index) in content_list.detail" :key="item" :item='item'></expensedetail>
				<u-line dashed></u-line>
				<view class="store_down">
					<view class="">

					</view>

					<view class="flex jc ae">
						<text class="goodsnum">共 {{content_list.detail.length||0}} 件商品</text>
						<view class="store_down_right">
							<text>合计￥</text>
							<text>{{content_list.order_amount_total}}</text>
						</view>
					</view>
				</view>
			</view>
			<distribution :content_list='content_list'></distribution>
			<OrderInformation :content_list='content_list' @update='order_detailApi'></OrderInformation>
		</block>

		<block v-if="barindex==3">
			<orderblock :code="content_list.code" :isSeat='true' :seat='content_list.seat_info'
				:str="content_list.status"></orderblock>
			<view class="gifts flex jc ac" @click.stop="gopages">
				<image class="gifticon" src="/static/images/gift.png" mode=""></image>
				<view class="giftt">
					本单可获得
					<text class="giftnum">{{content_list.default_points}}</text>
					个积分，去兑换好礼
				</view>
				<u-icon name="arrow-right" color="#333" size="28"></u-icon>
			</view>

			<view class="store">
				<view class="store_left">
					<view class="store_left_1 flex ac">
						<text class="ordertypetag" v-if="content_list.order_type==3">堂食</text>

						{{content_list.store}}
					</view>
					<view class="store_left_2">
						{{content_list.createtime}}
					</view>
				</view>

				<finishOrder :oneList='oneList' :twoList='twoList'></finishOrder>
				<view class="store_down">
					<view class="">

					</view>
					<view class="flex jc ae">
						<text class="goodsnum">共 {{content_list.detail.length||0}} 件商品</text>
						<view class="store_down_right">
							<text>合计￥</text>
							<text>{{content_list.order_amount_total}}</text>
						</view>
					</view>
				</view>
			</view>
			<OrderInformation :content_list='content_list'></OrderInformation>
		</block>


		<block v-if="barindex==4">
			<!-- <view class="gifts flex jc ac">
				<image class="gifticon" src="/static/images/gift.png" mode=""></image>
				<view class="giftt">
					本单可获得
					<text class="giftnum">{{content_list.default_points}}</text>
					个积分，去兑换好礼
				</view>
				<u-icon name="arrow-right" color="#333" size="28"></u-icon>
			</view> -->
			<view class="store">
				<view class="store_left">
					<view class="store_left_1 flex ac">
						<text class="ordertypetag" v-if="content_list.order_type==4">预约</text>
						{{content_list.store}}
					</view>
					<view class="store_left_2">
						{{content_list.createtime}}
					</view>
				</view>
				<view class="store_content flex" v-for="(item,index) in content_list.seat_info" :key="index">
					<image :src="item.seat_img" mode=""></image>
					<view class="store_content_text">
						<view class="">
							{{item.seat_title}}
						</view>
						<view class="" style="margin-top: 14rpx;">
							{{content_list.subscribe_time}}
						</view>
					</view>
				</view>

				<view class="store_down">
					<view class="">

					</view>
					<view class="flex jc ae">
						<!-- <text class="goodsnum">共 {{content_list.detail.length||0}} 件商品</text> -->
						<view class="store_down_right">
							<text>预约费￥</text>
							<text>{{content_list.order_amount_total}}</text>
						</view>
					</view>
				</view>
			</view>
			<reserve2 :content_list='content_list'></reserve2>
			<OrderInformation :content_list='content_list'></OrderInformation>
		</block>
		<view class="refund" @tap="userrefund(content_list.order_no)"
			v-if="(content_list.status==2||content_list.status==6||content_list.status==8) && content_list.pay_type!=0">
			申请退款
		</view>
		<view class="refund" @tap="modalshow2=true"
			v-if="barindex==4 &&(content_list.status==3 || content_list.status==10) && (newDate<item.subscribe_time)">
			取消预约
		</view>
		<view class="refund" @tap="getpay" v-if="content_list.status==1 && merchat">
			立即支付
		</view>
		<height :hg="20"></height>
		<u-modal :show="modalshow" @confirm="refundconfirm" ref="uModal" :asyncClose="true" :closeOnClickOverlay="true"
			:showCancelButton='true' @cancel='modalshow=false'>
			<view class="slot-content">
				<u--text text="退款原因:" type="primary" :size="36" :lineHeight='60'></u--text>
				<textarea class="textarea" v-model="modalshowvalue" placeholder="请输入退款原因"></textarea>
			</view>
		</u-modal>
		<paypopup :navidx='1' :orderNo='orderNo' :pageurl='pageurl' :popShow='popShow' @close='popShow=false'>
		</paypopup>

		<u-modal :showCancelButton='true' :show="modalshow2" :title="'确定要取消预约吗'" @cancel='modalshow2=false'
			@confirm='getcancelReservation'></u-modal>
	</view>
</template>

<script>
	import {
		order_detail,
		cancel_reservation
	} from "@/api/comm.js"
	import {
		toDate
	} from "@/utils/time.js"
	import {
		refund
	} from "@/api/merchant.js"
	export default {
		data() {
			return {
				newDate: +new Date(),
				modalshow2: false,
				orderNo: "",
				pageurl: '',
				popShow: false,

				barindex: 2,
				order_id: '',

				content_list: {}, //详情内容
				display: false,
				order_no: '',
				modalshow: false,
				modalshowvalue: '',
				merchat: true,
				oneList: [],
				twoList: []
			};
		},
		onLoad(obj) {
			this.newDate = +(new Date() / 1000).toFixed(0)
			this.order_id = obj.id
			if (obj.merchat == 'merchat') {
				this.merchat = false
			}
			this.order_detailApi()
		},
		methods: {
			gopages() {
				uni.navigateTo({
					url: '/pages/me_all/integral/integral'
				})
			},
			getpay() {
				this.orderNo = this.content_list.order_no
				this.pageurl = "/pages/order_form/order_form?idx=3"
				this.popShow = true
			},
			// 取消预约
			async getcancelReservation(order_no, id) {
				let res = await cancel_reservation({
					order_no: this.content_list.order_no
				})
				if (res.code == 1) {
					uni.navigateBack()
				}
			},
			async order_detailApi() {
				let data = await order_detail({
					order_id: this.order_id
				})
				if (data.code == 1) {
					this.barindex = data.data.order_type
					data.data.createtime = toDate(data.data.createtime * 1000, 1)
					data.data.subscribe_time = toDate(data.data.subscribe_time * 1000, 1)
					this.content_list = data.data
					this.display = true
					if (this.barindex == 3) {
						this.oneList = []
						this.twoList = []
						this.content_list.detail.forEach(item => {
							if (item.numbers == 1) {
								this.oneList.push(item)
							} else {
								this.twoList.push(item)
							}
						})
						this.oneList.push({
							image: this.content_list.cwf_img,
							title: '餐位费',
							item_name: "",
							price: this.content_list.cwf_fee,
							count: this.content_list.people_count || 1,
							numbers: 1
						})
					}
				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})
				}
			},
			contact(e) {},
			call() {
				this.order_detailApi()
			},
			async refundconfirm() {
				let data = await refund({
					order_no: this.order_no,
					apply: this.modalshowvalue
				})
				if (data.code == 1) {
					this.order_detailApi()
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
		}
	}
</script>
<style>
	page {
		background: #FAFAFA !important;
	}
</style>
<style lang="scss">
	.goodsnum {
		font-size: 24rpx;
		color: #777777;
		margin-right: 20rpx;
		line-height: 1;
	}

	.gifts {
		width: 702rpx;
		margin: auto;
		background: #fff;
		border-radius: 20rpx;
		padding: 24rpx 0;
		box-sizing: border-box;

		.gifticon {
			width: 48rpx;
			height: 48rpx;
			margin-right: 20rpx;
		}

		.giftt {
			font-size: 24rpx;
			color: #333333;
			margin-right: 20rpx;
		}

		.giftnum {
			font-size: 30rpx;
			color: #FFC300;
			margin-left: 5rpx;
			margin-right: 5rpx;
		}
	}

	.store {
		width: 660rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 20rpx auto;
		padding: 0 20rpx;
		padding-bottom: 30rpx;

		.store_left {
			padding: 20rpx 0rpx 0;

			.store_left_1 {
				font-size: 32rpx;
				font-weight: 400;
				color: #393939;
				// line-height: ;
			}

			.store_left_2 {
				font-size: 24rpx;
				font-weight: 400;
				color: #979797;
			}
		}

		.store_content {
			margin-top: 16rpx;

			.store_content_text {
				font-size: 24rpx;
				margin-top: 16rpx;
			}

			image {
				border: 1rpx solid #eee;
				border-radius: 10rpx;
				width: 103rpx;
				height: 106rpx;
				margin-right: 20rpx;
			}
		}
	}

	.store_down {
		padding: 0 20rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-top: 24rpx;

		.store_down_left {
			width: 120rpx;
			height: 45rpx;
			border-radius: 7rpx;
			border: 1rpx solid #00CCFF;
			font-size: 22rpx;
			font-weight: 400;
			color: #00C8FF;
			line-height: 45rpx;
			text-align: center;
		}

		.store_down_right {
			line-height: 1;

			text:nth-child(1) {
				font-size: 24rpx;
				color: #333333;
			}

			text:nth-child(2) {
				font-size: 34rpx;
				font-weight: bold;
				color: #333;
			}
		}
	}

	.refund {
		width: 702rpx;
		background: #FFFFFF;
		border-radius: 18rpx;
		margin: 0 auto;
		color: #E40030;
		font-size: 32rpx;
		padding: 15px 0;
		display: block;
		text-align: center;
	}

	::v-deep .u-modal__content {
		padding-top: 0 !important;
	}

	.slot-content {
		padding-top: 20rpx;
	}
</style>