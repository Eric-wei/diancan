<template>
	<view v-show="display">
		<view class="">
			<u-swiper :height="480" :list="list1"></u-swiper>
		</view>
		<view class="content">
			<view class="content_1">
				<text class="content_1_title">{{contentlist.title}}
				</text>
				<view class="content_1_label">
					<!-- <text>1人份</text>
					<text>咸鲜</text> -->
				</view>
				<view class="content_1_sales">
					月售{{contentlist.count}}+
				</view>



			</view>
			<!-- 	<view class="content_2">
				<view class="content_2_title">
					详情
				</view>
				
			</view> -->
			<view class="specification">
				<view class="specification_title_1">
					<view scroll-y="true" style="height: 100%;">
						<view v-for="(itemall,indexall) in specification_list" :key="itemall">
							<!-- <height :hg='30'></height> -->
							<text class="specification_title_1_title">{{itemall.name}}</text>
							<!-- 多规格 -->
							<view class="" v-if="order_type==2">
								<view class="specification_title_1_content">
									<view
										:class="{'specification_title_1_content_flex':true,'specification_title_1_content_flex_activate':style[indexall].id==item.id}"
										v-for="(item,index) in itemall.item" :key="item"
										@tap="selective_specification(indexall,item.id,item.name)">
										{{item.name}}
									</view>
								</view>
							</view>

							<view class="flexs flexw d_box" v-else>
								<view class="flexc flexs d_box_item" v-for="(item,index) in itemall.item" :key="item">
									<view class="d_box_itemt">
										{{item.name}}
									</view>
									<view class="">
										<u-number-box min='0' v-model="item.value" :name='item.id' @change='focus'>
											<view slot="minus" class="minus flex jc ac">
												<!-- <image class="caricon" :src="Httpimg+'ccar.png'" mode=""></image> -->
												<u-icon name="minus" size="24"></u-icon>
											</view>
											<text slot="input" class="input">{{item.value||0}}</text>
											<view slot="plus" class="plus flex jc ac">
												<!-- <image class="caricon" :src="Httpimg+'addcar.png'" mode=""></image> -->
												<u-icon name="plus" color="#fff" size="24"></u-icon>
											</view>
										</u-number-box>
									</view>

								</view>
							</view>

						</view>
					</view>
				</view>


			</view>
			<view class="content_1">
				<text class="content_1_title">
					商品详情
				</text>
				<view class="content_2_textContainer" :class="isexpand?'content_2_textContainerc':''">
					<u-parse :content="contentlist.json"></u-parse>
					<!-- 	<view class="content_2_text" v-html="contentlist.json">
				</view> -->
				</view>
				<view class="expandBut flex jc ac" @click="isexpand=!isexpand">
					展开全部
					<u-icon name="arrow-down" color="#AAAAAA " size="28" v-if="!isexpand"></u-icon>
					<u-icon name="arrow-up" color="#AAAAAA " size="28" v-if="isexpand"></u-icon>
				</view>

			</view>



		</view>

		<view class="payCar">
			<view class="payCart flex jsb ac">
				<view class="payCartl">
					<view class="flex ae">
						<text c class="payCartlpriceIcon">¥</text>
						<view class="payCartlprice">
							{{(orderprive*1).toFixed(2)}}
						</view>
						<!-- <text class="lineprice">
							{{'16'}}</text> -->
					</view>
					<view class="activeSku" v-if="order_type==2">
						{{funjointext(style)}}
						<!-- <text v-for="(item,index) in " :key="item">{{item.name +' 、'}}</text> -->
					</view>
					<view class="activeSku" v-else>
						<template v-for="(item,index) in spu_info">
							<text v-if="item.count>0">{{item.name}}x{{item.count}}</text>
						</template>
					</view>

				</view>
				<view class="payCarCount">
					<!-- v-model="counts" @change='changeCounts' -->
					<u-number-box>
						<view slot="minus" class="minus flex jc ac" @click="changeCounts('del')">
							<!-- <image class="caricon" :src="Httpimg+'ccar.png'" mode=""></image> -->
							<u-icon name="minus" size="24"></u-icon>
						</view>
						<text slot="input" style="width: 50px;text-align: center;" class="input">{{counts}}</text>
						<view slot="plus" class="plus flex jc ac" @click="changeCounts('add')">
							<!-- <image class="caricon" :src="Httpimg+'addcar.png'" mode=""></image> -->
							<u-icon name="plus" color="#FFFFFF" size="24"></u-icon>
						</view>
					</u-number-box>
				</view>
			</view>
			<view class="enterBut flex ac jsb ">
				<view class="enterButl flex jc ac" @click="instantlyPay">
					立即{{addAdish?'加菜':'购买'}}
				</view>
				<view class="enterButr  flex jc ac" @click="contentlist.sku_count>0?select(1):add_joinCar(1)">
					加入购物车
				</view>
			</view>
		</view>
		<height :hg='275'></height>
		<view class="settle" v-show="false">
			<close ref="shoppingcar" :shopping_trolley_list='shopping_trolley_list' @update='order_carListApi'
				@register='reg' :addAdish='addAdish' :seatShow='seatShow'></close>
		</view>
		<specification ref='specification' :store_id='store_id' :activate_data='activate_data' :order_id='order_id'
			:type='order_type' @order='order_carListApi' :show='show_specification' @close='show_specification=false'
			@login='enter=true'>
		</specification>




		<login :show="enter" @closepage='enter=false'></login>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		store_goodsDetail,
		store_item,
		store_itemPrice,
		order_joinCar,
		order_carList,
	} from "@/api/comm.js"
	import Location from "@/utils/wxApi.js"
	import {
		buyNow
	} from "@/api/user.js"


	export default {
		data() {
			return {
				Httpimg: config.HttpImg,
				counts: 1, //总数量
				isexpand: false, //是否展开
				enter: false,
				addAdish: false,
				list1: [],
				store_id: '',
				orderid: '', //商品id
				contentlist: {}, //页面数据
				// 规格参数的弹出层
				show_specification: false,
				specification_list: [], //规格参数
				order_id: '', //当前规格商品id
				style: [], //规格动态样式
				orderprive: 0, //规格价格计算
				shopping_trolley_list: [], //购物车列表
				display: false,
				register: false,
				activate_data: '',
				seatShow: true,
				addAdish: false,
				order_type: "",
				spu_info: [],
			};
		},
		onLoad(obj) {
			this.order_id = obj.id
			this.store_id = obj.store_id
			this.activate_data = obj.activate_data
			this.store_goodsDetailApi(obj.id)

			let add = uni.getStorageSync('addAdish') //加菜还是结算
			let option = uni.getStorageSync('option')
			if (option == 3) {
				this.seatShow = false
				if (add) {
					this.addAdish = add
				}
			}
			if (uni.getStorageSync('userinfo')) {
				this.order_carListApi()
				// setTimeout(() => {
				// 	this.specification()
				// }, 1500)

			}
		},
		computed: {

			shopid() {
				return uni.getStorageSync('shop')
			},
			public() {
				return uni.getStorageSync('public')
			}
		},
		watch: {
			orderprive(newval, oldval) {
				deep: true // 开启深度监听 专门对付复杂数据类型
				immediate: true // 首次监听 一打开页面就想监听
				this.count = 1;
				this.spu_info.forEach(item => {
					this.count = this.count + item.count
				})
			}
		},
		methods: {
			//立即购买
			instantlyPay() {
				if (this.contentlist.sku_count > 0) {
					this.select(2);
				} else {
					this.add_joinCar(2);
				}
				setTimeout(() => {
					if (this.addAdish) {
						this.$refs.shoppingcar.jiacai(2)
					} else {
						this.$refs.shoppingcar.gojs(2)
					}
				}, 300)
			},
			changeCounts(type) {
				if (type == 'del') {
					this.counts--;
					if (this.counts <= 0) {
						this.counts = 1
					}
				} else {
					this.counts++;
				}
				this.store_itemPriceApi()
				// add

			},
			focus(e) {

				let name;
				this.specification_list.forEach(item => {
					item.item.forEach(item1 => {
						if (item1.id == e.name) {
							name = item1.name
						}
					})
				})

				if (this.spu_info.length > 0) {
					this.spu_info.forEach(item => {
						if (item.spu_id == e.name) {
							item.count = e.value
						}
					})
				} else {
					this.spu_info.push({
						spu_id: e.name,
						count: e.value,
						name: name
					})
				}
				this.$forceUpdate()
				this.store_itemPriceApi()
			},
			funjointext(arr) {

				let text = null;
				if (arr && arr.length) {
					let arrs = arr.map(item => item.name);
					text = arrs.join('、')
				}
				return text;
			},
			// 添加购物车
			async store_goodsDetailApi(id) {
				let data = await store_goodsDetail({
					goods_id: id
				})
				if (data.code == 1) {
					this.list1 = []
					this.list1.push(...data.data.images)
					this.contentlist = data.data
					this.display = true
					this.order_type = data.data.type
					uni.setNavigationBarTitle({
						title: this.contentlist.title //这是修改后的导航栏文字
					})
					this.store_itemApi(id)
				}

			},
			async store_itemApi(id) {
				let data = await store_item({
					goods_id: id
				})
				this.style = []
				this.spu_info = []
				if (this.order_type == 2) {
					data.data.forEach(res => {
						this.style.push({
							id: res.item[0].id,
							name: res.item[0].name
						})
					})
				} else {
					data.data.forEach(res => {
						res.item.forEach(item1 => {
							item1.value = 0
							this.spu_info.push({
								spu_id: item1.id,
								count: 0,
								name: item1.name
							})
						})
					})
				}
				this.specification_list = []
				this.specification_list.push(...data.data)
				this.store_itemPriceApi()
				// this.style = [];
				// data.data.forEach(res => {
				// 	this.style.push({
				// 		id: res.item[0].id,
				// 		name: res.item[0].name
				// 	})
				// })
				// this.specification_list = []
				// this.specification_list.push(...data.data)
				// this.store_itemPriceApi()
			},

			async selective_specification(index, id, name) {

				this.$set(this.style[index], 'id', id)
				this.$set(this.style[index], 'name', name)
				this.store_itemPriceApi()
			},
			// 价格计算
			async store_itemPriceApi() {
				let that = this;
				console.log(that.counts, '总数量')
				// return
				let str = ''
				let spu = []
				if (this.order_type == 2) {
					this.style.forEach((res, index) => {
						// if (index == (this.style.length - 1)) {
						// 	str += res.id
						// } else {
						str += res.id + '_'
						// }
					})
					str = str.substring(0, str.length - 1)
				} else {
					that.spu_info.forEach(item => {
						if (item.count > 0) {
							spu.push({
								count: item.count,
								spu_id: item.spu_id
							})
						}
					})
				}

				let data = await store_itemPrice({
					goods_id: that.order_id,
					spu_id: str,
					spu_info: spu,
					count: that.counts
				})
				if (data.code == 1) {
					let num = data.data.price
					that.orderprive = num
				}

			},
			// 添加购物车
			async add_joinCar(ctype) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					return
				}
				let str = ''
				this.style.forEach(res => {
					str += res.id + '_'
				})
				str = str.substring(0, str.length - 1)
				let data = await order_joinCar({
					store_id: this.store_id,
					goods_id: this.order_id,
					spu_id: str,
					count: this.counts,
					order_type: this.activate_data,
					c_type: ctype || 1
				})
				if (data.code == 1) {
					// if (!ctype) {
					uni.showToast({
						title: data.msg,
						icon: "none",
						duration: 3000
					})
					// }
					this.order_carListApi()
					this.show_specification = false
				} else {
					uni.showToast({
						title: data.msg,
						icon: "error"
					})
				}
			},
			// 购物车列表
			async order_carListApi() {
				let peopleCount = uni.getStorageSync('peopleCount') || 0
				let type;
				if (this.addAdish) {
					type = 2
				} else {
					type = 1
				}
				let data = await order_carList({
					peopleCount: peopleCount,
					type,
					store_id: this.shopid
				})

				this.shopping_trolley_list = data.data
			},

			async select(ctype) {
				console.log('类型') //类型
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true;
					return
				}
				let str = ''
				let spu = []
				if (this.order_type == 2) {
					// 多规格
					this.style.forEach((res, index) => {
						if (index == (this.style.length - 1)) {
							str += res.id
						} else {
							str += res.id + '_'
						}
					})
				} else {
					// 单规格	
					this.spu_info.forEach(item => {
						if (item.count > 0) {
							spu.push({
								count: item.count,
								spu_id: item.spu_id
							})
						}
					})
				}
				let data = await order_joinCar({
					store_id: this.store_id,
					goods_id: this.order_id,
					spu_id: str,
					count: this.counts,
					order_type: this.activate_data,
					spu_info: spu,
					c_type: ctype || 1
				})
				if (data.code == 1) {
					setTimeout(() => {
						this.$emit('order')
					}, 500)
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
					if (!ctype) {

					}
					// this.spu_info = []
					// this.style = []
					// this.close()
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}


			},

			reg() {
				if (!uni.getStorageSync('userinfo')) {
					this.register = true
					uni.showToast({
						title: '未登录',
						icon: 'none'
					})
					return
				}
				this.show_specification = true
			},
			closepage() {
				this.register = false
			}
		}
	}
