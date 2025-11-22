<template>
	<view class="flex orders" v-show="display">
		<!-- wxinfo.height+ -->
		<view class="" :style="{width:'100%',height:wxinfo.top+'px',background:'#fff'}"></view>
		<view class="backgroundfff">
			<view class="search" @tap="routerTo('/pages/order_all/ordersearch/ordersearch?id='+store_default_data.id)">
				<u-search disabled :showAction="true" v-model="search" placeholder="想点什么搜一搜" :searchIconSize='40'
					:height='60' @custom='searchall' @change='inputchange' @search='searchall' actionText="搜索"
					:animation="true"></u-search>

			</view>
			<view class="hade_location">
				<view class="hade_location_left">
					<view class="hade_location_left_top flex ac">
						<view class="" @tap="store_collectionApi(store_default_data.is_collection)">
							<u-icon v-if="store_default_data.is_collection" name="star-fill" color='#E40030'
								size="32"></u-icon>
							<u-icon v-else name="star" color='#313131' size="32"></u-icon>
						</view>
						<view class="flex ac" @tap.stop="goorderAll()">
							<image class="store_image" :src="store_default_data.image" mode="aspectFill" v-if="store_default_data.image"></image>
							<text>{{store_default_data.alias_title}}</text>
							<u-icon v-if="option!==3" name="arrow-right" color='#313131' size="24rpx"></u-icon>
						</view>
					</view>
					<view class="hade_location_left_down flex ac" @tap.stop="goorderAll()">
						<image class="hade_location_left_downimg" :src="Httpimg+'order1.png'" mode="" v-if="Httpimg">
						</image>
						距离您 {{countKM(store_default_data.distance)||0}} km
					</view>
				</view>

				<view class="" v-if="seatShow">
					<view class="" v-if="store_default_data.takeaway_switch || store_default_data.delivery_switch">
						<view class="hade_location_right" v-if="store_default_data.is_business==1">
							<view class="hade_location_right_content flex ac jc"
								:class="{hade_location_right_content_activate:activate_data==1&&store_default_data.delivery_switch}"
								@tap="activate(1)" v-if="store_default_data.delivery_switch">
								自取
							</view>
							<view class="hade_location_right_content flex ac jc"
								:class="{hade_location_right_content_activate:activate_data==2&&store_default_data.takeaway_switch}"
								@tap="activate(2)" v-if="store_default_data.takeaway_switch">
								外卖
							</view>
						</view>
						<view class="tag" v-else>
							已歇业
						</view>
					</view>
					<view class="tag" v-else>
						歇业中
					</view>
				</view>
				<view class="" v-else>
					<view class="hade_location_right" v-if="store_default_data.is_business==1">
						<view class="hade_location_right_content flex ac jc hade_location_right_content_activate">
							堂食
						</view>
					</view>
					<view class="tag" v-else>
						已歇业
					</view>
				</view>
			</view>
			<view class="flex jc ac notices">
				<view class="notice">
					<u-notice-bar :text="content" color="#AAAAAA" fontSize="24" direction="column" mode="link"
						speed="250" @click="noticeclick"></u-notice-bar>
				</view>
			</view>
			<view class="shopmenu ">
				<view class="flex ac">
					<image class="shopmenuimg" :src="Httpimg+'orders.png'" mode="" v-if="Httpimg"></image>
					<view class="shopmenutt">
						门店菜单
					</view>
					<!-- 桌号/人数 -->
					<view style="margin-left: auto;" class="shopmenutt" v-if="!seatShow">
						{{dine_msg.number}}号桌/{{dine_msg.count}}人
					</view>
				</view>
			</view>
		</view>
		<view class="felx f1">
			<orderlist @aid_mgs='aid_mgs' :rightId="rightId" :indexc='components_indexb' :content='store_infoApi_data'>
			</orderlist>
			<view class="felx_right_box">
				<scroll-view class="felx_right_boxscroll" :scroll-with-animation="true" :scroll-into-view="leftId"
					scroll-y="true" style="height: 100%;" @scroll="leftScroll">

					<!-- 	:refresher-enabled='true'
						@refresherpulling='refresherpulling' -->
					<view class="felx_right_box_conten" id="scrollli" v-for="(ite,inde) in store_infoApi_data"
						:key="ite.id" :id="'goods'+inde">

						<view class="felx_right_box_conten_title">
							<!-- store_infoApi_data[childindex].name -->
							{{ite.name}}
						</view>
						<view style="margin-top: 150rpx;" v-if="ite.goods.length<1">
							<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
								text='暂无此类餐饮'></u-empty>
						</view>
						<ordercard :order_id='order_id' @specification="specification" :content='ite.goods'
							:store_id='store_default_data.id' @order='order_carListApi' :activate_data='activate_data'
							@login='enter=true'>
						</ordercard>
					</view>
					<height :hg='70'></height>
				</scroll-view>

			</view>
			<u-popup :safeAreaInsetBottom='false' mode="center" :round='"16rpx"' :show="popshow">
				<view class="popBox flexColumn">
					<view class="people_title">就餐人数</view>
					<view class="people flexc flexw flexs">
						<view class="people_item " @click="setindex(index)" :class="peopleCur==index?'active':''"
							v-for="(item,index) in 7" :key="index">
							{{index + 1}}人
						</view>
						<view class="people_item flex jc ac">
							<view class="" v-if="addshow" @click="setadd">
								<span>更多</span>
								<image src="../../static/edit.png" mode=""></image>
							</view>
							<view class="people_item_ipt" v-else>
								<input maxlength="2" v-model="people" type="number" placeholder="请输入"
									placeholder-style="font-size:24rpx;" />
							</view>

						</view>
					</view>
					<view class="people_btn" @click="Popconfirm()">
						确认
					</view>
				</view>
			</u-popup>
		</view>
		<close v-if="show_specification!=true" :shopping_trolley_list='shopping_trolley_list' @update='order_carListApi'
			@register='register' :addAdish='addAdish' :seatShow='seatShow'>
		</close>
		<specification ref='specification' :store_id='store_default_data.id' :activate_data='activate_data'
			:order_id='order_id' :type='order_type' @order='order_carListApi' :specshow='show_specification'
			@close='show_specification=false' @login='enter=true'>
		</specification>

		<login :show="enter" @loadpage="unloadpage" @closepage='closepage'></login>
		<movable-area class="movableArea">
			<movable-view class="movableView" direction="all" x="600rpx" y="800rpx">
				<view class="coupn" v-if="counp_show">
					<view class="coupn_icon" @tap="counp_show=false">
						<u-icon name="close-circle-fill" color="#0C0B0B" size="28"></u-icon>
					</view>
					<view class="coupn_img" @tap="routergo('/pages/me_all/coupon_collection/coupon_collection')">
						<image src="../../static/Project_drawing 38.png" mode=""></image>
					</view>
					<view class="coupn_title" @tap="routergo('/pages/me_all/coupon_collection/coupon_collection')">
						<view class="coupn_title_text">
							领券中心
						</view>
						<u-icon name="arrow-right" color="#68150A " size="22"></u-icon>
					</view>
				</view>
			</movable-view>
		</movable-area>
	</view>
