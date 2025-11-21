<template>
	<view>
		<u-sticky>
			<view class="tab_box ">
				<u-tabs lineWidth="0" :activeStyle="{
			    color: '#E40030',
			    fontWeight: 'bold',
			    transform: 'scale(1.05)'
			}" :inactiveStyle="{
			    color: '#000',
			    transform: 'scale(1)'
			}" itemStyle="width:134rpx; height: 70rpx;text-align:center;" :scrollable='false' :list="tabList"
					@click="click"></u-tabs>
			</view>
		</u-sticky>


		<view class="" style="margin-top: 20rpx;">
			<view class="" v-for="(item,index) in list">
				<view class="title" v-if="item.data.length>0">
					{{item.type}}
				</view>
				<view class="box">
					<view class="" v-for="item1 in item.data">
						<view class="box_item" @click="order(item1.id,item1)"
							:class="item1.use_status !='1'?'active':''">
							<image v-if="item1.use_status ==2" @click.stop="gopage(item1.order_id)"
								src="../static/icon21.png" mode="">
							</image>
							<view class="circle" v-if="item1.use_status==1 && item1.is_show">
								<u-icon name="checkmark-circle-fill" color="#333" size="32"></u-icon>
							</view>
							<view class="text_1">{{item1.title}}</view>
							<view class="text_2">{{item1.use_status==1?'空闲中':item1.use_status==2?'使用中':'打扫中'}}</view>
							<view class="text_3" v-if="item1.order_time">{{item1.order_time | Formatone}}</view>
						</view>
						<view class="clear" v-if="item1.use_status==3 || item1.use_status==2"
							@click.stop="clearDesk(item1.id,item1)">
							完成打扫
						</view>
					</view>
				</view>
			</view>
			<emptyPage v-if="emptyShow"></emptyPage>
		</view>

		<view class="fixed" v-if="is_show">
			<view class="fixed_btn" @click="orderCreate">
				提交订单
			</view>
		</view>
	</view>
</template>

