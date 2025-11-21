<template>
	<view>
		<u-sticky>
			<view class="hade">
				<view class="" :style="{width:'100%',height:wxinfo.top+'px',background:'#fff'}"></view>
				<!-- <view :style="{height:System_height+'rpx'}"> -->
				<!-- </view> -->
				<view class="title">
					订单列表
				</view>
				<view class="">
					<u-tabs lineColor='#333 ' :scrollable="false" :lineWidth='60' :lineHeight='5'
						:activeStyle="{color: '#333', fontWeight: 'bold',fontsize: '28rpx' }"
						:inactiveStyle="{ color: '#333', fontWeight: 'bold', fontsize: '28rpx'}" :list="tabList"
						itemStyle="height: 34px;width:38%;" :current='tabindex' @click="tabclick"></u-tabs>
				</view>
				<view class="" v-if="tabindex==1">
					<u-tabs lineColor='#333 ' :scrollable="true" :lineWidth='60' :lineHeight='5'
						:activeStyle="{color: '#333', fontWeight: 'bold',fontsize: '28rpx' }"
						:inactiveStyle="{ color: '#6A6A6A', fontWeight: 'bold', fontsize: '28rpx'}" :list="list1"
						:current='barindex' @click="click"></u-tabs>
				</view>
			</view>

		</u-sticky>
		<!-- v-if="pageShow" -->

		<view class="box" v-if="tabindex==1">
			<view class="box_content">
				<view class="hadea">
					<view class="hade_left">
					</view>
					<view class="hade_right">
						<view class="hade_right_left" @tap="show_type=true" v-if="barindex!==3">
							<view>
								<image src="@/static/Project_drawing 41.svg" mode=""></image>
							</view>
							<view>
								{{list[classify].name}}
							</view>
						</view>
						<view class="flexc">
							<view class="hade_right_right" @tap="open">
								<view>
									<image src="@/static/Project_drawing 37.png" mode=""></image>
								</view>
								<view>
									{{time[0] || '时间'}}
								</view>
							</view>
							<image @click="czTime" class="czimg" src="../../static/cz.png" mode="aspectFill"></image>
						</view>

					</view>
				</view>
			</view>

			<block>
				<view style="margin-top: 350rpx;" v-if="content_list.length<1">
					<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
						text='暂无订单'></u-empty>
				</view>
				<view class="record" v-for="(item,index) in content_list" :key="item">
					<view class="record_content"
						@tap="routerTo('/pages/order_all/order_details/order_details?id='+item.id)">
						<view class="record_content_left">
							<view class="record_content_left_1 flex ac">
								<text class="ordertypetag" v-if="item.type==1">自提</text>
								<text class="ordertypetag" v-if="item.type==2">外卖</text>
								<text class="ordertypetag" v-if="item.type==3">堂食</text>
								<text class="ordertypetag" v-if="item.type==4">预约</text>

								{{item.store}}
								<!--{{item.order_no || ''}} -->
							</view>
							<!-- <view class="record_content_left_2">
								{{item.createtime || ''}}
							</view> -->
						</view>
						<view class="record_content_right">
							<!-- 自提 -->
							<text v-if="item.status==1">待付款</text>
							<view class="" v-if="item.status==2">
								<text v-if="item.type==2">待收货</text>
								<text v-else>待自提</text>
							</view>
							<text v-if="item.status==3">已完成</text>
							<text v-if="item.status==4">待退款</text>
							<text v-if="item.status==5">已取消</text>
							<text v-if="item.status==6">备餐</text>
							<text v-if="item.status==7">待取单</text>
							<text v-if="item.status==8">待取单</text>
							<text v-if="item.status==9">已退款</text>
							<text v-if="item.type==4&&item.status==10">预约中</text>
							<u-icon name="arrow-right" color="#717171" size="20"></u-icon>
						</view>
					</view>

					<view class="refund" v-if="item.code&&item.type==3" style="position: relative;">
						<view class="codebackimgs  flex jc">
							<view class="codebackimgts">
								<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg"></image>
								<view class="w100h100 codebacktt flex jc ac">
									餐座号
								</view>
							</view>
						</view>
						<!-- 	<view class="refund_text">
						餐座号
					</view> -->
						<view class="refund_text2">
							<span style="padding:10rpx 14rpx;" v-for="(ite,inde) in  item.seat_info"
								:key="inde">{{ite.seat_title}} </span>
						</view>
					</view>

					<view class="refund" style="position: relative;" v-if="item.type==1&&item.code">
						<!-- flex jc -->
						<view class="codebackimgs  flex jc">
							<view class="codebackimgts">
								<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg"></image>
								<view class="w100h100 codebacktt flex jc ac">
									取餐号
								</view>
							</view>
						</view>
						<view class="refund_text">
							{{item.code || ''}}
						</view>
						<!-- <view class="refund_text2">
							取餐号
						</view> -->
					</view>


					<view class="shops" :style="{'max-height': item.heg}" style="overflow: hidden;">
						<view v-for="(itemall,indexall) in item.detail" :key="itemall">
							<expensedetail :item='itemall'></expensedetail>
						</view>
					</view>
					<view class="flexc-s setheg " v-if="item.detail.length>2">
						<view class="" @click="setheg(item.id)" v-if="item.heg=='320rpx'">
							展开(共{{item.detail.length}}件)
						</view>
						<view class="" @click="addheg(item.id)" v-else>
							收起(共{{item.detail.length}}件)
						</view>
						<view class="">
							<u-icon name="arrow-down" v-if="item.heg=='320rpx'" size="28rpx"></u-icon>
							<u-icon name="arrow-up" v-else size="28rpx"></u-icon>
						</view>
					</view>
					<view class="creatTimes flex jsb ac" v-if="item.detail.length">
						<view class="creatTime">
							{{item.createtime || ''}}
						</view>
						<view class="flex ac">
							<view class="ordernum">
								共{{item.detail.length}}件
							</view>
							<view class="orderprice">
								<text class="orderpriceicon">￥</text>
								<text class="orderpricenum">{{item.order_amount_total}}</text>
							</view>
						</view>

					</view>
					<view class="" v-if="barindex==3">
						<expensedetail2 :item='item'></expensedetail2>
					</view>

					<view class="record_pay" v-if="barindex!==3">
						<view @tap="cancelOrder(item.id)" v-if="item.status==1">
							取消订单
						</view>
						<view @tap="take(item.order_no)" v-if="item.status==2 || item.status==7 || item.status==8">
							确认收货
						</view>
						<view @tap="btnBClick(item.order_no,item.id)" v-if="item.status==1">
							立即支付
						</view>
						<template v-if="item.p_type==1">
							<view @tap="userrefund(item.order_no)"
								v-if="item.status==2||item.status==6||item.status==7||item.status==8">
								申请退款
							</view>
						</template>

					</view>
					<view class="record_pay" v-if="barindex==3">
						<view @tap="getcancelReservation(item.order_no,item.id)"
							v-if="item.type==4&&( item.status==10 || item.status==3) && (newDate<item.subscribe_time)">
							取消预约
						</view>
						<view @tap="btnBClick(item.order_no,item.id)" v-if="item.type==4&&item.status==1">
							立即支付
						</view>
					</view>
				</view>

			</block>
		</view>
		<!-- 当前订单 -->
		<view class="box_2" v-else>
			<view class="" v-for="(item,index) in currentList" :key="item.id">
				<!-- item.oneList.length>0 && -->
				<view class="" v-if=" nowaday">
					<block>
						<view class="store">
							<view class="record_content flexs flexc"
								@tap=" routerTo('/pages/order_all/order_details/order_details?id='+item.id)">
								<view class="record_content_left_1 flex jc ac">
									<text class="ordertypetag" v-if="item.order_type==1">自提</text>
									<text class="ordertypetag" v-if="item.order_type==2">外卖</text>
									<text class="ordertypetag" v-if="item.order_type==3">堂食</text>
									<text class="ordertypetag" v-if="item.order_type==4">预约</text>
									{{item.store}}
								</view>
								<view class="record_content_right">
									<!-- 自提 -->
									<text v-if="item.status==1">待付款</text>
									<view class="" v-if="item.status==2">
										<text v-if="item.order_type==2">待收货</text>
										<text v-else>待自提</text>
									</view>
									<text v-if="item.status==3">已完成</text>
									<text v-if="item.status==4">待退款</text>
									<text v-if="item.status==5">已取消</text>
									<text v-if="item.status==6">备餐</text>
									<text v-if="item.status==7">待取单</text>
									<text v-if="item.status==8">待取单</text>
									<text v-if="item.status==9">已退款</text>
									<text v-if="item.order_type==4&&item.status==10">预约中</text>
									<u-icon name="arrow-right" color="#717171" size="20"></u-icon>
								</view>
							</view>

							<!-- <view class="">
								<storelocation :addShow='true' :content_list='item'></storelocation>
							</view> -->

							<view class="refund" v-if="item.order_type==3 || item.order_type==4"
								style="position: relative;">
								<view class="codebackimgs  flex jc">
									<view class="codebackimgts">
										<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg">
										</image>
										<view class="w100h100 codebacktt flex jc ac">
											餐座号
										</view>
									</view>
								</view>
								<!-- 	<view class="refund_text">
								餐座号
							</view> -->
								<view class="refund_text2">
									<span style="padding:10rpx 14rpx;">{{item.seat_title }}</span>
								</view>
							</view>

							<view class="refund" style="position: relative;" v-if="item.order_type==1&&item.code">
								<!-- flex jc -->
								<view class="codebackimgs  flex jc">
									<view class="codebackimgts">
										<image class="codebackimg" :src="Httpimg+'qccode.png'" mode="" v-if="Httpimg">
										</image>
										<view class="w100h100 codebacktt flex jc ac">
											取餐号
										</view>
									</view>
								</view>
								<view class="refund_text">
									{{item.code || ''}}
								</view>
								<!-- <view class="refund_text2">
									取餐号
								</view> -->
							</view>


							<view v-for="(ite,inde) in item.oneList" :key="inde" class="content_detail"
								@tap="routerTo('/pages/order_all/goodsordersearch/goodsordersearch?id='+ite.goods_id+'&store_id='+ite.score_id+'&activate_data='+item.type)">
								<view class="content_left">
									<view class="content_left_img">
										<image :src="ite.image" mode="aspectFill"></image>
									</view>
									<view class="content_left_title">
										<view class="content_left_title_1">
											{{ite.title || ''}}
										</view>
										<view class="content_left_title_2">
											{{ite.item_name || ''}}
										</view>
										<view class="hue1 size12" v-if="item.title=='餐位费'">
											用餐人数：{{item.people_count || 1}}
										</view>
									</view>
								</view>
								<view class="content_right">
									<view class="content_right_price">
										¥{{ite.price || 0}}
									</view>
									<view class="content_right_count">
										x{{ite.count || 0}}
									</view>
								</view>
							</view>

							<view class="addTitle blod" v-if="item.twoList.length>0">
								加餐菜品
							</view>

							<view class="">
								<view v-for="(ite,inde) in item.twoList" :key="inde" class="content_detail">
									<view class="content_left">
										<view class="content_left_img">
											<image :src="ite.image" mode="aspectFill"></image>
										</view>
										<view class="content_left_title">
											<view class="content_left_title_1 me-text-beyond">
												{{ite.title || ''}}
											</view>
											<view class="content_left_title_2 me-text-beyond">
												{{ite.item_name || ''}}
											</view>
										</view>
									</view>
									<view class="content_right">
										<view class="content_right_price">
											¥{{ite.price || 0}}
										</view>
										<view class="content_right_count">
											x{{ite.count || 0}}
										</view>
									</view>
								</view>
							</view>
							<view class="tool">
								<view class="total-price">
									订单总额：￥{{item.order_amount_total}}
								</view>
								<view class="store_down" v-if="item.order_type==4">
									<view class="store_down_right" @tap="getcancelReservation(item.order_no,item.id)"
										v-if="item.order_type==4&&( item.status==10 || item.status==3) && (newDate<item.subscribe_time)">
										取消预约
									</view>
									<view class="store_down_right" @tap="btnBClick(item.order_no,item.id)"
										v-if="item.order_type==4&&item.status==1">
										立即支付
									</view>
								</view>
								<view class="store_down" v-else>
									<!-- 订单总额 -->
									<view class="" v-if="item.status==6 || item.status==1">
										<view class="store_down_right" v-if="item.p_type==2 && item.order_type==3"
											@click="addAdish(item)">
											加菜
										</view>
									</view>
									<!-- currentList.status==1 &&user.id==currentList.user_id -->
									<view class="store_down_left" @tap="cancelOrder(item.id,1)"
										v-if="item.status==1 && user.id==item.user_id  && item.order_type!=3">
										取消订单
									</view>
									<!--  -->
									<view class="store_down_right" style="margin-left: 20rpx;"
										v-if="item.status==1 && user.id==item.user_id"
										@tap="btnBClick(item.order_no,item.id)">
										立即支付
									</view>
								</view>
							</view>
						</view>
					</block>
				</view>
				<view v-else>

				</view>
			</view>
			<view class="" v-if="currentList.length==0">
				<emptyPage></emptyPage>
			</view>
		</view>

		<login :show="enter" @loadpage="unloadpage" @closepage='closepage'></login>
		<view class="time">
			<uni-calendar ref="calendar" :insert="false" @confirm="confirm" />
		</view>
		<u-action-sheet :actions="list" @select="selectClick" @close='show_type=false' :closeOnClickOverlay="true"
			:closeOnClickAction="true" :show="show_type"></u-action-sheet>
		<u-modal :show="modalshow" @confirm="refundconfirm" ref="uModal" :asyncClose="true" :closeOnClickOverlay="true"
			:showCancelButton='true' confirmColor="#E40030" @cancel='modalshow=false'>
			<view class="slot-content">
				<view class="refundt">
					退款原因
				</view>
				<view class="textareainp">
					<textarea class="textarea" v-model="modalshowvalue" placeholder="请输入退款原因"></textarea>
				</view>
				<!-- <u--text text=":退款原因" type="primary" :size="36" :lineHeight='60'></u--text> -->
			</view>
		</u-modal>
		<paypopup :navidx='2' :orderNo='orderNo' @orderFrom='orderFrom' :pageurl='pageurl' :popShow='popShow'
			@close='popShow=false'></paypopup>
		<u-modal :showCancelButton='true' :show="modalshow2" :title="'确定要取消预约吗'" @cancel='modalshow2=false'
			@confirm='modalConfirm'></u-modal>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		order_order,

		confirm,
		cancel_reservation,
		seat,
		order_detail,
		orderCancel,
		orderCurrent
	} from "@/api/comm.js"
	import {
		refund
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
				Httpimg: config.HttpImg,
				nowaday: false,
				user: uni.getStorageSync('user'),
				newDate: +new Date(),
				content_list: {},

				tabindex: 0,
				orderNo: '',
				pageurl: "",
				popShow: false,

				modalshow2: false,
				list1: [{
					name: '自提订单'
				}, {
					name: '外卖订单'
				}, {
					name: '堂食订单'
				}, {
					name: '预约订单'
				}],

				tabList: [{
						name: '今日订单'
					},
					{
						name: '历史订单'
					}
				],
				barindex: 0, //自提/外卖
				time: [], //筛选的时间

				enter: false,
				page: 1,
				show: false,
				min: new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000,
				show_type: false, //选择类型
				list: [{
						name: '全部展示',
						fontSize: '24',
						index: 0
					},
					{
						name: '待付款',
						fontSize: '24',
						index: 1
					},
					{
						name: '待收货',
						fontSize: '24',
						index: 2
					},
					{
						name: '已完成',
						fontSize: '24',
						index: 3
					},
					{
						name: '待退款',
						fontSize: '24',
						index: 4
					},
					{
						name: '已取消',
						fontSize: '24',
						index: 5
					},


				],
				poptext: "展开",
				classify: 0,
				System_height: cellphone(), //系统高度
				showcomm: true,
				order_no: '',
				modalshow: false,
				modalshowvalue: '',
				yyorder_no: "",
				yyId: '',
				pageShow: true,
				currentList: [], // 当前订单

				oneList: [],
				twoList: [],
				pageList: {},
				firstPay: uni.getStorageSync('firstPay'),
				orderDetail: ''
			};
		},
		onLoad(e) {
			this.newDate = +(new Date() / 1000).toFixed(0)
			if (e.idx) {
				this.barindex = Number(e.idx)
			}
			if (e.tabidx) {
				this.tabindex = Number(e.tabidx)
			}
			if (!uni.getStorageSync('userinfo')) {
				this.enter = true
				this.content_list = []
				return
			}
		},
		onShow() {
			this.min = new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000
			this.page = 1
			let scene = uni.getStorageSync('scene')

			if (!uni.getStorageSync('userinfo')) {
				this.enter = true
				this.content_list = []
				return
			} else {
				if (this.tabindex == 1) {
					this.order_orderApi()
				} else {

					if (scene) {
						this.getorderCurrent(scene)
					} else {
						this.getorderCurrent()
					}
				}
				this.enter = false
			}
		},
		computed: {
			wxinfo() {
				const info = uni.getMenuButtonBoundingClientRect()
				return info;
			}
		},
		methods: {
			open() {
				this.$refs.calendar.open();
			},
			czTime() {
				this.time = []
				this.order_orderApi()
			},
			addheg(id) {
				this.content_list.forEach(item => {
					if (item.id == id) {
						item.heg = '320rpx'
					}
				})
			},
			setheg(id) {
				this.content_list.forEach(item => {
					if (item.id == id) {
						item.heg = ''
					}
				})
			},
			addAdish(item) {
				uni.setStorageSync('addAdish', true)
				uni.setStorageSync('orderId', item.id)
				uni.setStorageSync('option', 3)
				uni.setStorageSync('scene', item.seat_id)
				uni.reLaunch({
					url: '/pages/order/order'
				})
			},
			async order_detailApi() {
				return
				let data = await order_detail({
					order_id: this.currentList.id
				})
				console.log(data, '当前订单数据结构')
				if (data.code == 1) {
					data.data.createtime = toDate(data.data.createtime * 1000, 1)
					data.data.subscribe_time = toDate(data.data.subscribe_time * 1000, 1)
					// this.content_list = data.data
					this.orderDetail = data.data

				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})
				}
			},
			async getorderCurrent(seat_id) {
				let res;
				if (seat_id) {
					res = await orderCurrent({
						seat_id
					})
				} else {
					res = await orderCurrent()
				}
				if (res.code == 1) {

					this.currentList = res.data
					if (this.currentList) {
						this.currentList.forEach(item => {
							let oneList = []
							let twoList = []
							if (item.detail && item.detail.length != 0) {
								item.detail.forEach(ite => {
									if (ite.numbers == 1) {
										oneList.push(ite)
									} else {
										twoList.push(ite)
									}
								})
								// oneList.push({
								// 	image: this.content_list.cwf_img,
								// 	title: '餐位费',
								// 	item_name: "",
								// 	price: item.cwf_fee,
								// 	count: item.people_count || 1,
								// 	numbers: 1
								// })
							}

							this.$set(item, 'oneList', oneList)
							this.$set(item, 'twoList', twoList)
							// item.oneList = oneList;
							// item.twoList = twoList;
						})
						// this.order_detailApi()   //数据详情，老逻辑
					}
					this.nowaday = true
				} else {
					this.nowaday = false
				}
			},

			async cancelOrder(id, e) {
				let that = this;
				uni.showModal({
					title: '提示',
					content: '是否取消订单？',
					success(resss) {
						if (resss.confirm) {
							orderCancel({
								order_id: id
							}).then(res => {
								if (res.code == 1) {
									if (e == 1) {
										that.nowaday = false
									}
									console.log(that.tabindex, '*********')
									if (that.tabindex == 0) {
										that.getorderCurrent()
									}
									if (that.tabindex == 1) {
										that.order_orderApi()
									}
								}
								that.$forceUpdate()
							})

						}
					}
				})

			},
			orderFrom() {
				this.order_orderApi()
			},
			setlist() {
				this.list1 = []
				let common = uni.getStorageSync('public')
				if (common.iszq == 1) {
					this.list1.push({
						name: '自提订单'
					})
				}
				if (common.iswm == 1) {
					this.list1.push({
						name: '外卖订单'
					})
				}
				if (common.ists == 1) {
					this.list1.push({
						name: '堂食订单'
					})
				}
				if (common.isyy == 1) {
					this.list1.push({
						name: '预约订单'
					})
				}
			},
			// 取消预约
			getcancelReservation(order_no, id) {
				this.yyorder_no = order_no
				this.yyId = id
				this.modalshow2 = true
			},
			async modalConfirm() {
				let res = await cancel_reservation({
					order_no: this.yyorder_no
				})
				let common = uni.getStorageSync('public')
				if (res.code == 1) {
					if (common.refund_switch == 0) {
						if (this.tabindex == 0) {
							this.currentList.forEach((item, index) => {
								if (item.id == this.yyId) {
									if (item.pay_type == 1) {
										item.status = 4
									} else if (item.pay_type == 2) {
										item.status = 5
									}
								}
							})
						} else {
							this.content_list.forEach((item, index) => {
								if (item.id == this.yyId) {
									item.status = 4
								}
							})
						}

						this.$forceUpdate()
					} else {
						if (this.tabindex == 0) {
							this.getorderCurrent()
						} else {
							this.order_orderApi()
						}
					}
				}
				this.modalshow2 = false
			},
			click(item) {
				if (item.name == '自提订单') {
					this.barindex = 0
				} else if (item.name == '外卖订单') {
					this.barindex = 1
				} else if (item.name == '堂食订单') {
					this.barindex = 2
				} else if (item.name == '预约订单') {
					this.barindex = 3
				} else if (item.name == '全部') {
					this.barindex = -1
				}
				this.pageurl = "/pages/order_form/order_form?idx=" + this.barindex
				this.page = 1
				this.order_orderApi()
			},
			tabclick(e) {
				let scene = uni.getStorageSync('scene')
				this.enter = false
				this.tabindex = e.index
				if (this.tabindex == 1) {
					this.order_orderApi()
				} else {
					if (scene) {
						this.getorderCurrent(scene)
					} else {
						this.getorderCurrent()
					}
				}

			},
			async order_orderApi(e) {
				let scene = uni.getStorageSync('scene')
				let type
				if (this.tabindex == 0) {
					type = 0
				} else {
					type = this.barindex + 1
				}
				let data = await order_order({
					limit: 10,
					page: 1,
					type,
					time: this.time[0],
					status: this.classify
				})
				if (data.code == 1) {
					if (this.tabindex == 1) {
						data.data.data.forEach(res => {
							res.createtime = toDate(res.createtime * 1000, 1)

						})
						this.content_list = []
						this.content_list.push(...data.data.data)
					}
				}
			},
			closepage() {
				this.enter = false
			},
			// 触底加载
			async order_orderApi_down() {
				let data = await order_order({
					limit: 10,
					page: this.page,
					type: this.barindex + 1,
					time: this.time[0],
					status: this.classify
				})
				this.pageList = data.data
				if (data.code == 1) {
					data.data.data.forEach(res => {
						res.createtime = toDate(res.createtime * 1000, 1)
					})
					this.content_list.push(...data.data.data)
				}

			},
			btnBClick(order_no, id) {
				this.order_no = order_no
				// 此处用法为在js中调用，需要写uni.$u.throttle()
				uni.$u.throttle(this.again(order_no, id), 1000)
			},
			async again(order_no, id) {
				this.orderNo = this.order_no
				this.pageurl = "/pages/order_form/order_form?idx=" + this.barindex
				this.popShow = true

				return

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
				this.time.push(e.fulldate)
				this.order_orderApi()
			},
			selectClick(e) {
				this.classify = e.index
				this.page = 1
				this.order_orderApi()
			}
		},
		onReachBottom() {
			if (this.page < this.pageList.current_page) {
				this.page++
				this.order_orderApi_down()
			}

		}
	}