</template>

<script>
	import XmBezierCart from '@/components/xm-bezier-cart/xm-bezier-cart.vue';
	import {
		message
	} from "@/api/user.js"
	import {
		toDate
	} from "@/utils/time.js"
	import config from '@/config.js'
	import Location from "@/utils/wxApi.js"
	import {
		store_default,
		store_collection,
		store_cancelColl,
		store_info,
		store_itemPrice,
		store_item,
		store_goodsSearch,
		order_joinCar,
		order_carList,
		order_empty,
		seat
	} from "@/api/comm.js"
	import {
		forEach
	} from "lodash";
	import {
		cellphone
	} from "@/utils/type_height.js"
	import {
		userInfo
	} from "@/api/public.js"
	import { data } from 'uview-ui/libs/mixin/mixin';
	export default {
		data() {
			return {
				rightId: '', //右边选中的
				topList: [], //获取每个节点到顶部的距离
				leftId: '', //右边滚动到左边左边的id
				notice: ['通知1', '通知2'],
				Httpimg: config.HttpImg,
				search: '',
				addshow: true,
				popshow: false,
				// 胶囊开关
				activate_data: 2,
				// 规格参数
				show_specification: false,
				// 领卷
				counp_show: true,
				list1: '',
				list1url: '',
				childindex: 0, //侧边栏子组件传过来的索引
				store_default_data: {}, //默认门店
				store_infoApi_data: [], //分类列表
				orderlist: [], //此分类下的商品
				store_id: '', //选择门店后使用选择的门店
				specification_list: [], //规格参数
				order_id: '', //当前规格商品id
				order_type: null, //判断多规格还是单规格
				style: [], //规格动态样式
				orderprive: 0, //价格
				shopping_trolley_list: {}, //购物车列表
				display: false, //页面数据未加载前隐藏
				enter: false, //判断是否登录登录
				System_height: cellphone(), //系统高度
				components_index: 0, //刷新组件索引
				components_indexb: 0, //刷新组件索引
				scene: null,
				seatShow: true,
				peopleCur: 0,
				peopleCount: 1,
				people: '',
				addAdish: false,
				option: null,
				content: [],
				contentarr: [],

				// 扫码就餐信息
				dine_msg: {
					number: '', //桌号
					count: 0,
				}
			};

		},
		components: {
			XmBezierCart
		},
		onLoad(e) {
			if (e.scene) {
				let option = uni.getStorageSync('option')
				if (option !== 3) {
					order_empty()
				}
				let bb = e.scene.split('_')
				let cc = bb[1]
				this.dine_msg.number = bb[0]
				if (cc == '1' || cc == 1) {
					uni.setStorageSync('firstPay', 1)
				} else {
					uni.setStorageSync('firstPay', 2)
				}
				//2先餐后付款 //1先付款后餐
				uni.setStorageSync('option', 3)
				uni.setStorageSync('scene', bb[0])
				this.activate_data = 3
				this.getseat(bb[0])
			}
			this.messageApi()
		},
		onShow() {
			if (!uni.getStorageSync("userinfo")) {
				this.shopping_trolley_list = {}
			}
			this.store_defaultApi() //默认门店
			let user = uni.getStorageSync('userinfo')
			let add = uni.getStorageSync('addAdish') //加菜还是结算
			let option = uni.getStorageSync('option')
			let scene = uni.getStorageSync('scene')
			let firstPay = uni.getStorageSync('firstPay')
			this.option = option
			if (user) {
				this.getUser()
			}
			if (option == 3) {
				this.seatShow = false
				// this.addAdish = true
				this.getseat(scene)
			} else {
				if (scene) {
					uni.removeStorageSync('scene')
					uni.removeStorageSync('peopleCount')
				}
				this.seatShow = true
				this.addAdish = false
			}
			this.enter = false
			this.activate_data = option
			this.getlocation() //模糊定位
			this.store_defaultApi(1) //默认门店
			// this.order_carListApi()
		},
		onReady() {

		},
		computed: {
			wxinfo() {
				const info = uni.getMenuButtonBoundingClientRect()
				return info;
			}
		},
		methods: {
			noticeclick(e) {
				console.log(e)
				this.routergo('/pages/me_all/particulars/particulars?id=' + this.contentarr[e].id)
				return

			},
			async messageApi() {
				let messages = [];
				message({
					page: 1,
					limit: 3
				}).then(data => {
					uni.hideToast()
					if (data.code == 1) {
						data.data.data.forEach(item => {
							messages.push(item.content)
						})
						this.contentarr = data.data.data;
						this.content = messages;
						uni.hideToast()
					} else {
						this.messages = [];
						uni.hideToast()
					}
					uni.hideToast()
				})
				uni.hideToast()

			},
			leftScroll(e) {
				const scrollTop = e.target.scrollTop + 400; //获取屏幕滚动的距离

				// console.log(scrollTop, '滚动距离')

				for (var i = 0; i <= this.topList.length; i++) {
					if (parseInt(scrollTop) >= parseInt(this.topList[i]) && parseInt(scrollTop) <= parseInt(this.topList[
							i + 1])) {
						this.components_indexb = i;
						this.rightId = 'class' + i;
						// console.log(i, '选中的')
					} else {
						if (scrollTop >= this.topList[this.topList.length - 1]) {
							this.components_indexb = this.topList.length - 1;
							this.rightId = 'class' + this.topList.length - 1;
							console.log('选中的最后一个')
						}
					}
				}
			},
			// 获取右边title节点属性  或者 轮播图
			getRightTop() {
				// setTimeout(() => {
				this.$nextTick(() => {
					const query = uni.createSelectorQuery().in(this);
					query.selectAll('.felx_right_box_conten').boundingClientRect(data => {
						console.log(data, '进入了')
						data.forEach((item) => {
							this.topList.push(item.top)
						})
					}).exec();
				})
				// }, 200)

			},
			countKM(m) {
				let ms = (m * 1 / 1000).toFixed(2);
				let mss = 0;
				if (ms > 0) {
					mss = ms;
				}
				return mss;
			},
			goorderAll() {
				let option = uni.getStorageSync('option')
				if (option !== 3) {
					this.routergo('/pages/order_all/selectstore/selectstore')

				}
			},
			async getUser() {
				let user = await userInfo()
				uni.hideToast()
				if (user.code == 1) {
					uni.setStorageSync('user', user.data)
				}
			},
			Popconfirm() {
				// 判断用户是否登录
				if (!uni.getStorageSync("userinfo")) {
					this.enter = true
					return
				}
				this.popshow = false
				if (this.addshow) {
					uni.setStorageSync('peopleCount', this.peopleCount)
				} else {
					uni.setStorageSync('peopleCount', this.people)
				}
				this.dine_msg.count = uni.getStorageSync('peopleCount') || 0
				this.order_carListApi()
			},
			setadd() {
				this.addshow = false
				this.peopleCur = null

			},
			setindex(index) {
				this.peopleCur = index
				this.peopleCount = index + 1
				this.addshow = true
				this.people = ''
			},
			// 获取桌号信息
			async getseat(id) {
				var peopleCount = uni.getStorageSync('peopleCount')
				let token = uni.getStorageSync('userinfo').token
				let firstPay = uni.getStorageSync('firstPay')
				let res = await seat({
					id
				})
				if (res.code == 1) {
					this.scene = res.data
					this.seatShow = false

					// 获取该餐桌就餐人数
					uni.setStorageSync('peopleCount', res.data.people_count)
					peopleCount = uni.getStorageSync('peopleCount')
					this.dine_msg.count = peopleCount
					this.dine_msg.number = res.data.no


					uni.setStorageSync('shop', res.data.store_id)
					this.store_defaultApi() //默认门店
					if (res.data.order_id > 0) {
						if (firstPay == 1) {
							this.addAdish = false
						} else {
							this.addAdish = true
						}
						uni.setStorageSync('orderId', res.data.order_id)
						this.popshow = false
					} else {
						this.addAdish = false
					}
					uni.setStorageSync('addAdish', this.addAdish)
					setTimeout(() => {
						this.order_carListApi()
					}, 500)
					console.log(peopleCount, res)
					if (!peopleCount && res.data.order_id == 0) {
						this.popshow = true
					}
				} else {
					uni.showToast({
						title: res.msg,
						icon: 'none'
					})
				}
			},
			async activate(index) {
				if (this.components_index == index) return
				if (index == 1) {
					this.components_index = 1
				} else {
					this.components_index = 2
				}
				uni.setStorageSync('option', index)
				this.activate_data = index
				this.store_defaultApi()
				// 清空购物车
				let data = await order_empty()
				// uni.showToast({
				// 	title: data.msg,
				// 	icon: 'none'
				// })
				this.store_infoApi()
				this.order_carListApi()
			},
			async specification(e) {
				this.order_id = e.e
				this.order_type = e.type
				setTimeout(() => {
					this.$refs.specification.specification()
					this.show_specification = true
				}, 500)
			},
			aid_mgs(e, index) {
				this.childindex = index
				this.leftId = e;
				// this.orderlist = []
				// this.orderlist.push(...e)
			},
			swpclick(url) {
				console.log(url)
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				}
				if (url) {
					let arr = ['/pages/index/index', '/pages/order/order', '/pages/order_form/order_form', '/pages/me/me']
					if (arr.includes(url)) {
						uni.switchTab({
							url: url
						})
					} else {
						uni.navigateTo({
							url: url
						})
					}
				}
			},
			async getlocation() {
				uni.getLocation({
					success(data) {
						uni.setStorageSync('location', data)
					}
				})
			},
			async store_defaultApi(e) {
				let shop = uni.getStorageSync('shop')
				let data = await store_default({
					id: shop || ''
				})
				if (data.code == 1) {
					this.store_default_data = data.data
					uni.setStorageSync('shop', this.store_default_data.id)
					uni.setStorageSync('currentShop', this.store_default_data)
				}
				if (data.data.takeaway_switch == 1 && data.data.delivery_switch !== 1) {
					this.activate_data = 2
					uni.setStorageSync('option', 2)
				}
				this.list1 = data.data.image;
				this.list1url = data.data.tz_url;
				if (e == 1) {
					this.store_infoApi() //门店下的分类
				}
				this.order_carListApi() //购物车列表
			},
			async store_collectionApi(comtent) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					uni.showToast({
						title: '未登录',
						icon: 'none'
					})
					return
				} else {
					if (comtent) {
						let data = await store_cancelColl({
							store_id: this.store_default_data.id
						})
						if (data == 1) {
							// uni.showToast({
							// 	title: data.msg,
							// 	icon: 'success'
							// })
						} else {
							// uni.showToast({
							// 	title: data.msg,
							// 	icon: 'error'
							// })
						}
					} else {
						let data = await store_collection({
							store_id: this.store_default_data.id
						})
						if (data == 1) {
							// uni.showToast({
							// 	title: data.msg,
							// 	icon: 'success'
							// })
						} else {
							// uni.showToast({
							// 	title: data.msg,
							// 	icon: 'error'
							// })
						}
					}
					this.store_defaultApi()
					// this.order_carListApi()
				}
			},
			async store_infoApi() {
				let data = await store_info({
					store_id: this.store_default_data.id,
					shipment: this.activate_data || 2
				})
				console.log(data, '商品数据')
				this.store_infoApi_data = []
				this.store_infoApi_data.push(...data.data)
				this.orderlist = data.data[0].goods
				this.display = true;
				this.getRightTop() //查询子节点
			},

			async selective_specification(index, id, name, type) {
				let that = this
				this.specification_list.forEach((item, idx) => {
					item.item.forEach((item1, idx1) => {
						if (item1.id == id) {
							if (item1.is_show) {
								if (type == 2) {
									item1.is_show = false
								}
							} else {
								item1.is_show = true
							}
						} else {
							if (type == 1) {
								// 多选加这个
								if (idx == index) {
									item1.is_show = false
								}
							}
						}
					})
				})
				this.style = []
				this.specification_list.forEach(item => {
					item.item.forEach((item1, idx1) => {
						if (item1.is_show) {
							this.style.push(item1)
						}
					})
				})

				this.store_itemPriceApi()
				this.$forceUpdate()
			},
			// 价格计算
			async store_itemPriceApi() {
				let str = ''
				this.style.forEach(res => {
					str += res.id + '_'
				})
				str = str.substring(0, str.length - 1)
				let data = await store_itemPrice({
					goods_id: this.order_id,
					spu_id: str
				})
				this.orderprive = Location.BumberPrecision(data.data.price)
			},
			// 添加购物车
			async add_joinCar() {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				}
				let str = []
				this.style.forEach(res => {
					str.push(res.id)
				})
				let data = await order_joinCar({
					store_id: this.store_default_data.id,
					goods_id: this.order_id,
					spu_id: str,
					count: 1,
					order_type: this.activate_data

				})
				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
					this.order_carListApi()
					this.show_specification = false
					this.style = []
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			// 购物车列表
			async order_carListApi() {
				let token = uni.getStorageSync('userinfo').token
				if (!token) {
					this.shopping_trolley_list = []
					return
				}
				let data;
				let peopleCount = uni.getStorageSync('peopleCount') || 0
				// let iscwf = uni.getStorageSync('public').iscwf
				let seat_id = uni.getStorageSync('scene')
				let iscwf = this.store_default_data.iscwf
				let firstPay = uni.getStorageSync('firstPay')

				let type;
				if (this.addAdish) {
					type = 2
				} else {
					type = 1
				}
				if (iscwf == 1) {
					if (firstPay == 2) {
						// console.log('店铺id1', this.store_default_data)
						data = await order_carList({
							peopleCount: peopleCount,
							type,
							store_id: this.store_default_data.id,
							seat_id,
						})
					} else {
						// console.log('店铺id2', this.store_default_data)
						data = await order_carList({
							store_id: this.store_default_data.id,
							peopleCount: peopleCount,
							seat_id
						})
					}

				} else {
					console.log('店铺id', seat_id, '不需要餐位费')
					data = await order_carList({
						store_id: this.store_default_data.id,
						seat_id
					})
				}
				if (data.code == 1) {
					this.shopping_trolley_list = data.data
				} else {
					this.shopping_trolley_list = []
				}

			},
			//结算前判断是否登录
			register() {
				this.enter = true
			},
			router_close(url) {
				uni.reLaunch({
					url: url
				})
			},
			routergo(url) {
				uni.navigateTo({
					url
				})
			},
			//未登录关闭弹出层需要关掉组件
			closepage() {
				this.enter = false
			},
			refresherpulling() {

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
	.orders {
		flex-direction: column;
		height: 100vh;

	}

	.backgroundfff {
		background: #fff;
	}

	.shopmenu {
		padding: 30rpx 35rpx;
		box-sizing: border-box;
		margin-top: 18rpx;
		border-bottom: 2rpx solid #F2F2F2;
		background: #fff;

		.shopmenuimg {
			width: 28rpx;
			height: 28rpx;
			margin-right: 12rpx;
		}

		.shopmenutt {
			font-size: 28rpx;
			color: #333333;
		}
	}


	.movableArea {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		pointer-events: none; //设置area元素不可点击，则事件便会下移至页面下层元素
		z-index: 3;
	}

	.notices {
		background: #fff;
	}

	.notice {
		width: 100%;
		background: #fff;
		width: 690rpx;
		margin: auto;

		::v-deep {
			.u-notice-bar {
				height: 52rpx !important;
				background: #FAFAFA !important;
				border-radius: 62rpx !important;
				padding: 14rpx 20rpx !important;
				box-sizing: border-box !important;
			}

			.u-icon__icon {
				font-size: 24rpx !important;
			}
		}


	}

	.movableView {
		pointer-events: auto; //可以点击
	}

	.popBox {
		padding: 22rpx;
		width: 561rpx;
		background: #FFFFFF;
		border-radius: 16rpx;

		.people_title {
			font-size: 24rpx;
			color: #676767;
		}

		.people {
			margin: 20rpx 0;

			.people_item {
				width: 23%;
				height: 63rpx;
				background: #F4F5F7;
				border-radius: 8rpx;
				border: 1rpx solid #999999;
				text-align: center;
				line-height: 63rpx;
				margin-top: 24rpx;
				font-size: 24rpx;
				color: #999999;

				image {
					width: 18rpx;
					height: 18rpx;
				}

				.people_item_ipt {
					padding: 8rpx;
					box-sizing: border-box;


					input {
						color: #000;
					}
				}

			}

			.active {
				border: 1rpx solid #333 !important;
				color: #fff !important;
				background: #333333 !important;
			}
		}

		.people_btn {
			width: 100%;
			height: 74rpx;
			background: #333333;
			border-radius: 9rpx;
			font-size: 28rpx;
			color: #FFFFFF;
			line-height: 74rpx;
			text-align: center;
		}
	}

	.search {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 550rpx;
		padding-left: 30rpx;
		box-sizing: border-box;
		font-size: 26rpx;
		background: #fff;

		::v-deep {
			.u-search__content {
				background-color: #FAFAFA !important;

			}

			.u-search__content__input {
				background-color: #FAFAFA !important;
			}
		}



		.search_left {
			font-size: 32rpx;
			font-weight: 600;
			color: #05B6F6;
			padding-left: 30rpx;
		}

		.search_right {
			width: 62rpx;
			height: 62rpx;
			padding: 0 10rpx;
		}
	}

	.hade_location {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 24rpx 30rpx 24rpx 30rpx;
		box-sizing: border-box;
		// border-bottom: 1rpx solid #F4F4F4;

		.tag {
			height: 39rpx;
			border-radius: 4rpx;
		border: 2rpx solid #E40030;
		font-size: 24rpx;
		font-weight: 400;
		color: #E40030;
			text-align: center;
			box-sizing: border-box;
			padding: 0 10rpx;
			margin-right: 10rpx;
			line-height: 37rpx;
		}

		.hade_location_left {
			// margin-left: 20rpx;

			.hade_location_left_top {
				display: flex;
				align-items: center;
				font-size: 30rpx;
				font-weight: bold;
				color: #313131;

				.store_image {
					width: 60rpx;
					height: 60rpx;
					border-radius: 8rpx;
					margin-right: 12rpx;
					margin-left: 12rpx;
				}

				text {
					-webkit-line-clamp: 1; //设置几行
					display: -webkit-box; //设置为伸缩盒弹性盒子展示
					overflow: hidden; //超出隐藏
					text-overflow: ellipsis; //设置超出部分以省略号展示
					-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
					margin-right: 6rpx;
					margin-left: 0;
				}

			}

			.hade_location_left_down {
				font-size: 24rpx;
				font-weight: 400;
				color: #666A6B;
				margin-top: 12rpx;

				.hade_location_left_downimg {
					width: 24rpx;
					height: 24rpx;
					margin-right: 12rpx;
				}
			}
		}

		.hade_location_right {
			// width: 180rpx;
			// height: 63rpx;
			background: #F4F4F4;
			// border-radius: 32rpx;
			margin-right: 20rpx;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.hade_location_right_content {
				width: 96rpx;
				height: 56rpx;
				text-align: center;
				font-size: 24rpx;
				font-weight: 400;
				color: #333;

			}

			.hade_location_right_content_activate {
				background-color: #E40030;
				color: #fff;
				border-radius: 4rpx;
			}
		}
	}

	.felx_right_box_conten_img {
		width: 100%;
		overflow: hidden;
		position: absolute;
		z-index: 2;

		// margin: auto;
		.felx_right_box_conten_imgss {
			width: 542rpx;
			margin: auto;
			padding: 24rpx 20rpx;
			box-sizing: border-box;
			background: #fff;
			border-radius: 16rpx;
		}
	}

	.felx {
		display: flex;
		justify-content: space-between;
		overflow: hidden;

		.felx_right_box {
			width: 560rpx;
			height: 100%;
			background: #FAFAFA;
			// height: 79vh;
			position: relative;

			.felx_right_boxscroll {
				padding: 0;
				box-sizing: border-box;

			}

			.felx_right_box_conten {
				width: 542rpx;
				margin: auto;
				margin-bottom: 24rpx;
				border-radius: 20rpx;
				background: #fff;
				padding: 24rpx 20rpx;
				box-sizing: border-box;



				.felx_right_box_conten_title {
					font-size: 26rpx;
					font-weight: 600;
					color: #353535;
					padding: 30rpx 0 30rpx 10rpx;
				}
			}
		}
	}

	.specification {
		width: 688rpx;
		background: #FFFFFF;
		border-radius: 12rpx;
		position: relative;
		z-index: 10074;

		.specification_title {
			text-align: center;
			font-size: 32rpx;
			font-weight: 600;
			color: #353535;
			margin: 0 auto;
		}

		.specification_title_1 {
			width: 95%;
			height: 504rpx;
			margin: 0 auto;

			.specification_title_1_title {
				font-size: 24rpx;
				font-family: PingFangSC-Regular, PingFang SC;
				font-weight: 400;
				color: #676767;
				display: inline-block;
				padding: 20rpx 0;

			}

			.specification_title_1_content {
				display: flex;
				overflow-x: auto;
				display: -webkit-box;
				-webkit-overflow-scrolling: touch;

				.specification_title_1_content_flex_activate {
					background: #F4FCFF !important;
					border: 1rpx solid #00B8FB !important;
					color: #00B8FB !important;
				}

				.font_sizi_1 {
					color: #00B8FB;
				}

				.font_sizi_2 {
					border-left: 1rpx solid #00B8FB;
				}

				.specification_title_1_content_flex {
					height: 63rpx;
					background: #FFFFFF;
					border-radius: 14rpx;
					border: 1rpx solid #F1F1F1;
					margin-right: 20rpx;
					text-align: center;
					line-height: 63rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #343434;
					padding: 0 40rpx;
				}
			}
		}

		.close {
			position: absolute;
			bottom: -150rpx;
			left: 50%;
			transform: translateX(-50%);
		}
	}

	.selected {
		width: 688rpx;
		padding: 20rpx 0;
		background: #F5F5F5;
		margin-top: 60rpx;

		text {
			font-size: 24rpx;
			font-weight: 400;
			color: #363636;
		}

		text:nth-child(1) {
			font-size: 24rpx;
			font-weight: 400;
			color: #676767;
			padding: 0 20rpx;
			margin-left: 10rpx;
		}
	}

	.sublist {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30rpx 40rpx;

		.sublist_left {
			text:nth-child(1) {
				font-size: 28rpx;
				font-weight: 600;
				color: #363636;
			}

			text:nth-child(2) {
				font-size: 28rpx;
				font-weight: 600;
				color: #FF4000;

				text:nth-child(1) {
					font-size: 28rpx;
					font-weight: 600;
					color: #FF4000;
					font-size: 24rpx;
				}
			}
		}

		.sublist_right {
			width: 234rpx;
			height: 62rpx;
			background: #02B6FD;
			border-radius: 12rpx;
			font-size: 32rpx;
			font-weight: 500;
			color: #F3FCFF;
			line-height: 62rpx;
			text-align: center;
		}
	}

	.coupn {
		.coupn_icon {
			width: 24rpx;
			height: 24rpx;
			margin-left: 100rpx;
			margin-bottom: 10rpx;
			opacity: 0.5;
		}

		.coupn_img {
			width: 130rpx;
			height: 121rpx;
		}

		.coupn_title {
			width: 120rpx;
			padding: 5rpx 10rpx;
			background: #FEDD5B;
			border-radius: 18rpx;
			display: flex;
			align-items: center;
			position: relative;
			bottom: 30rpx;

			.coupn_title_text {
				font-size: 22rpx;
				font-weight: 400;
				color: #631407;
				margin-left: 10rpx;
			}
		}
	}
</style>