</script>
<style>
	page {
		background: #fff;
	}
</style>
<style lang="scss" scoped>
	.expandBut {
		font-size: 24rpx;
		color: #AAAAAA;
		margin-top: 24rpx;
	}


	.d_box {
		.d_box_item {
			font-size: 34rpx;
			color: #3d3d3d;
			width: 45%;
			margin-bottom: 20rpx;
		}
	}



	.enterBut {
		width: 100%;
		margin-top: 24rpx;

		.enterButl {
			width: 334rpx;
			height: 80rpx;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			border: 2rpx solid #777777;
			font-size: 30rpx;
			color: #333333;
			font-weight: bold;
		}

		.enterButr {
			width: 334rpx;
			height: 80rpx;
			background: #E40030;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			font-size: 30rpx;
			color: #fff;
			font-weight: bold;
		}
	}


	.caricon {
		width: 48rpx;
		height: 48rpx;
	}

	.minus {
		width: 44rpx;
		height: 44rpx;
		border-width: 1px;
		border-color: #333;
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
		padding: 0 10px;
		box-sizing: border-box;
		font-size: 40rpx;
		font-weight: bold;
	}

	.plus {
		width: 48rpx;
		height: 48rpx;
		background-color: #333;
		border-radius: 50%;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: center;
		align-items: center;
	}

	.payCar {
		width: 100%;
		background: #fff;
		padding: 24rpx 32rpx 40rpx 32rpx;
		box-sizing: border-box;
		background: #fff;
		position: fixed;
		bottom: 0rpx;
		left: 0;

		.payCart {
			// padding: 24rpx 0;
			box-sizing: border-box;
		}

		.payCartl {
			line-height: 1;
		}

		.payCartlprice {
			font-size: 42rpx;
			font-weight: bold;

			.payCartlpriceIcon {
				font-size: 28rpx !important;
			}
		}

		.lineprice {
			text-decoration: line-through;
			font-size: 24rpx;
			color: #AAAAAA;
			margin-left: 5rpx;
		}

		.activeSku {
			font-size: 24rpx;
			color: #777777;
			margin-top: 12rpx;
		}

	}


	.specification_title_1 {
		width: 100%;
		// height: 504rpx;
		margin: 0 auto;

		.specification_title_1_title {
			font-size: 32rpx;
			font-weight: 600;
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
				background: #E40030 !important;
				border: 1rpx solid #333 !important;
				color: #FFF !important;
			}

			.font_sizi_1 {
				color: #00B8FB;
			}

			.font_sizi_2 {
				border-left: 1rpx solid #00B8FB;
			}

			.specification_title_1_content_flex {
				height: 63rpx;
				background: #FAFAFA;
				border-radius: 8rpx;
				border: 1rpx solid #FAFAFA;
				margin-right: 20rpx;
				text-align: center;
				line-height: 63rpx;
				font-size: 24rpx;
				font-weight: 400;
				color: #333;
				padding: 0 40rpx;
			}
		}
	}

	.content_1 {
		background-color: #FFFFFF;
		padding: 30rpx;

		.content_1_title {
			font-size: 34rpx;
			color: #333333;
			font-weight: bold;
		}

		.content_1_label {
			margin-top: 20rpx;

			text {
				background-color: #F5F5F5;
				font-size: 24rpx;
				font-weight: 400;
				color: #353535;
				padding: 5rpx 10rpx;
				margin-right: 10rpx;
			}
		}

		.content_1_sales {
			font-size: 24rpx;
			font-weight: 400;
			color: #8A8A8A;
			margin-top: 20rpx;
		}

		.content_1_price {
			display: flex;
			align-items: center;
			justify-content: space-between;

			view:nth-child(1) {
				font-size: 34rpx;
				font-weight: 600;
				color: #FC4F46;
			}

			view:nth-child(2) {
				width: 209rpx;
				height: 61rpx;
				background: #05B6F6;
				border-radius: 13rpx;
				font-size: 28rpx;
				font-weight: 500;
				color: #FFFFFF;
				line-height: 61rpx;
				text-align: center;
				margin-top: 20rpx;
			}
		}
	}

	.content_2 {
		background: #FFFFFF;
		margin-top: 30rpx;
		padding: 30rpx 0;

		.content_2_title {
			font-size: 32rpx;
			font-weight: 600;
			color: #353535;
			padding: 0 30rpx;
			padding-bottom: 30rpx;
			border-bottom: 1rpx solid #ECECEC;
		}


	}

	.content_2_textContainer {
		height: 80rpx;
		box-sizing: border-box;
		overflow: hidden;
		margin-top: 30rpx;

		.content_2_text {
			// min-height: 300rpx;
			height: auto !important;
			padding: 20rpx 0rpx;
		}
	}

	.content_2_textContainerc {
		height: auto !important;
	}



	.settle {
		position: fixed;
		bottom: 50rpx;
	}

	.specification {
		width: 100%;
		background: #FFFFFF;
		border-radius: 12rpx;
		padding: 0rpx 32rpx 24rpx 32rpx;
		box-sizing: border-box;
		// position: relative;
		// z-index: 10074;

		.specification_title {
			text-align: center;
			font-size: 32rpx;
			font-weight: 600;
			color: #353535;
			margin: 0 auto;
		}

		.specification_title_1 {
			width: 100%;
			// height: 504rpx;
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
				align-items: center;
				overflow-x: auto;
				display: -webkit-box;

				.specification_title_1_content_flex_activate {
					background: #E40030 !important;
					border: 1rpx solid #333 !important;
					color: #fff !important;
				}

				.font_sizi_1 {
					color: #00B8FB;
				}

				.font_sizi_2 {
					border-left: 1rpx solid #00B8FB;
				}

				.specification_title_1_content_flex {
					height: 63rpx;
					background: #FAFAFA;
					border-radius: 8rpx;
					border: 1rpx solid #FAFAFA;
					display: flex;
					align-items: center;
					justify-content: space-between;
					margin-right: 20rpx;
					color: #333;

					view:nth-child(1) {
						font-size: 24rpx;
						font-weight: 400;
						color: #343434;
						line-height: 63rpx;
						text-align: center;
						padding: 0 10rpx;
					}

					view:nth-child(2) {
						font-size: 24rpx;
						font-weight: 600;
						color: #F65329;
						line-height: 63rpx;
						border-left: 1rpx solid #F5F5F5;
						text-align: center;
						padding: 0 10rpx;
					}
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
		position: absolute;
		bottom: 150rpx;
		right: 0;
		z-index: 999;

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