<script>
	import {
		seat_list,
		order_create
	} from '@/api/comm.js'
	import { release_desk } from '@/api/merchant.js'
	export default {
		data() {
			return {
				tabList: [{
						name: '全部',
						id: 0
					}, {
						name: '空闲',
						id: 1
					}, {
						name: '使用中',
						id: 2
					},
					// {
					// 	name: '打扫中',
					// 	id: 3
					// },
				],
				list: [],
				emptyShow: false,
				current: 0,
				idList: [],
				is_show: true
			};
		},
		filters: {
			Formatone(num) {
				//过滤器 用于格式化时间
				let date = new Date(num * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				let year = date.getFullYear();
				let month = ("0" + (date.getMonth() + 1)).slice(-2);
				let sdate = ("0" + date.getDate()).slice(-2);
				let hour = ("0" + date.getHours()).slice(-2);
				let minute = ("0" + date.getMinutes()).slice(-2);
				let second = ("0" + date.getSeconds()).slice(-2);
				// 拼接
				let result = month + "/" + sdate + ' ' + hour + ":" + minute
				// 返回
				return result;
			}
		},
		onLoad(e) {
			if (e.is_show) {
				this.is_show = false
				uni.setNavigationBarTitle({
					title: '桌号管理'
				})
			}
			this.seat_listApi()
			this.listLoad()
		},
		onShow() {
			this.seat_listApi()
		},
		methods: {
			listLoad() {
				setInterval(() => {
					this.seat_listApi()
				}, 60000)
			},
			click(e) {
				this.current = e.id
				this.list = []
				this.seat_listApi()
			},
			gopage(id) {
				if (!id * 1) {
					uni.showModal({
						title: '提示',
						content: '订单已完成，请手动释放桌号',
					})
					return
				}
				uni.navigateTo({
					url: `/pages/scan/orderDetail/orderDetail?id=${id}`
				})

			},
			async seat_listApi() {
				let res = await seat_list({
					store_id: uni.getStorageSync('shopuser').id,
					user_status: this.current
				})
				if (res.code == 1) {
					this.list = res.data
					let arr = []
					res.data.forEach(item => {
						item.data.forEach(item1 => {
							arr.push((item1))
						})
					})
					if (arr.length < 1) {
						this.emptyShow = true
					} else {
						this.emptyShow = false
					}
				}
			},
			orderCreate() {
				let peopleCount = uni.getStorageSync('peopleCount')
				order_create({
					orderType: 3,
					storeId: uni.getStorageSync('shopuser').id,
					type: 1,
					dc_type: 2,
					seatId: this.idList,
					peopleCount,
				}).then(res => {
					if (res.code == 1) {
						uni.setStorageSync('tableNumber', false)
						uni.reLaunch({
							url: '/pages/merchant/order/order'
						})
					} else {
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
					}

				})
			},
			order(id, itemMsg) {
				if (itemMsg.use_status == 1 && this.is_show) {
					console.log(this.list)
					this.list.forEach(item => {
						item.data.forEach(item1 => {
							if (item1.id == id) {
								// if (item1.is_show) {
								// 	item1.is_show = false
								// 	this.idList.forEach((item2, index2) => {
								// 		if (item2 == id) {
								// 			this.idList.splice(index2, 1)
								// 		}
								// 	})
								// } else {
								// 	item1.is_show = true
								// 	this.idList.push(id)
								// }

								// 只能选择一桌
								item1.is_show = true
								this.idList = [id]
							} else {
								item1.is_show = false
							}
						})
					})
					this.$forceUpdate()
				}
				if (itemMsg.use_status == 2) {
					this.gopage(itemMsg.order_id)
				}

				// uni.setStorageSync('option', 3)
				// uni.setStorageSync('scene', id)
				// uni.setStorageSync('tableNumber', true)
				// uni.setStorageSync('addAdish', false)

			},
			// 清理桌子
			clearDesk(id, desk) {
				release_desk({ id }).then(res => {
					if (res.code == 1) {
						desk.use_status = 1
					}
					uni.$showMsg(res.msg)
				})
			}
		}
	}
</script>

<style lang="scss">
	page {
		padding: 0 0 150rpx;
	}

	.fixed {
		padding: 20rpx 30rpx 50rpx;
		background-color: #fff;
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;

		.fixed_btn {
			height: 80rpx;
			background-color: #E40030;
			font-size: 28rpx;
			text-align: center;
			line-height: 80rpx;
			color: #fff;
			border-radius: 8rpx;
		}
	}

	.box {
		padding: 30rpx;
		box-sizing: border-box;
		display: grid;
		grid-template-columns: repeat(3, 220rpx);
		justify-content: space-between;
		row-gap: 20rpx;
	}

	.title {
		padding-left: 30rpx;
		font-size: 30rpx;
		color: #353535;
	}

	.box_item {
		padding: 20rpx;
		height: 160rpx;
		border-radius: 10rpx;
		background-color: #E40030;
		text-align: center;
		color: #fff;
		position: relative;
		display: flex;
		flex-direction: column;
		justify-content: space-between;

		.circle {
			position: absolute;
			top: -10rpx;
			right: -10rpx;
		}

		image {
			position: absolute;
			top: 8rpx;
			right: 10rpx;
			width: 40rpx;
			height: 40rpx;
		}

		.text_1 {
			margin-top: 10rpx;
			font-size: 32rpx;
		}

		.text_2 {
			font-size: 28rpx;
			margin: 20rpx 0;
		}

		.text_3 {
			font-size: 24rpx;
		}
	}

	.clear {
		margin-top: 15rpx;
		width: 100%;
		border-radius: 10rpx;
		background-color: #E40030;
		color: #fff;
		padding: 15rpx 0rpx;
		text-align: center;
		font-size: 24rpx
	}

	.active {
		background-color: red !important;
	}

	.tab_box {
		background-color: #fff;
		padding: 10rpx 0;
		box-sizing: border-box;

	}
</style>