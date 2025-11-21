<template>
	<view v-show="display">
		<view class="hade_location">
			<!-- 店铺名称 -->
			<view class="hade_location_left" @tap="routerTo('/pages/order_all/selectstore/selectstore')">
				<view class="hade_location_left_top">
					<text>{{store_default_data.alias_title || ''}}</text>
					<u-icon name="arrow-right" color='#313131' size="24rpx"></u-icon>
				</view>
				<view class="hade_location_left_down">
					{{store_default_data.desc || ''}}
				</view>
			</view>

		</view>

		<view class="felx">
			<view class="" style="	background: #F4F8F9;">
				<orderList2 @aid_mgs='aid_mgs' :index='components_index' :content='seatList'></orderList2>
			</view>
			<view class="felx_right_box">
				<scroll-view scroll-y="true" style="height: 100%;">
					<view class="felx_right_box_conten">
						<view class="felx_right_box_conten_img">
							<image :src="list1" mode="aspectFill"
								style="width: 100%;height: 251rpx;border-radius: 12rpx;" @click="swpclick(list1url)">
							</image>
							<!-- <u-swiper :list="list1" :height='251' ></u-swiper> -->
						</view>
						<view class="felx_right_box_conten_title">
							{{seatList[childindex].type || ''}}
						</view>
						<view style="margin-top: 150rpx;" v-if="orderlist.length<1">
							<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
								text='暂无此类餐饮'></u-empty>
						</view>

						<ordercard2 :content='orderlist' :store_id='store_default_data.id'
							:activate_data='activate_data'></ordercard2>
					</view>
					<height :hg='70'></height>
				</scroll-view>
			</view>
		</view>
		<u-modal :show="modelShow" :title="title" :content='content' :showCancelButton='true'
			:closeOnClickOverlay='true' @cancel='cancel' @confirm='confirm' :confirmText='"去切换"'></u-modal>

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
		seat,
		seat_list,
		reservation,
		cancel_reservation
	} from "@/api/comm.js"
	import {
		forEach
	} from "lodash";
	import {
		cellphone
	} from "@/utils/type_height.js"
	export default {
		data() {
			return {
				modelShow: false,
				content: '当前门店不可预约，是否切换门店',
				title: '温馨提示',
				// 胶囊开关
				activate_data: 2,
				// 领卷
				counp_show: true,
				list1: '',
				list1url: '',
				childindex: 0, //侧边栏子组件传过来的索引
				store_default_data: {}, //默认门店
				orderlist: [], //此分类下的商品
				store_id: '', //选择门店后使用选择的门店
				style: [], //规格动态样式
				display: false, //页面数据未加载前隐藏
				System_height: cellphone(), //系统高度
				components_index: 0, //刷新组件索引
				scene: null,
				seatList: []
			};

		},
		onLoad(e) {
			this.getlocation() //模糊定位
			this.store_defaultApi() //默认门店
		},
		onShow() {
			this.store_defaultApi()
			let option = uni.getStorageSync('option');
			this.activate_data = option ? option : 1
		},
		methods: {
			cancel() {
				uni.navigateBack()
			},
			confirm() {
				uni.redirectTo({
					url: '/pages/order_all/selectstore/selectstore'
				})
			},
			async getseat_list(id) {
				let res = await seat_list({
					store_id: id
				})
				if (res.code == 1) {
					this.seatList = res.data
					this.orderlist = res.data[this.childindex].data
				}
			},



			aid_mgs(e, index) {
				this.childindex = index
				this.seatList.forEach(item => {
					if (item.type == e) {
						this.orderlist = item.data
					}
				})
			},
			swpclick(url) {
				console.log(url)
				if (url) {
					uni.navigateTo({
						url: url
					})
				}
				// return
			},
			async getlocation() {
				uni.getLocation({
					success(data) {
						uni.setStorageSync('location', data)
					}
				})
			},
			async store_defaultApi() {
				let shop = uni.getStorageSync('shop')
				let data = await store_default({
					id: shop || ''
				})
				if (data.code == 1) {
					if (data.data.subscribe_switch == 0) {
						this.modelShow = true
					}
					this.store_default_data = data.data
					this.list1 = data.data.image;
					this.list1url = data.data.tz_url;
					this.getseat_list(data.data.id)
					this.display = true
				}

			},


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

	.movableView {
		pointer-events: auto; //可以点击
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
		margin: 0 0 10rpx;

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
			width: 180rpx;
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

	.felx {
		display: flex;
		justify-content: space-between;

		.felx_right_box {
			width: 571rpx;
			height: 88vh;

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
			bottom: -120rpx;
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