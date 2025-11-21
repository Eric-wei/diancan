<template>
	<view>
		<view class="hade">
			<!-- lineWidth="58px" -->
			<!-- 	<u-tabs :scrollable="false" :inactiveStyle="{width:'140rpx'}" lineWidth='58' lineHeight="6" :list="list1"
				lineColor="#333" :activeStyle="{ }" @click="tabclick"></u-tabs> -->

			<view class="hade_left flex jsa ac">
				<view class="hade_leftlines" @tap="index_select=0,store_listApi()"
					:style="{color:index_select==0?'#333':'', fontWeight: index_select==0?'bold':'' }">
					附近门店
					<view class="hade_leftline flex jc " v-if="index_select==0">
						<view class="hade_leftlinel">

						</view>
					</view>
				</view>
				<view class="hade_leftlines" @tap="index_select=1,store_listApi()"
					:style="{color:index_select==1?'#333':'', fontWeight: index_select==1?'bold':'' }">
					常去/收藏
					<view class="hade_leftline flex jc" v-if="index_select==1">
						<view class="hade_leftlinel ">

						</view>
					</view>
				</view>
			</view>
			<!-- 	<view class="hade_right" @tap="position_selection">
				<view>
					<u-icon name="search" color="#B6B6B6" size="40"></u-icon>
				</view>
				<view>
					搜索
				</view>
			</view> -->
		</view>
		<view class="content">
			<view class="contentinp">
				<!--  -->
				<u-search :showAction="true" v-model="search" placeholder="搜索门店" :searchIconSize='40' :height='60'
					@custom='searchall' @search='searchall' actionText="搜索" :animation="true"></u-search>
			</view>

			<view class="content_hade" @tap="position_selection">
				<view class="content_hade_1">
					<u-icon name="map" color="#5A5A5A " size="30"></u-icon>
				</view>
				<view class="content_hade_2">
					{{locationname}}
				</view>
				<view class="content_hade_3">
					<u-icon name="arrow-right" color="#5A5A5A " size="30"></u-icon>
				</view>
			</view>
			<view class="box">
				<map class="map" :style="{height: mapshow==0?'399rpx':'100rpx'}" :latitude="latitude"
					:longitude="longitude" scale='12' :show-location='true' :markers="covers" @markertap="markertap"
					@tap="tap" @updated="updated"></map>
				<view class="display" @tap="mapdisplay">
					<view class="display_text">
						<view class="display_text_content" v-if="mapshow==0">
							<text>收起地图 </text>
							<u-icon name="arrow-up" color="#252525" size="28"></u-icon>
						</view>
						<view class="display_text_content" v-if="mapshow==1">
							<text>展开地图 </text>
							<u-icon name="arrow-down" color="#252525" size="28"></u-icon>
						</view>
					</view>
				</view>
			</view>

			<view class="mt24" v-if="loading">
				<u-loading-icon size="72" text="加载中" textSize="36"></u-loading-icon>
			</view>
			<view style="margin-top: 150rpx;" v-if="shoplist.length<1&&!loading">
				<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无内容'></u-empty>
			</view>
			<view class="" v-if="!loading">


				<view class="position_box" :class="item.id==shop?'position_boxc':''" v-for="(item,index) in shoplist"
					:key="item">
					<view class="chosen_position">
						<view class="chosen_position_left">
							<view class="chosen_position_left_1">
								{{item.alias_title}}
							</view>
							<view class="chosen_position_left_4">
								<view class="chosen_position_left_41" v-if="item.is_business==1">营业中</view>
								<view class="chosen_position_left_41c" v-else>已歇业</view>
								<view class="chosen_position_left_42"
									v-if="item.subscribe_switch==1&&item.is_business==1">
									接受预定</view>
								<view class="chosen_position_left_43"
									v-if="item.takeaway_switch==1&&item.is_business==1">
									可外卖</view>
								<view class="chosen_position_left_44"
									v-if="item.delivery_switch==1&&item.is_business==1">
									可自提</view>
							</view>

							<view class="chosen_position_left_3">
								<u-icon name="clock" color="#5A5A5A " size="28"></u-icon>
								<text style="margin-left: 5rpx;"> {{item.start_times}}-{{item.end_times}}</text>
							</view>

							<view class="chosen_position_left_2">
								<u-icon name="map" color="#5A5A5A " size="28"></u-icon>
								<text style="margin-left: 5rpx;"> {{item.address}}</text>

							</view>

							<view class="chosen_position_left_4">
								<view class="chosen_position_left_41b" v-if="item.is_business==1"
									@tap="goplace(item.id,1)">
									去下单</view>
								<view class="chosen_position_left_41b" v-if="item.is_business==1"
									@tap="goplace(item.id,2)">
									去预约</view>
							</view>

						</view>
						<view class="chosen_position_right">
							<view class="chosen_position_right_1 flex jc"
								@tap="store_collectionApi(item,item.is_collection,index)">
								<u-icon v-if="item.is_collection||index_select==1" name="star-fill" color='#E40030'
									size="32"></u-icon>
								<u-icon v-else name="star" color='#313131' size="32"></u-icon>
							</view>
							<!-- <view class="chosen_position_right_1">
							<span @tap="goplace(item.id,1)">去下单</span>
							<span v-if="item.subscribe_switch==1&&item.is_business==1">/</span>
							<span @tap="goplace(item.id,2)"
								v-if="item.subscribe_switch==1&&item.is_business==1">去预约</span>
						</view> -->
							<view class="chosen_position_right_2">
								距离{{ item.distance}}km
							</view>
							<view class="chosen_position_right_3">
								<view class="chosen_position_right_3_1" @tap="openTel(item.tel)">
									<image :src="Httpimg+'store2.png'" mode="" v-if="Httpimg"></image>
								</view>
								<view class="chosen_position_right_3_2" @tap="getopenLocation(item.lat*1,item.lon*1)">
									<image :src="Httpimg+'store1.png'" mode="" v-if="Httpimg"></image>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>

		</view>
	</view>
