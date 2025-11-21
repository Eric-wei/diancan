<template>
	<view v-show="display" class="merchantOrder flex">
		<view class="hade_location">
			<view class="hade_location_left">
				<view class="hade_location_left_top">
					<text>{{store_default_data.alias_title}}</text>
				</view>
				<view class="hade_location_left_down">
					{{store_default_data.desc}}
				</view>
			</view>
		</view>
		<view class="felx f1">
			<orderlist @aid_mgss='aid_mgs' :indexc='components_index' :nopadding="true" :content='store_infoApi_data'>
			</orderlist>
			<view class="felx_right_box">
				<scroll-view class="felx_right_boxscroll" style="height: 100%;" scroll-y="true">
					<!-- 	:refresher-enabled='true'
						@refresherpulling='refresherpulling' -->
					<view class="felx_right_box_conten">
						<view class="felx_right_box_conten_img">
							<image :src="list1" mode="aspectFill"
								style="width: 100%;height: 251rpx;border-radius: 12rpx;" @click="swpclick(list1url)">
							</image>
							<!-- <u-swiper :list="list1" :height='251'></u-swiper> -->
						</view>
						<view class="felx_right_box_conten_title">
							{{store_infoApi_data[childindex].name}}
						</view>
						<view style="margin-top: 150rpx;" v-if="orderlist.length<1">
							<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
								text='暂无此类餐饮'></u-empty>
						</view>
						<ordercard @specification="specification" :content='orderlist' :store_id='store_default_data.id'
							:activate_data='activate_data' @order='order_carListApi'></ordercard>
					</view>
					<height :hg='90'></height>
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
						<view class="people_item ">
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
			@register='register' :addAdish='addAdish' :seatShow='seatShow' pb="30rpx">
		</close>
		<specification ref='specification' :store_id='store_default_data.id' :activate_data='0' :order_id='order_id'
			:type='order_type' @order='order_carListApi' :specshow='show_specification'
			@close='show_specification=false' @login='enter=true'>
		</specification>
		<login :show="enter" @loadpage="unloadpage" @closepage='closepage'></login>
	</view>
</template>

