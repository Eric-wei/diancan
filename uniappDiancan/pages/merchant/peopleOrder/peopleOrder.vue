<template>
	<view v-show="display">
		<height :hg='20'></height>
		<view class="content">
			<view class="option">
				<view class="option_box">
					<view class="option_box_1">
						<view class="option_box_1_left">
							<view class="option_box_1_left_1">
								<text class="ordertypetag" v-if="select==3">堂食</text>
								<text>{{store_default_list.alias_title}}</text>
								<u-icon name="arrow-right" color="#232323" size="28"></u-icon>
							</view>
							<view class="option_box_1_left_2">
								<u-icon name="map" color="#3B3B3B" size="28"></u-icon>
								<text>{{store_default_list.address}}</text>
							</view>
						</view>
						<view class="option_box_1_right"
							@tap="getopenLocation(store_default_list.lat,store_default_list.lon)">
							<view class="option_box_1_right_1">
								<text> 距离您{{mkm(store_default_list.distance)}}km</text>
								<u-icon name="arrow-right" color="#979797" size="20"></u-icon>
							</view>
							<view class="option_box_1_right_2">
								<!-- 	<view class="option_box_1_right_2_location">
									<u-icon name="map" color="#3B3B3B" size="28"></u-icon>
								</view> -->
							</view>
						</view>
						<view class="option_box_1_back">
							<image src="../../../static/Project_drawing 5.png" mode="aspectFill"></image>
						</view>
					</view>

				</view>
				<view class="option_invite" v-if="select_count==2">
					<view class="option_invite_top"
						@tap="routergo('/pages/order_all/shipping_address/shipping_address?goback='+0)">
						<view class="option_invite_top_left">
							{{address_data.address||'请选择收货地址'}}
						</view>
						<view class="option_invite_top_right">
							<u-icon name="arrow-right" color="#444444" size="32rpx"></u-icon>
						</view>
					</view>

					<view class="option_invite_down" @tap="show_time=true">
						<view class="option_invite_down_left">
							送达时间
						</view>
						<view class="option_invite_down_right">
							<text>{{columns_value||"立即就餐"}}</text>
							<u-icon name="arrow-right" color="#949494" size="24rpx"></u-icon>
						</view>
					</view>

				</view>
			</view>


			<view class="order_details">

				<expensedetail v-for="(item,index) in order_list.data" :item="item" :key="item"></expensedetail>
				<view class="order_details_coupon" @tap="coupon_list.length?coupon_show=true:'' ">
					<view class="order_details_coupon_left">
						<!-- <view class="order_details_coupon_left_img">
							<image src="../../../static/Project_drawing 12.svg" mode=""></image>
						</view> -->
						<text>优惠券</text>
					</view>
					<view class="order_details_coupon_right">
						<text class="order_details_coupon_rightobj"
							v-if="coupom_id.used_amount">-￥{{coupom_id.used_amount}}</text>
						<text v-else>可用优惠劵 {{coupon_list.length}} 张</text>
						<u-icon name="arrow-right" color="#AEAEAE" size="24"></u-icon>
					</view>
				</view>
				<view class="order_details_count">
					<text class="order_details_countnum" v-if="order_list.data">共{{order_list.data.length||0}}件商品</text>
					<text style="padding-right: 10rpx;">合计¥</text>
					<text><text style="font-size: 34rpx;font-weight: bold;"> {{order_list.total}}</text></text>
				</view>
			</view>
			<view class="order_details_guarantee" v-show="select_count==2&&store_default_list.content">
				<view class="order_details_guarantee_top">
					<view class="order_details_guarantee_top_left">
						<view class="order_details_guarantee_top_left_img">
							<image src="../../../static/Project_drawing 20.svg" mode=""></image>
						</view>
						<view class="">
							外卖超时赔付
						</view>
					</view>
					<view class="order_details_guarantee_top_right">
						商家赠送
					</view>
				</view>
				<view class="order_details_guarantee_down" v-html="store_default_list.content">
				</view>
			</view>
			<view class="order_details_remark">
				<view class="order_details_remark_box" @tap="show_remark=true">
					<view class="order_details_remark_box_left">
						备注
					</view>
					<view class="order_details_remark_box_right">
						<text>{{remark ||"口味、偏好等要求"}}</text>
						<u-icon name="arrow-right" color="#AEAEAE" size="24"></u-icon>
					</view>
				</view>
				<view class="order_details_remark_box" v-if="select_count!=1&&select!=3" @tap="select_tableware=true">
					<view class="order_details_remark_box_left">
						餐具数量
					</view>
					<view class="order_details_remark_box_right">
						<text>{{tableware_value||"无需餐具"}}</text>
						<u-icon name="arrow-right" color="#AEAEAE" size="24"></u-icon>
					</view>
				</view>
			</view>
			<height :hg='120'></height>
		</view>

		<view class="tab_down">
			<view class="tab_down_box flex jsb ac">
				<view class="tab_down_box_left">
					<!-- <text>共1件</text> -->
					<text>合计:¥</text>
					<text class="tab_down_box_leftbig">{{order_list.after_total}}</text>
				</view>
				<view class=" flex jc ac">
					<view class="tab_down_box_right flex jc ac" @tap="zfShow?setpType():''">
						稍后支付
					</view>
					<view class="tab_down_box_right1  flex jc ac" @tap="zfShow?btnBClick():''">
						立即支付
					</view>
				</view>

			</view>
		</view>
		<!-- 派送时间弹出层 -->
		<u-picker :show="show_time" :closeOnClickOverlay='true' :columns="columns" @cancel="show_time=false"
			@close="show_time=false" @confirm="confirm" @change="changeHandler"></u-picker>
		<!-- 备注弹出层 -->
		<u-popup :show="show_remark" mode="bottom" :round='20' :closeable='true' @close="show_remark=false">
			<view class="up_remark">
				<view class="up_remark_hadr">备注</view>
				<view class="up_remark_content">
					<textarea class="up_remark_content_text" :maxlength="120" placeholder="请输入口味、偏好等要求"
						v-model="remark2"></textarea>
					<text class="up_remark_content_count">{{remark2.length}}/120</text>
				</view>
				<view class="up_remark_bottom" @click.stop="confirm_remarks()">
					确定
				</view>
			</view>
		</u-popup>
		<!-- 选择餐具份数 -->
		<u-picker :show="select_tableware" :closeOnClickOverlay='true' :columns="tableware"
			@cancel="select_tableware=false" @close="select_tableware=false" @confirm="select_tableware_confirm"
			@change="select_tableware_changeHandler"></u-picker>
		<!-- 确认支付 -->
		<u-popup :show="affirm" mode="center" :round='20' :closeable='true' :safeAreaInsetBottom="false"
			@close="affirm=false">
			<view class="affirm_box">
				<view class="affirm_box_1">
					*请确认取餐门店
				</view>

				<view class="affirm_box_2">
					<view>
						{{store_default_list.alias_title}}
					</view>
					<view>
						{{store_default_list.address}}
					</view>
					<view>
						<view class="img">
							<image src="../../../static/Project_drawing 7.png" mode=""></image>
						</view>
						<text>距您{{mkm(store_default_list.distance)}}km</text>
					</view>
				</view>

				<view class="affirm_box_3">
					提醒:注意门店与您的距离哦
				</view>

				<view class="affirm_box_4">
					<view @tap="routerTo('/pages/order_all/selectstore/selectstore')">
						更换门店
					</view>
					<view @tap="dbClick">
						<text v-if="dbshow">确认</text>
						<text v-else>支付中</text>
					</view>
				</view>
			</view>
		</u-popup>
		<!-- 优惠劵 -->
		<u-popup :show="coupon_show" mode="bottom" :round='20' :closeable='true' @close="coupon_show=false">
			<height :hg='60'></height>
			<view>
				<scroll-view :scroll-y="coupon_list.length>2"
					style="width: 100%; height: 600rpx; background-color: #F8F8F8;">
					<view class="content" v-if="Httpimg">
						<view class="content_box" :style="{backgroundImage: `url(${Httpimg}couponBack.png)`,}"
							v-for="(item,index) in coupon_list" :key="item">
							<view>
								<view class="content_box_1 flex jsb ac">
									<view class="content_box_1_right">
										<view>
											{{item.title}}元优惠券
										</view>
										<view>
											{{item.draw_range}}
										</view>
									</view>
									<view class="content_box_1_left">
										<view>
											{{item.used_amount}}<text style="font-size: 24rpx;">元</text>
										</view>
										<view>
											满{{item.with_amount}}元可用
										</view>
									</view>

								</view>
								<view class="content_box_2">
									<view class="content_box_2_left flex ac" @tap="rule(index)">
										<view>使用规则</view>
										<view class="">
											<image src="../../../static/Project_drawing 18.png" mode=""></image>
										</view>
										<view class="rule" v-show="item.show">
											{{item.remarks}}
										</view>
									</view>
									<view class="checkboxs flex jc ac" @click="choosecoupon(item)">
										<image class="checkboxselected" :src="'/static/images/couponc.png'" mode=""
											v-if="temporary_coupom_ids.includes(item.id)">
										</image>
										<view class="checkboxnotselected" v-else>

										</view>

									</view>
									<!-- <view class="content_box_2_right" @tap="selective_offer(item)">
									使用
								</view> -->
								</view>


							</view>
						</view>
					</view>
				</scroll-view>
				<view class="">
					<view class="up_remark_bottom" @click.stop="confirmCoupon()">
						确定
					</view>
				</view>

				<height :hg="20"></height>
			</view>
		</u-popup>
		<paypopup @confirm='payConfirm' :sign='true' :order='order' :orderNo='orderNo' :pageurl='pageurl'
			:popShow='popShow' @close='popShow=false'>
		</paypopup>

	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		coupon_usable,
		order_carList,
		store_default,
		order_tableware,
		order_create,
		order_pay,
	} from "@/api/comm.js"
	import wxparApi from "@/utils/wxApi.js"
	import {
		forEach
	} from 'lodash';
	import
	loginApi
	from '@/utils/wxApi.js'
	import {
		userInfo
	} from "@/api/public.js"
	import {
		user_binding
	} from "@/api/user.js"
	export default {
		data() {
			return {
				Httpimg: config.HttpImg,
				temporary_coupom_id: {}, // 临时 已选优惠劵内容
				temporary_coupom_ids: [], // 临时 已选优惠劵内容
				remark2: '',
				// select: uni.getStorageSync('option'),
				orderNo: "",
				pageurl: "",
				popShow: false,
				scene: uni.getStorageSync('scene') || null,
				tsShow: false,
				dbshow: true,
				select_count: 2, //选择取餐方式
				phone: '', //电话号
				show_time: false, //配送时间弹出框
				columns: [
					['立即就餐', '11:30', '12:00', '12:50']
				],
				columns_value: '立即就餐',

				show_remark: false, //备注信息弹出框
				remark: '', //备注信息
				select_tableware: false, // 餐具选择
				tableware: [
					['无需餐具', '一份餐具', '两份餐具', '三份餐具']
				],
				tableware_value: '无需餐具',
				affirm: false, //确认订单
				coupon_show: false, //优惠劵弹出层
				order_list: {}, //购物车
				store_default_list: {}, //默认门店详情
				address_data: {}, //选择的地址
				coupon_list: [], //优惠劵列表
				coupom_id: {}, //已选优惠劵内容
				display: false,
				zfShow: true,
				type: 1,
				order: false,
				pType: 1,
				ctype: 1
			};
		},
		computed: {
			shopid() {
				return uni.getStorageSync('shop')
			},
			select() {
				return uni.getStorageSync('option')
			},
		},
		onLoad(obj) {
			if (obj.ctype) {
				this.ctype = obj.ctype;
			}
			this.getcode()
			this.select_count = uni.getStorageSync('option')
			this.order_carListApi()
			this.order_tablewareApi()
			if (obj.query) {
				if (obj.query.item) {
					this.address_data = JSON.parse(obj.query.item)
				}
			}
		},
		onShow() {
			let pages = getCurrentPages();
			let currPage = pages[pages.length - 1]; // 获取当前页面
			if (currPage.$item) {
				this.address_data = JSON.parse(currPage.$item)
			}
		},
		methods: {

			confirmCoupon() {
				this.coupom_id = this.temporary_coupom_id;
				this.coupon_show = false;
				this.order_carListApi()
			},
			choosecoupon(item) {
				if (this.temporary_coupom_ids.includes(item.id)) {
					if (this.temporary_coupom_ids[0] == item.id) {
						this.temporary_coupom_id = {};
						this.temporary_coupom_ids = [];
					} else {
						this.temporary_coupom_id = item;
						this.temporary_coupom_ids = [item.id];
					}
				} else {
					this.temporary_coupom_id = item;
					this.temporary_coupom_ids = [item.id];
				}

			},
			async getcode() {
				let user = await userInfo()
				if (user.data.openid == '') {
					let code = await loginApi.loginApi()
					await user_binding({
						code: code.code
					})
				}
			},
			async setpType() {
				let peopleCount = uni.getStorageSync('peopleCount') || 1
				let res = await order_create({
					orderType: 3,
					storeId: this.store_default_list.id,
					subscribeTime: this.columns_value,
					subscribeTel: this.phone,
					serviceTime: this.columns_value,
					addressId: this.address_data.id,
					remark: this.remark,
					couponId: this.coupom_id.id,
					tablewareCount: this.tableware_value || '无需餐具',
					type: this.type,
					seatId: this.scene ? [this.scene] : '',
					pType: 2,
					peopleCount,
					c_type: this.ctype || 1
				})
				if (res.code == 1) {
					uni.reLaunch({
						url: `/pages/order_form/order_form?idx=2&pageShow=2`

					})
				}
			},
			payConfirm() {
				if (this.select == 1) {
					this.pick_up_store()
				} else {
					this.TakeoutOrder()
				}
			},
			routergo(url) {
				uni.navigateTo({
					url: url
				})
			},
			rule(index) {
				if (this.coupon_list[index].show) {
					this.coupon_list[index].show = 0
				} else {
					this.coupon_list[index].show = 1
				}
			},
			confirm(e) {
				this.columns_value = e.value[0]
				this.show_time = false
			},

			changeHandler(e) {
				this.columns_value = e.value[0]
			},
			confirm_remarks() {
				this.remark = this.remark2
				this.show_remark = false
			},

			// 餐具选择
			select_tableware_confirm(e) {
				this.select_tableware = false
				this.tableware_value = e.value[0]
			},
			select_tableware_changeHandler(e) {
				this.tableware_value = e.value[0]
			},

			btnBClick() {
				uni.$u.throttle(this.submit, 1000)
				// 此处用法为在js中调用，需要写uni.$u.throttle()
			},
			async binding(index, e) {

			},
			async TakeoutOrder() {
				let peopleCount = uni.getStorageSync('peopleCount') || 1
				//外卖创建订单
				let data = await order_create({
					orderType: uni.getStorageSync('option'),
					storeId: this.store_default_list.id,
					subscribeTime: this.columns_value,
					subscribeTel: this.phone,
					serviceTime: this.columns_value,
					addressId: this.address_data.id,
					remark: this.remark,
					couponId: this.coupom_id.id,
					tablewareCount: this.tableware_value || '无需餐具',
					type: this.type,
					seatId: this.scene ? [this.scene] : '',
					Type: this.pType,
					peopleCount,
					c_type: this.ctype || 1
				})
				if (data.code == 1) {
					// this.zfShow = false
					let openid = uni.getStorageSync('openid') || ''
					let data_seed = await order_pay({
						orderNo: data.data.orderNo,
						openid
					})
					if (data_seed.code == 1) {
						if (this.scene) {
							this.pageurl = '/pages/order_form/order_form?idx=2'
						} else {
							this.pageurl = '/pages/order_form/order_form?idx=1'
						}
						this.affirm = false
						this.orderNo = data.data.orderNo
						this.popShow = true
						this.dbshow = true
						this.order = true
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
			async submit() {
				if (uni.getStorageSync('option') == 1) {
					var reg = /^1[3-9]\d{9}$/;
					let ttt = reg.test(this.phone);
					if (ttt) {} else {
						uni.showToast({
							title: '请正确输入手机号',
							icon: 'none'
						})
						return
					}
					if (this.phone.length < 11) {
						uni.showToast({
							title: '请正确输入手机号',
							icon: 'none'
						})
						return
					}
					this.affirm = true
				} else if (uni.getStorageSync('option') == 2) {
					let user = await userInfo()
					if (user.data.openid == '') {
						let code = await loginApi.loginApi()
						await user_binding({
							code: code.code
						})
					}
					if (this.address_data.address) {
						this.popShow = true
					} else {
						uni.$showMsg('请选择收货地址')
					}

				} else {
					this.popShow = true
				}
			},


			dbClick() {
				// 此处用法为在js中调用，需要写uni.$u.throttle()
				this.setpopShow()
				this.dbshow = false
			},
			setpopShow() {
				this.popShow = true
				this.zfShow = false
				this.affirm = false
			},
			//自取创建订单
			async pick_up_store() {
				let user = await userInfo()
				if (user.data.openid == '') {
					let code = await loginApi.loginApi()
					await user_binding({
						code: code.code
					})
				}
				let peopleCount = uni.getStorageSync('peopleCount') || 1
				let data = await order_create({
					orderType: uni.getStorageSync('option'),
					storeId: this.store_default_list.id,
					subscribeTime: this.columns_value,
					subscribeTel: this.phone,
					serviceTime: this.columns_value,
					addressId: this.address_data.id,
					remark: this.remark,
					couponId: this.coupom_id.id,
					tablewareCount: this.tableware_value,
					peopleCount,
					c_type: this.ctype || 1
				})
				if (data.code == 1) {
					this.orderNo = data.data.orderNo
					this.pageurl = '/pages/order_form/order_form?idx=0'
					this.dbshow = true
					this.order = true
				} else {
					uni.showToast({
						title: data.msg,
						icon: 'none'
					})
				}
			},
			async order_carListApi() {
				let data;
				let peopleCount = uni.getStorageSync('peopleCount') || 0
				// let iscwf = uni.getStorageSync('public').iscwf
				let iscwf = uni.getStorageSync('currentShop').iscwf
				if (iscwf == 1) {
					data = await order_carList({
						coupon_id: this.coupom_id.id,
						peopleCount,
						store_id: this.shopid,
						c_type: this.ctype
					})
				} else {
					data = await order_carList({
						coupon_id: this.coupom_id.id,
						store_id: this.shopid,
						c_type: this.ctype
					})
				}

				if (data.code == 1) {
					this.order_list = data.data
					this.store_defaultApi()
					if (data.data.total > 0) {
						this.coupon_usableApi()
					}
				}

			},
			async store_defaultApi() {
				let data = await store_default({
					id: this.order_list?.data[0]?.store_id
				})
				this.columns[0] = []
				this.columns[0].push(...data.data.time)
				this.store_default_list = data.data
				this.display = true
			},
			async coupon_usableApi() {
				let data = await coupon_usable({
					price: this.order_list.total,
				})
				this.coupon_list = []
				this.coupon_list.push(...data?.data)
			},
			async order_tablewareApi() {
				let data = await order_tableware()
				this.tableware[0] = []
				this.tableware[0].push(...data.data.tableware)
			},
			selective_offer(item) {
				this.coupom_id = item
				this.coupon_show = false
				this.order_carListApi()
			}
		}
	}
</script>

<style lang="scss" scoped>
	::v-deep .u-icon__icon {
		font-size: 28rpx !important;
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

	.content {
		width: 707rpx;
		margin: 0 auto;

		.option {
			background: #FFFFFF;
			border-radius: 20rpx;
			overflow: hidden;

			.option_box {
				.option_box_1 {
					padding: 30rpx 20rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-bottom: 1rpx solid #F1F1F1;
					position: relative;

					.option_box_1_left {
						z-index: 2;

						.option_box_1_left_1 {
							font-size: 32rpx;
							font-weight: 600;
							color: #333333;
							-webkit-line-clamp: 1; //设置几行
							display: -webkit-box; //设置为伸缩盒弹性盒子展示
							overflow: hidden; //超出隐藏
							text-overflow: ellipsis; //设置超出部分以省略号展示
							-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
							display: flex;
							align-items: center;
						}

						.option_box_1_left_2 {
							font-size: 24rpx;
							font-family: PingFangSC-Regular, PingFang SC;
							font-weight: 400;
							color: #707070;
							padding-top: 10rpx;
							display: flex;
							align-items: center;
						}
					}

					.option_box_1_right {
						z-index: 2;

						.option_box_1_right_1 {
							height: 47rpx;
							background: #FFFFFF;
							font-size: 24rpx;
							font-weight: 400;
							color: #666666;
							display: flex;
							align-items: center;
							border-radius: 6rpx;
							padding: 0 10rpx;
							margin-bottom: 10rpx;
							white-space: nowrap;
							overflow: hidden;
							text-overflow: ellipsis;
						}

						.option_box_1_right_2 {
							width: 48rpx;
							height: 48rpx;
							// background: #DFDFDF;
							// box-shadow: 0rpx 4rpx 6rpx 0rpx rgba(218, 218, 218, 0.83), inset 0rpx 0rpx 3rpx 0rpx rgba(0, 0, 0, 0.26);
							border-radius: 10rpx;
							margin: 0 auto;

							.option_box_1_right_2_location {
								margin-left: 10rpx;
								padding-top: 10rpx;
							}
						}
					}

					.option_box_1_back {
						width: 230rpx;
						height: 100%;
						position: absolute;
						right: 0;
					}
				}

				.order_time {
					padding: 30rpx 20rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-bottom: 1rpx solid #F1F1F1;

					.order_time_left {
						font-size: 26rpx;
						font-weight: 400;
						color: #262626;
					}

					.order_time_right {
						font-size: 26rpx;
						font-weight: 400;
						color: #02B6FD;
					}
				}

				.phone {
					padding: 30rpx 20rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;

					.phone_left {
						font-size: 26rpx;
						font-weight: 400;
						color: #262626;
					}

					.phone_right {
						display: flex;
						align-items: center;

						.phone_right_1 {
							width: 279rpx;
							height: 100%;

							input {
								text-align: right;
								font-size: 26rpx;
								font-weight: 400;
								color: #C6C6C6;
							}
						}

						.phone_right_2 {
							width: 120rpx;
							height: 49rpx;
							border-radius: 2rpx;
							border: 1rpx solid #00B9FF;
							font-size: 24rpx;
							font-weight: 400;
							color: #02B6FD;
							text-align: center;
							line-height: 49rpx;
						}
					}
				}
			}

			.option_invite {
				.option_invite_top {
					padding: 20rpx 20rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-bottom: 1rpx solid #F2F2F2;

					.option_invite_top_left {
						font-size: 32rpx;
						font-weight: 400;
						color: #333333;
					}
				}

				.option_invite_down {
					padding: 20rpx 20rpx;
					display: flex;
					align-items: center;
					justify-content: space-between;

					.option_invite_down_left {
						font-size: 26rpx;
						font-weight: 400;
						color: #333333;
						line-height: 37rpx;
					}

					.option_invite_down_right {
						font-size: 26rpx;
						font-weight: 400;
						color: #02B6FD;
						display: flex;
						align-items: center;
						justify-content: space-between;
					}
				}
			}

		}

		.order_details {
			margin: 20rpx auto;
			background-color: #FFFFFF;
			border-radius: 20rpx;

			.order_details_hadr {
				width: 670rpx;
				margin: 0 auto;
				font-size: 32rpx;
				padding: 20rpx 0;
				font-weight: 400;
				color: #5F5F5F;
				border-bottom: 1rpx solid #F0F0F0;
			}

			.content {
				width: 670rpx;
				margin: 0 auto;
				padding: 20rpx 0;
			}

			.order_details_coupon {
				width: 670rpx;
				margin: 0 auto;
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 20rpx 0;
				border-top: 1rpx solid #F0F0F0;

				.order_details_coupon_left {
					display: flex;
					align-items: center;
					font-size: 24rpx;
					font-weight: 400;
					color: #313131;

					.order_details_coupon_left_img {
						width: 32rpx;
						height: 32rpx;
						margin-right: 10rpx;
					}
				}

				.order_details_coupon_right {
					display: flex;
					align-items: center;
					justify-content: space-between;
					font-size: 24rpx;
					font-weight: 400;
					color: #C2C2C2;

					.order_details_coupon_rightobj {
						color: #FA5151;
					}
				}
			}

			.order_details_count {
				width: 670rpx;
				margin: 0 auto;
				padding: 20rpx 0;
				text-align: right;
				border-top: 1rpx solid #F0F0F0;

				.order_details_countnum {
					font-size: 24rpx;
					color: #777777;
					margin-right: 12rpx;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #1D1D1D;
				}

				text:nth-child(3) {
					font-size: 28rpx;
					color: #1D1D1D;
				}


			}


		}

		.order_details_guarantee {
			background: #FFFFFF;
			border-radius: 20rpx;

			.order_details_guarantee_top {
				padding: 20rpx 20rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;

				.order_details_guarantee_top_left {
					display: flex;
					align-items: center;
					font-size: 28rpx;
					font-weight: 400;
					color: #313131;

					.order_details_guarantee_top_left_img {
						width: 28rpx;
						height: 30rpx;
						padding-right: 10rpx;
					}
				}

				.order_details_guarantee_top_right {
					font-size: 24rpx;
					font-weight: 400;
					color: #313131;
				}
			}

			.order_details_guarantee_down {
				padding: 0 20rpx;
				padding-bottom: 20rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #313131;
				display: flex;
				align-items: center;
				justify-content: space-between;
			}
		}

		.order_details_remark {
			background: #FFFFFF;
			border-radius: 20rpx;
			margin: 20rpx 0;

			.order_details_remark_box {
				padding: 30rpx 20rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;

				.order_details_remark_box_left {
					font-size: 26rpx;
					font-weight: 400;
					color: #313131;
				}

				.order_details_remark_box_right {
					font-size: 26rpx;
					font-weight: 400;
					color: #949494;
					text-align: right;
					display: flex;
					align-items: center;
				}
			}
		}

	}

	.tab_down {
		width: 100%;
		background-color: #FFFFFF;
		position: fixed;
		bottom: 0;
		padding-bottom: 20rpx;
		box-sizing: border-box;
		height: calc(70px + env(safe-area-inset-bottom) /2);

		.tab_down_box {
			padding: 20rpx 20rpx;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.tab_down_box_left {
				display: flex;
				align-items: flex-end;

				.tab_down_box_leftbig {
					font-size: 50rpx !important;
					color: #333333 !important;
					font-weight: bold !important;
					line-height: 1 !important;
				}

				text:nth-child(1) {
					font-size: 26rpx;
					font-weight: 400;
					color: #313131;
					white-space: nowrap;
				}

				text:nth-child(2) {
					font-size: 26rpx;
					font-weight: 400;
					color: #313131;
					margin-left: 10rpx;
				}

				text:nth-child(3) {
					font-size: 32rpx;
					font-weight: 500;
					color: #E45F3A;
					margin-left: 10rpx;
				}
			}

			.tab_down_box_right {
				// width: 203rpx;
				// height: 78rpx;
				padding: 14rpx 36rpx;
				box-sizing: border-box;
				background: #fff;
				border-radius: 8rpx;
				border: 2rpx solid #333;
				font-size: 28rpx;
				font-weight: 400;
				color: #333;
				// line-height: 82rpx;
				text-align: center;
				margin-right: 24rpx;
			}

			.tab_down_box_right1 {
				padding: 18rpx 40rpx;
				box-sizing: border-box;
				background: #E40030;
				border-radius: 8rpx;
				font-size: 28rpx;
				font-weight: 400;
				color: #fff;
				// line-height: 82rpx;
				text-align: center;
			}
		}
	}

	.up_remark {
		height: 450rpx;
		background: #FFFFFF;
		border-radius: 18rpx 18rpx 0rpx 0rpx;

		.up_remark_hadr {
			font-size: 34rpx;
			font-weight: 500;
			color: #373737;
			line-height: 48rpx;
			text-align: center;
			padding-top: 20rpx;
			padding-bottom: 30rpx;
		}

		.up_remark_content {
			width: 687rpx;
			height: 178rpx;
			margin: 0 auto;
			background: #F5F5F5;
			border-radius: 12rpx;
			position: relative;

			.up_remark_content_text {
				width: 100%;
				height: 100%;
				padding: 20rpx;
				font-size: 28rpx;
				font-weight: 400;
				color: #919191;
			}

			.up_remark_content_count {
				position: absolute;
				right: 20rpx;
				bottom: 10rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #919191;
			}
		}

		.up_remark_bottom {
			width: 706rpx;
			height: 88rpx;
			margin: 0 auto;
			background: #E40030;
			border-radius: 8rpx;
			text-align: center;
			font-size: 32rpx;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 88rpx;
			margin-top: 50rpx;
		}
	}

	.up_remark_bottom {
		width: 690rpx;
		height: 88rpx;
		margin: 0 auto;
		background: #E40030;
		border-radius: 8rpx;
		text-align: center;
		font-size: 32rpx;
		font-weight: 400;
		color: #FFFFFF;
		line-height: 88rpx;
		margin-top: 50rpx;
	}


	.tableware {
		height: 513rpx;
		text-align: center;

		.tableware_top {
			view:nth-child(1) {
				font-size: 34rpx;
				font-weight: 500;
				color: #373737;
			}

			view:nth-child(2) {
				font-size: 22rpx;
				font-weight: 400;
				color: #A1A1A1;
			}
		}
	}

	.affirm_box {
		width: 650rpx;
		background: #FFFFFF;
		border-radius: 22rpx;

		.affirm_box_1 {
			font-size: 24rpx;
			font-weight: 400;
			color: #02B6FD;
			padding: 20rpx 0;
			text-indent: 2em;
		}

		.affirm_box_2 {
			width: 550rpx;
			background: #F1FBFF;
			border-radius: 21rpx;
			margin: 0 auto;
			padding: 0 20rpx;

			view:nth-child(1) {
				font-size: 26rpx;
				font-weight: 600;
				color: #34383C;
				padding: 20rpx 0;
			}

			view:nth-child(2) {
				font-size: 24rpx;
				font-weight: 400;
				color: #62656C;
				padding-bottom: 10rpx;
				-webkit-line-clamp: 2; //设置几行
				display: -webkit-box; //设置为伸缩盒弹性盒子展示
				overflow: hidden; //超出隐藏
				text-overflow: ellipsis; //设置超出部分以省略号展示
				-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
			}

			view:nth-child(3) {
				font-size: 24rpx;
				font-weight: 400;
				color: #00B9FF;
				display: flex;
				align-items: center;

				.img {
					width: 25rpx;
					height: 28rpx;
					margin-right: 10rpx;
				}
			}
		}

		.affirm_box_3 {
			font-size: 22rpx;
			font-family: PingFangSC-Regular, PingFang SC;
			font-weight: 400;
			color: #00B9FF;
			text-indent: 2em;
			padding: 30rpx 0;
		}

		.affirm_box_4 {
			display: flex;
			height: 90rpx;
			border-top: 1rpx solid #E3E3E3;

			view:nth-child(1) {
				flex: 1;
				font-size: 32rpx;
				font-weight: 400;
				color: #313131;
				line-height: 90rpx;
				text-align: center;
				border-right: 1rpx solid #E3E3E3;
			}

			view:nth-child(2) {
				flex: 1;
				font-size: 32rpx;
				font-weight: 500;
				color: #00BCFF;
				line-height: 90rpx;
				text-align: center;
			}
		}
	}


	.content_box {
		width: 702rpx;
		// height: 256rpx;
		// background: #FFFFFF;
		margin: 20rpx auto;
		border-radius: 20rpx;
		background-size: 100% 100%;
		background-repeat: no-repeat;
		padding: 24rpx 20rpx;
		box-sizing: border-box;


		.content_box_1 {
			display: flex;
			// align-items: center;
			padding: 24rpx 0;
			box-sizing: border-box;

			.content_box_1_left {
				text-align: center;


				view:nth-child(1) {
					font-size: 40rpx;
					font-weight: 600;
					color: #FA5151;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #979797;
					margin-top: 8rpx;
				}
			}

			.content_box_1_right {


				view:nth-child(1) {
					font-size: 32rpx;
					font-weight: 600;
					color: #3A3A3A;
				}

				view:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #A0A0A0;
					margin-top: 8rpx;
				}
			}
		}

		.content_box_2 {
			display: flex;
			align-items: center;
			justify-content: space-between;
			border-top: 1rpx solid #F6F6F6;
			margin-top: 20rpx;
			padding-top: 15rpx;

			.content_box_2_left {
				display: flex;
				align-items: center;
				position: relative;

				view:nth-child(1) {
					font-size: 24rpx;
					font-weight: 400;
					color: #343434;
				}

				view:nth-child(2) {
					width: 33rpx;
					height: 33rpx;
					margin-left: 10rpx;
				}
			}

			.content_box_2_right {
				width: 151rpx;
				height: 58rpx;
				background: #E40030;
				border-radius: 8rpx;

				font-size: 24rpx;
				font-weight: 400;
				color: #FFFFFF;
				line-height: 58rpx;
				text-align: center;
			}
		}

		.rule {
			width: 299rpx;
			background: #FFFFFF;
			box-shadow: 0rpx 2rpx 10rpx 0rpx rgba(0, 0, 0, 0.13);
			border-radius: 8rpx;
			position: absolute;
			bottom: -30rpx;
			left: 140rpx;
			padding: 20rpx 20rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #A0A0A0;
		}
	}
</style>