</template>

<script>
	import config from '@/config.js'
	import img from "@/static/Project_drawing 10.png"
	import {
		store_list,
		store_collectionList,
		order_empty,
		store_cancelColl,
		store_collection
	} from "@/api/comm.js"
	import wApi from "@/utils/wxApi.js"
	import {
		forEach
	} from "lodash";
	export default {
		data() {
			return {
				loading: false,
				search: '',
				list1: [{
					name: '附近门店',
					index: 0
				}, {
					name: '常去/收藏',
					index: 1
				}],
				Httpimg: config.HttpImg,
				index_select: 0,
				mapshow: 0,
				title: '百度地图',
				latitude: 34.7586,
				longitude: 113.672307,
				covers: [], //标记点地图数据
				shoplist: [],
				location: {}, //当前页面展示的小地图经纬度
				locationname: '', //当前位置名字
				page: 1 //下拉加载
			};
		},
		onLoad() {
			this.store_listApi()
			let location = uni.getStorageSync('location')
			this.latitude = location.latitude
			this.longitude = location.longitude
			this.location = uni.getStorageSync('location')
		},
		computed: {
			shop() {
				return uni.getStorageSync('shop')
			}
		},
		methods: {
			searchall() {
				this.store_listApi()
			},
			async store_collectionApi(item, comtent, index, type) {
				if (!uni.getStorageSync('userinfo')) {
					this.enter = true
					uni.showToast({
						title: '未登录',
						icon: 'none'
					})
					return
				} else {
					if (comtent || this.index_select == 1) {
						let data = await store_cancelColl({
							store_id: item.id
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
							store_id: item.id
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
					if (this.index_select == 1) {
						this.shoplist.splice(index, 1)
					} else {
						if (comtent) {
							item.is_collection = 0;
						} else {
							item.is_collection = 1;
						}
						// this.store_listApi()
					}
				}
			},
			tabclick(e) {
				this.index_select = e.index;
				this.store_listApi()
				console.log(e)
			},
			init() {
				let that = this;
				//发起网络请求获取数据
				//用uni.request(OBJECT)方法
				//我这里模拟下数据
				var data = [{
					id: 1,
					name: '雷军',
					imgUrl: '../../static/Project_drawing 1.png',
					lat: "34.7586",
					lng: "113.672307"
				}, {
					id: 2,
					name: '李彦宏',
					imgUrl: '../../static/Project_drawing 2.png',
					lat: "34.763466",
					lng: "113.686285"
				}, {
					id: 3,
					name: '马化腾',
					imgUrl: '../../static/Project_drawing 3.png',
					lat: "34.763412",
					lng: "113.680185"
				}, ];
				that.MapData(that, data)
			},
			//地图数据初始化
			MapData(that, data) {
				let arrayData = [];
				for (var i = 0; i < data.length; i++) {
					arrayData.push({
						id: data[i].id, //marker点击事件回调会返回此id。建议为每个marker设置上Number类型id，保证更新marker时有更好的性能。
						latitude: data[i].lat, //纬度
						longitude: data[i].lng, //经度
						title: data[i].name, //点击时显示，callout存在时将被忽略
						iconPath: data[i].imgUrl, //项目目录下的图片路径，支持相对路径写法，以'/'开头则表示相对小程序根目录；也支持临时路径
						width: 60,
						height: 60,
						callout: {
							//自定义标记点上方的气泡窗口
							content: data[i].name,
							color: '', //文本颜色
							fontSize: 16, //文字大小
							borderRadius: 20, //callout边框圆角
							bgColor: '', //背景色
							padding: 6, //文本边缘留白
							display: 'BYCLICK', //'BYCLICK':点击显示; 'ALWAYS':常显
							textAlign: 'left', //文本对齐方式。有效值: left, right, center
						},
						label: {
							//为标记点旁边增加标签
							content: '', //标记点旁边的文字
							color: '#ff6600', //文本颜色
							fontSize: 16, //文字大小
							x: 0, //label的坐标，原点是 marker 对应的经纬度
							y: 0, //label的坐标，原点是 marker 对应的经纬度
							borderWidth: 1, //边框宽度
							borderColor: '', //边框颜色
							borderRadius: 10, //边框圆角
							bgColor: 'red',
							padding: 6, //	文本边缘留白
							textAlign: 'left', //文本对齐方式。有效值: left, right, center
						},
						anchor: {
							//经纬度在标注图标的锚点，默认底边中点      {x, y}，x表示横向(0-1)，y表示竖向(0-1)。{x: .5, y: 1} 表示底边中点
							x: .5,
							y: 1
						}
					});
				}
				//重新给地图数据赋值covers 
				that.covers = arrayData;
			},

			//地图点击事件
			markertap(e) {},
			//点击地图时触发; App-nuve、微信小程序2.9支持返回经纬度
			tap(e) {
				uni.showToast({
					title: '选中附近位置',
					icon: "success"
				})
				uni.setStorageSync('location', e.detail)
				this.store_listApi()
			},
			//在地图渲染更新完成时触发
			updated(e) {},
			mapdisplay() {
				if (this.mapshow) {
					this.mapshow = 0
				} else {
					this.mapshow = 1
				}
			},
			async store_listApi() {
				let that = this;
				// this.loading = true;
				if (this.index_select == 0) {
					let data = await store_list({
						page: 1,
						limit: 10,
						search: this.search
					})
					this.shoplist = []
					this.locationname = data.data.address
					if (!data?.data?.rows?.length) return
					data?.data?.rows?.forEach(res => {
						res.distance = wApi.mkm(res.distance)
					})
					this.shoplist.push(...data.data.rows)
				} else {
					let data = await store_collectionList({
						page: 1,
						limit: 10,
						search: this.search
					})
					this.shoplist = []
					if (data.data) {
						data.data.rows.forEach(res => {
							res.distance = wApi.mkm(res.distance)
						})
						this.locationname = data.data.address
						this.shoplist.push(...data.data.rows)
					}
				}
				setTimeout(() => {
					that.loading = false;
				}, 500)
			},
			async store_collectionListApi() {
				let data = await store_collectionList({
					page: '',
					limit: '',
				})
			},
			async position_selection() {
				let data = await wApi.getchooseLocation()
				uni.setStorageSync('location', data)
				this.store_listApi()
			},
			goplace(id, e) {
				uni.setStorageSync('shop', id)
				if (e == 1) {
					order_empty()
					uni.reLaunch({
						url: '/pages/order/order'
					})
				} else {
					uni.reLaunch({
						url: '/pages/reserved/reserved'
					})
				}

			},
			// 下拉加载
			async store_listApi_down() {
				if (this.index_select == 0) {
					let data = await store_list({
						page: this.page,
						limit: 10,
						search: ''
					})
					data.data.rows.forEach(res => {
						res.distance = wApi.mkm(res.distance)
					})
					this.locationname = data.data.address
					this.shoplist.push(...data.data.rows)
				} else {
					let data = await store_collectionList({
						page: this.page,
						limit: 10,
						search: ''
					})
					if (data.data) {
						data.data.rows.forEach(res => {
							res.distance = wApi.mkm(res.distance)
						})
						this.locationname = data.data.address
						this.shoplist.push(...data.data.rows)
					}
				}
			},
		},
		onReachBottom() {
			this.page++
			this.store_listApi_down()
		}
	}
</script>

<style lang="scss">
	.hade {
		// display: flex;
		// align-items: center;
		// justify-content: space-between;
		background-color: #fff;
		padding: 20rpx 0;
		width: 750rpx;



		.hade_left {
			display: flex;
			align-items: center;
			font-size: 34rpx;
			font-weight: 400;
			color: #aaa;

			.hade_leftlines {
				position: relative;
				width: 220rpx;
				text-align: center;

				.hade_leftline {
					position: absolute;
					bottom: -20rpx;
					width: 220rpx;
				}

				.hade_leftlinel {
					width: 58rpx;
					height: 6rpx;
					background: #E40030;
					border-radius: 4rpx;
				}
			}



		}

		.hade_right {
			width: 141rpx;
			height: 66rpx;
			background: #F7F7F7;
			border-radius: 33rpx;
			display: flex;
			align-items: center;
			text-align: center;
			font-size: 28rpx;
			font-weight: 400;
			color: #7A7A7A;
			margin-right: 20rpx;

			view:nth-child(1) {
				margin-left: 10rpx;
			}
		}
	}

	.content {
		// width: 704rpx;
		margin: 0 auto;

		.contentinp {
			padding: 30rpx 30rpx 0 30rpx;
			box-sizing: border-box;
			background: #fff;
		}

		.content_hade {
			display: flex;
			align-items: center;
			padding: 30rpx;
			box-sizing: border-box;
			background: #fff;

			.content_hade_2 {
				font-size: 28rpx;
				font-weight: 400;
				color: #373737;
				padding: 0 10rpx;
			}
		}

		.map {
			width: 100%;
			height: 399rpx;
		}

		.display {
			height: 67rpx;
			background: #FFFFFF;
			border-radius: 0rpx 0rpx 6rpx 6rpx;

			.display_text {
				width: 150rpx;
				margin: 0 auto;

				.display_text_content {
					display: flex;
					align-items: center;
					padding: 15rpx 0;
					font-size: 24rpx;
					font-weight: 400;
					color: #373737;

					text {
						margin-right: 10rpx;
					}
				}
			}
		}

		.position_boxc {
			border: 2rpx solid #E40030 !important;
		}

		.position_box {
			width: 704rpx;
			// height: 273rpx;
			background: #FFFFFF;
			border-radius: 20rpx;
			margin: auto;
			margin-top: 20rpx;

			.chosen_position {
				padding: 24rpx 20rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;

				.chosen_position_left {
					width: 70%;

					.chosen_position_left_1 {
						font-size: 32rpx;
						font-weight: 600;
						color: #060606;
					}

					.chosen_position_left_2 {
						display: flex;
						font-size: 24rpx;
						font-weight: 400;
						color: #676767;
						margin-top: 15rpx;
					}

					.chosen_position_left_3 {
						display: flex;
						font-size: 24rpx;
						font-weight: 400;
						color: #676767;
						margin-top: 15rpx;

					}

					.chosen_position_left_4 {
						display: flex;
						align-items: center;
						margin-top: 15rpx;




						.chosen_position_left_41b {
							background: #E40030;
							border-radius: 8rpx;
							text-align: center;
							font-size: 24rpx;
							font-weight: 400;
							color: #F3F8FF;
							margin-right: 10rpx;
							padding: 8rpx 20rpx;
							box-sizing: border-box;
						}

						.chosen_position_left_41 {
							background: #E40030;
							border-radius: 4rpx;
							text-align: center;
							font-size: 24rpx;
							font-weight: 400;
							color: #F3F8FF;
							margin-right: 10rpx;
							padding: 8rpx 10rpx;
							box-sizing: border-box;
						}

						.chosen_position_left_41c {
							background: #AAAAAA;
							border-radius: 4rpx;
							text-align: center;
							font-size: 24rpx;
							font-weight: 400;
							color: #F3F8FF;
							margin-right: 10rpx;
							padding: 8rpx 10rpx;
							box-sizing: border-box;
						}

						.chosen_position_left_42 {

							border-radius: 4rpx;
							border: 2rpx solid #E40030;
							font-size: 24rpx;
							font-weight: 400;
							color: #E40030;
							text-align: center;
							box-sizing: border-box;
							padding: 8rpx 10rpx;
							margin-right: 10rpx;
							box-sizing: border-box;
						}

						.chosen_position_left_43 {

							border-radius: 4rpx;
							border: 2rpx solid #E40030;
							font-size: 24rpx;
							font-weight: 400;
							color: #E40030;
							text-align: center;
							box-sizing: border-box;
							padding: 8rpx 10rpx;
							margin-right: 10rpx;

							box-sizing: border-box;
						}

						.chosen_position_left_44 {

							border-radius: 4rpx;
							border: 2rpx solid #E40030;
							font-size: 24rpx;
							font-weight: 400;
							color: #E40030;
							text-align: center;
							box-sizing: border-box;
							padding: 8rpx 10rpx;
							margin-right: 10rpx;

							box-sizing: border-box;
						}
					}
				}

				.chosen_position_right {
					width: 180rpx;
					text-align: center;
					// border-left: 1rpx solid #F0F0F0;
					padding-left: 20rpx;

					.chosen_position_right_1 {
						font-size: 26rpx;
						font-weight: 400;
						color: #05B6F6;
						padding: 20rpx 0;
					}

					.chosen_position_right_2 {
						font-size: 24rpx;
						font-weight: 400;
						color: #666666;
						margin-bottom: 20rpx;
					}

					.chosen_position_right_3 {
						display: flex;
						align-items: center;
						justify-content: space-between;

						.chosen_position_right_3_1 {
							width: 66rpx;
							height: 66rpx;
						}

						.chosen_position_right_3_2 {
							width: 66rpx;
							height: 66rpx;
						}
					}
				}
			}
		}
	}
</style>