<script>
	import
	Location
	from "@/utils/wxApi.js"
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
	export default {
		data() {
			return {
				addshow: true,
				popshow: false,
				// 胶囊开关
				activate_data: 1,
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
				style: [], //规格动态样式
				orderprive: 0, //价格
				shopping_trolley_list: {}, //购物车列表
				display: false, //页面数据未加载前隐藏
				enter: false, //判断是否登录登录
				System_height: cellphone(), //系统高度
				components_index: 0, //刷新组件索引
				scene: null,
				seatShow: true,
				peopleCur: 0,
				peopleCount: 1,
				people: '',
				addAdish: false,
				option: null,
				order_type: null, //判断多规格还是单规格
			};

		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		onLoad(e) {
			this.getlocation() //模糊定位
			this.store_defaultApi(1) //默认门店		
			this.remove()


		},
		onShow() {
			if (!uni.getStorageSync('peopleCount')) {
				console.log('就餐人数')
				this.popshow = true
			}

			let user = uni.getStorageSync('userinfo')
			let add = uni.getStorageSync('addAdish') //加菜还是结算
			let option = uni.getStorageSync('option')
			let scene = uni.getStorageSync('scene')
			let firstPay = uni.getStorageSync('firstPay')
			this.addAdish = uni.getStorageSync('addAdish')
			this.option = option
			if (user) {
				this.getUser()
			}
			if (option == 3) {
				this.seatShow = false
			} else {
				if (scene) {
					uni.removeStorageSync('scene')
					uni.removeStorageSync('peopleCount')
				}
				this.seatShow = true
				this.addAdish = false
			}
			this.enter = false
			this.order_carListApi() //购物车列表
		},
		methods: {
			// 清空购物车
			async remove() {
				let res = await order_empty()
				if (res.code == 1) {
					this.order_carListApi() //购物车列表
				}
			},
			goorderAll() {
				let option = uni.getStorageSync('option')
				if (option !== 3) {
					this.routergo('/pages/order_all/selectstore/selectstore')

				}
			},
			async getUser() {
				let user = await userInfo()
				if (user.code == 1) {
					uni.setStorageSync('user', user.data)
				}
			},
			Popconfirm() {
				this.popshow = false
				if (this.addshow) {
					uni.setStorageSync('peopleCount', this.peopleCount)
				} else {
					uni.setStorageSync('peopleCount', this.people)
				}
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
				let res = await seat({
					id
				})
				if (res.code == 1) {
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
					this.order_carListApi()

				}
			},
			async activate(index) {
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
				uni.showToast({
					title: data.msg,
					icon: 'none'
				})
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
				this.components_index = index;
				this.orderlist = []
				this.orderlist.push(...e)
			},
			swpclick(url) {
				console.log()
				if (url) {
					uni.navigateTo({
						url: url
					})
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
				let shop = uni.getStorageSync('shopuser').id
				let data = await store_default({
					id: shop || '',

				})
				this.store_default_data = data.data
				if (data.data.takeaway_switch == 1 && data.data.delivery_switch == 0) {
					this.activate_data = 2
				}
				this.list1 = data.data.image;
				this.list1url = data.data.tz_url;

				if (e == 1) {
					this.store_infoApi() //门店下的分类
				}
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
					this.order_carListApi()
				}
			},
			async store_infoApi() {
				let data = await store_info({
					store_id: this.store_default_data.id,
					shipment: 3
				})
				this.store_infoApi_data = []
				this.store_infoApi_data.push(...data.data)
				this.orderlist = data.data[0].goods
				this.display = true
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
				let iscwf = uni.getStorageSync('shopuser').iscwf
				let firstPay = uni.getStorageSync('firstPay')

				let seat_id = uni.getStorageSync('scene')

				let type;
				if (this.addAdish) {
					type = 2
				} else {
					type = 1
				}
				if (iscwf == 1) {
					if (firstPay == 2) {
						data = await order_carList({
							peopleCount: peopleCount,
							type,
							store_id: this.shopuser.id,
							seat_id
						})
					} else {
						data = await order_carList({
							peopleCount: peopleCount,
							type,
							store_id: this.shopuser.id,
							seat_id
						})
					}
				} else {
					data = await order_carList({
						store_id: this.shopuser.id,
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
		background: #FFFFFF;
	}
</style>
<style lang="scss" scoped>
	.movableArea {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		pointer-events: none; //设置area元素不可点击，则事件便会下移至页面下层元素
	}

	.merchantOrder {
		height: 100vh;
		flex-direction: column;
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
				border-radius: 14rpx;
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
				border: 1rpx solid #00B8FB !important;
				color: #00B8FB !important;
				background: #F4FCFF !important;
			}
		}

		.people_btn {
			width: 100%;
			height: 74rpx;
			background: #05B6F6;
			border-radius: 659rpx;
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
		padding: 30rpx 0;
		border-bottom: 1rpx solid #F4F4F4;

		.tag {
			height: 39rpx;
			border-radius: 4rpx;
			border: 1rpx solid #05B6F6;
			font-size: 24rpx;
			font-weight: 400;
			color: #05B6F6;
			text-align: center;
			box-sizing: border-box;
			padding: 0 10rpx;
			margin-right: 10rpx;
			line-height: 37rpx;
		}

		.hade_location_left {
			margin-left: 20rpx;

			.hade_location_left_top {
				display: flex;
				align-items: center;
				font-size: 32rpx;
				font-weight: 600;
				color: #313131;

				text {
					-webkit-line-clamp: 1; //设置几行
					display: -webkit-box; //设置为伸缩盒弹性盒子展示
					overflow: hidden; //超出隐藏
					text-overflow: ellipsis; //设置超出部分以省略号展示
					-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
				}

			}

			.hade_location_left_down {
				font-size: 24rpx;
				font-weight: 400;
				color: #666A6B;
				margin-top: 5rpx;
			}
		}

		.hade_location_right {
			// width: 180rpx;
			height: 63rpx;
			background: #F4F4F4;
			border-radius: 32rpx;
			margin-right: 20rpx;
			display: flex;
			align-items: center;
			justify-content: space-between;

			.hade_location_right_content {
				width: 90rpx;
				height: 63rpx;
				text-align: center;
				line-height: 63rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #949494;
			}

			.hade_location_right_content_activate {
				background-color: #05B6F6;
				color: #FDFEFF;
				border-radius: 32rpx;
			}
		}
	}

	.felx_right_box {
		width: 560rpx;
		height: 100%;
		background: #FAFAFA;

		.felx_right_boxscroll {
			padding: 23rpx 0 0 0;
			box-sizing: border-box;
		}


		.felx_right_box_conten {
			width: 522rpx;
			margin: 0 auto;

			.felx_right_box_conten_img {
				width: 525rpx;
				border-radius: 16rpx;
				overflow: hidden;
			}

			.felx_right_box_conten_title {
				font-size: 26rpx;
				font-weight: 600;
				color: #353535;
				padding: 30rpx 0 30rpx 10rpx;
			}
		}
	}

	.felx {
		display: flex;
		justify-content: space-between;
		overflow: hidden;

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