<template>
	<view class="" v-if="pageShow">
		<view>
			<view class="">
				<u-swiper :height="563" :list="list1"></u-swiper>
			</view>
			<view class="content">
				<view class="content_1 flexc flexs">
					<view class="">
						<text class="content_1_title">{{contentlist.title || ''}}
						</text>
						<view class="content_1_sales">
							桌号：{{contentlist.no || ''}}
						</view>
					</view>

					<view class="content_1_price" v-if="contentlist.deposit!=='0.00'">
						<view class="">
							预约金：¥{{contentlist.deposit || ''}}
						</view>
					</view>
				</view>
				<view class="content_2">
					<view class="content_2_title">
						预约时间
					</view>
					<view class="content_2_content">
						<scroll-view scroll-x="true" style="height: 100rpx;width:100%;white-space: nowrap;">
							<view class="content_2_content_Title_box">
								<view class="content_2_content_Title" :class="scrollIdx==index?'active':''"
									v-for="(item,index) in contentlist.day" :key="item.day"
									@click="setscrollIdx(index)">
									{{item.day}}
								</view>
							</view>
						</scroll-view>
						<view class=" content_2_content_item_box flexc flexw">
							<view @click="setTimeDate(item1.time_date,item1.status,item1.time)"
								class="content_2_content_item" v-for="item1 in contentlist.day[scrollIdx].time"
								:class="item1.status==1?'content_2_content_item_status':(yytime==item1.time_date?'content_2_content_item_active':'')">
								{{item1.tag}}{{item1.time}}
							</view>
						</view>


					</view>

					<height :hg='80'></height>
				</view>

			</view>
			<view class="settle">
				<close2 :timeDate='timeDate' :shopping_trolley_list='contentlist' :subscribe_id='order_id'
					:subscribe_time='yytime' :price='contentlist.deposit' :pageurl='pageurl' @confrim='close2confrim'>
				</close2>
			</view>
			<paypopup :sign='true' @confirm='payConfrim' :orderNo='orderNo' :order='order' :pageurl='pageurl'
				:popShow='popShow' @close='popShow=false'>
			</paypopup>

		</view>
	</view>
</template>

<script>
	import {
		store_goodsDetail,
		store_item,
		store_itemPrice,
		order_joinCar,
		order_carList,
		seat,
		reservation,

	} from "@/api/comm.js"

	import Location from "@/utils/wxApi.js"
	export default {
		data() {
			return {
				enter: false,
				pageurl: "/pages/order_form/order_form?idx=3",
				orderNo: "",
				popShow: false,
				timeDate: null,
				pageShow: false,
				list1: [],
				store_id: '',
				orderid: '', //商品id
				contentlist: {}, //页面数据
				// 规格参数的弹出层
				show_specification: false,
				display: false,
				register: false,
				detail: {},
				show: false,
				columns: [
					[],
					[]
				],
				yytime: null,
				yyday: null,
				scrollIdx: 0,
				order: false,
				obj: {},
				order_id: ''
			};
		},
		onLoad(obj) {
			this.order_id = obj.id
			this.getseat(obj.id)
		},
		methods: {
			async payConfrim() {
				let res = await reservation(this.obj)
				if (res.code == 1) {
					this.orderNo = res.data.orderNo
					this.order = true
				}
			},
			close2confrim(obj) {
				this.obj = obj
				this.popShow = true
			},
			setscrollIdx(idx) {
				this.scrollIdx = idx
			},
			setTimeDate(e, status, time) {
				if (status !== 0) return
				this.yytime = e
				this.timeDate = this.contentlist.day[this.scrollIdx].day + ' ' + time
				this.$forceUpdate()
			},


			async getseat(id) {
				let res = await seat({
					id
				})
				if (res.code == 1) {
					this.contentlist = res.data
					this.list1.push(res.data.img)
					this.contentlist.day.forEach(item => {
						this.columns[0].push(item.day)
					})
					this.contentlist.day[0].time.forEach(item => {
						this.columns[1].push(item.time)
					})
					this.pageShow = true
					uni.setNavigationBarTitle({
						title: this.contentlist.title
					})
				}

			},


			select() {
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

<style lang="scss" scoped>
	.content_1 {
		background-color: #FFFFFF;
		padding: 30rpx;

		.content_1_title {
			font-size: 32rpx;
			font-weight: 500;
			color: #353535;
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
			margin-top: 10rpx;

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

		.content_2_text {
			min-height: 300rpx;
			height: auto !important;
			padding: 20rpx 20rpx;
		}

		.content_2_content {
			// padding: 0rpx 30rpx;

			.content_2_content_Title {
				display: inline-block;
				background-color: #f8f8f8;
				// width: 150rpx !important;
				text-align: center;
				// line-height: 80rpx;
				font-size: 28rpx;
				margin: 20rpx;
				padding: 16rpx 24rpx;
				box-sizing: border-box;
				border-radius: 8rpx;

			}

			.active {
				background-color: #333;
				color: #fff !important;
			}

			.content_2_content_item {
				border: 1rpx solid #585858;
				margin: 10rpx 9rpx 10rpx;
				text-align: center;
				font-size: 24rpx;
				border-radius: 8rpx;
				padding: 10rpx 22rpx;
				box-sizing: border-box;
			}

			.content_2_content_item_box {
				margin-top: 30rpx;
				margin-left: 10rpx;
			}

			.content_2_content_item_status {
				border: 1rpx solid #cacaca !important;
				color: #cacaca !important;
			}

			.content_2_content_item_active {
			border: 1rpx solid #E40030 !important;
			color: #E40030 !important;
			}
		}
	}

	.settle {
		position: fixed;
		bottom: 60rpx;
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
				align-items: center;
				overflow-x: auto;
				display: -webkit-box;

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
					display: flex;
					align-items: center;
					justify-content: space-between;
					margin-right: 20rpx;

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