</script>
<style>
	page {
		background: #FAFAFA !important;
	}
</style>
<style lang="scss" scoped>
	.time {
		::v-deep .u-icon__icon {
			font-size: 18px !important;
		}
	}

	.refund {
		width: 100%;
		background: #FAFAFA;
		border-radius: 18rpx;
		margin: auto;
		color: #333333;
		font-size: 32rpx;
		padding: 54rpx 20rpx 20rpx 20rpx;
		box-sizing: border-box;
		display: block;
		text-align: center;

		.refund_text {
			font-weight: bold;
			font-size: 54rpx;
			color: #333333;

		}

		.refund_text2 {
			font-size: 44rpx;
			font-weight: bold;
			color: #333;
			margin-top: 12rpx;
		}
	}

	.codebackimgs {
		width: 100%;
		height: 44rpx;
		position: absolute;
		left: 0;
		top: -5rpx;

		.codebackimgts {
			position: relative;
			width: 168rpx;
			height: 44rpx;
			// width: 100%;
		}

		.codebackimg {
			width: 168rpx;
			height: 44rpx;
			position: absolute;
			left: 0;
			top: 0;
			z-index: 1;
		}

		.codebacktt {
			width: 168rpx;
			height: 44rpx;
			font-size: 24rpx;
			color: #FFFFFF;
			position: absolute;
			left: 0;
			top: 0;
			z-index: 2;
		}
	}


	.creatTimes {
		padding: 0rpx 30rpx 24rpx 30rpx;
		box-sizing: border-box;

		.creatTime {
			font-size: 24rpx;
			color: #777777;
		}

		.ordernum {
			font-size: 24rpx;
			color: #AAAAAA;
		}

		.orderprice {
			font-size: 24rpx;
			color: #AAAAAA;
		}

		.orderpriceicon {
			font-size: 24rpx;
			color: #333333;
		}

		.orderpricenum {
			font-size: 24rpx;
			color: #333333;
			font-weight: bold;
		}
	}



	::v-deep .u-tabs__wrapper__nav__line {
		position: absolute !important;
		left: 30rpx !important;
	}

	.u-tabs__wrapper__nav__item {
		padding: 0 40rpx !important;
	}

	.hade {
		background-color: #fff;

		.title {
			font-size: 32rpx;
			font-weight: 600;
			color: #000000;
			text-indent: 1em;
			padding-bottom: 20rpx;
			text-align: center;
		}
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

				.czimg {
					width: 32rpx;
					height: 32rpx;
					margin-top: 2rpx;
				}

				.hade_right_right {
					display: flex;
					align-items: center;
					margin-right: 16rpx;

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
			min-height: 900rpx;
			height: auto !important;
			height: 900rpx;
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
				padding: 24rpx 0;
				border-bottom: 1rpx solid #EBEBEB;
				// margin-bottom: 30rpx;

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

		.record_content {
			margin-bottom: 16rpx;
		}

		.record_content_right {
			display: flex;
			font-size: 24rpx;
			font-weight: 400;
			color: #383838;

			text {
				padding: 6rpx 0 0;
			}
		}

		.record_content_left_1 {
			font-size: 28rpx;
			color: #333333;
			font-weight: bold;

		}

		.store_down {
			margin: 0 auto;
			// padding: 30rpx 0;
			text-align: right;
			// border-top: 1rpx solid #E1E1E1;
		}

		.tool {
			padding: 30rpx 0;
			border-top: 1rpx solid #E1E1E1;
			padding-bottom: 0rpx;

			.total-price {
				text-align: right;
				font-size: 24rpx;
				color: #333333;
				font-weight: bold;
			}
		}
	}

	.record {
		width: 703rpx;
		margin: 0 30rpx 30rpx 30rpx;
		background: #FFFFFF;
		border-radius: 24rpx;
		padding-bottom: 20rpx;

		.record_content {
			display: flex;
			justify-content: space-between;
			padding: 20rpx 30rpx;

			.record_content_left {
				.record_content_left_1 {
					font-size: 28rpx;
					font-weight: 400;
					color: #333;

				.ordertypetag {
					background: #E40030;
					border-radius: 4rpx;
					padding: 5rpx 8rpx;
					box-sizing: border-box;
					font-size: 20rpx;
					color: #FFFFFF;
					margin-right: 12rpx;
				}
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

		.refundt {
			font-size: 30rpx;
			font-weight: bold;
			text-align: center;
			margin-bottom: 24rpx;
		}

		.textareainp {
			border-radius: 12rpx;
			background: #F5f5f5;
			padding: 20rpx;
			box-sizing: border-box;
		}
	}

	.head {
		padding: 113rpx 34rpx 0;
		box-sizing: border-box;
		background-color: #fff;
		font-size: 30rpx;
		font-weight: 600;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		z-index: 10;
		height: 12vh;

		.head_title {
			margin-bottom: 40rpx;
		}
	}

	.box_2 {


		.store {
			width: 660rpx;
			background: #FFFFFF;
			border-radius: 18rpx;
			margin: 20rpx auto;
			padding: 32rpx 20rpx;

			.store_left {
				padding: 20rpx 32rpx 0;

				.store_left_1 {
					font-size: 32rpx;
					font-weight: 400;
					color: #393939;
				}

				.store_left_2 {
					font-size: 24rpx;
					font-weight: 400;
					color: #979797;
				}
			}

			.store_content {
				.store_content_text {
					font-size: 24rpx;
					margin-top: 16rpx;
				}

				image {
					width: 103rpx;
					height: 106rpx;
				}
			}
		}

		.addBtn {
			width: 100%;
			height: 31rpx;
			font-weight: 400;
			font-size: 24rpx;
			color: #999999;
			text-align: center;
		}

		.store_down {
			padding: 20rpx 0rpx 0;
			display: flex;
			align-items: center;
			justify-content: flex-end;
			box-sizing: border-box;

			.store_down_left {
				width: 203rpx;
				height: 79rpx;
				background: #fff;
				border-radius: 8rpx;
				color: #E40030;
				line-height: 79rpx;
				text-align: center;
				margin-right: 24rpx;
				border: 2rpx solid #E40030;
			}

			.store_down_right {
				width: 207rpx;
				height: 82rpx;
				background: #E40030;
				border-radius: 8rpx;
				color: #FFFFFF;
				line-height: 82rpx;
				text-align: center;
			}
		}



		::v-deep .u-modal__content {
			padding-top: 0 !important;
		}

		.slot-content {
			padding-top: 20rpx;
		}


		.addTitle {
			width: 653rpx;
			font-size: 32rpx;
			color: #232323;
			padding-bottom: 12rpx;
			box-sizing: border-box;
			border-bottom: 1rpx solid #F2F2F2;
		}

		.content_detail {
			display: flex;
			justify-content: space-between;
			padding: 25rpx 0;

			.content_left {
				display: flex;

				.content_left_img {
					width: 103rpx;
					height: 106rpx;
					margin-right: 16rpx;

					image {
						border-radius: 8rpx;
					}
				}

				.content_left_title {
					margin-left: 10rpx;

					.content_left_title_1 {
						width: 400rpx;
						font-size: 28rpx;
						font-weight: 400;
						color: #353535;
					}

					.content_left_title_2 {
						width: 400rpx;
						font-size: 24rpx;
						font-weight: 400;
						color: #989898;
						margin: 4rpx 0;
					}
				}
			}

			.content_right {
				text-align: right;

				.content_right_price {
					font-size: 32rpx;
					font-weight: 400;
					color: #353535;
				}

				.content_right_count {
					font-size: 24rpx;
					font-weight: 400;
					color: #989898;
					margin-top: 10rpx;
				}
			}
		}
	}

	.setheg {
		padding: 10rpx 0 16px;
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
					color: #777777;
					margin: 6rpx 0;
				}

				.three {
					font-size: 24rpx;
					color: #777777;
				}
			}
		}
	}
</style>