<template>

	<view class="addreddList">
		<view class="ListTabs">
			<u-tabs :list="list1" :activeStyle="{
				color:' #E40030',fontWeight:'bold'  }" :scrollable="false" lineColor="#E40030" @click="click"></u-tabs>
		</view>
		<view class="" style="padding-top: 200rpx;box-sizing: border-box;" v-if="loading_show">
			<u-loading-icon text="加载中" size="68" textSize="18"></u-loading-icon>
		</view>
		<view class="" v-if="addressList.length==0&&!loading_show">
			<emptyPage></emptyPage>
		</view>
		<u-swipe-action v-if="addressList.length&&!loading_show" :autoClose="true">
			<u-swipe-action-item :options="options1" :show="item.show" :name="item.id" :index='item.id'
				v-for="(item,index) in addressList" :key="item.id" @click.stop='actionitem'>
				<!-- @click.stop="set_addressId?chooseNewAddress(item):Selected(item)" -->
				<view class="addressli">
					<view class=" flex jsb ">
						<image class="view1 flex jc ac" :src="item.icon" mode="aspectFill"></image>
						<!-- <view class="view1 flex jc ac">
							{{item.name[0]}}
						</view> -->
						<!-- @tap.stop="chooseNewAddress(item)" -->
						<view class="view2 f1">
							<view class="flex ac">
								<view class="flex ac">
									<text class="name">
										{{item.name||''}}
									</text>


								</view>
							</view>
							<view class="detail flex ac">
								<text class="switch">状态:</text>
								<!-- 	<text class="switch"
									:class="item.switch?'switchk':'switchg'">{{item.switch?'启用':'禁用'}}</text> -->
								<u-switch space="2" :activeValue="1" activeColor="#E40030" inactiveColor="red" size="35"
									:inactiveValue="0" v-model="item.state" @change="change($event,item)"></u-switch>
							</view>
							<view class="detail flex ac">
								<view class="">
									最后修改：{{item.updatetime | timerFormat}}
								</view>
							</view>
						</view>
						<!-- <view class="view3 flex jc ac"
							@tap.stop="go('/pagesA/UserAll/UserToolsFour/Address/AddAddress?type=edit&id='+item.id)">
							<up-icon name="edit-pen-fill" color="#232323" size="22"></up-icon>
						</view> -->
					</view>
					<view class="line">

					</view>
					<view class="flex jsb ac">
						<view class="flex ac">
							<!-- <view class="choose flex ac jc " @tap.stop="chooseGoods(item)">
								<image class="bimg" :src="Data.imgurl + 'car1c.png'" mode="" v-if="item.default==1">
								</image>
								<image class="bimg" :src="Data.imgurl + 'car1.png'" mode="" v-else></image>
							</view>
							<view class="default">
								当前默认
							</view> -->
						</view>
						<!-- <view class="flex ac">
							<view class="default flex ac" @tap="fundelAddress(item)">
								<view class="mr10">
									<up-icon name="trash-fill" color="#232323" size="22"></up-icon>
								</view>
								删除
							</view>
							<view class="default flex ac ml30"
								@tap="go('/pagesA/UserAll/UserToolsFour/Address/AddAddress?type=edit&id='+item.id)">
								<view class="mr10">
									<up-icon name="edit-pen-fill" color="#232323" size="22"></up-icon>
								</view>
								编辑
							</view>
						</view> -->

					</view>
				</view>
			</u-swipe-action-item>
		</u-swipe-action>


		<view class="Bench">

		</view>
		<view class="bcbuts flex jc ac">
			<view class="bcbut flex jc ac" @tap.stop="gopage('/pages/merchant/AddMB/addClass')">
				<!-- <image class="add" src="" mode=""></image> -->
				新建分类
			</view>
		</view>

		<!-- <Loading :show="Data.loading_show"></Loading> -->
	</view>

</template>

<script>
	import {
		goodsCategory,
		goodsCategorySwitch,
		goodsCategoryDel
	} from '@/api/user.js'
	export default {
		data() {
			// 配送设置
			return {
				// imgurl: config.serverimg,
				loading_show: true,
				addressList: [],

				set_addressId: "",

				options1: [{
					text: '编辑',
					style: {
						backgroundColor: '#E40030 '
					}
				}, {
					text: '删除',
					style: {
						backgroundColor: '#f56c6c'
					}
				}],

				Selected: null,

				list1: [{
						name: '全部',
						type: "true"
					},
					{
						name: '已开启',
						type: 'normal'
					}, {
						name: '已禁用',
						type: 'false'
					}
				],
				type: true
			};
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		onLoad(e) {

		},
		onShow() {
			this.loading_show = true;
			this.getlist()
		},
		onReachBottom() {
			// if (this.last_page > this.page) {
			// 	this.page++;
			// 	this.getlist(1);
			// } else {
			// 	uni.showToast({
			// 		icon: "none",
			// 		title: '没有更多了~'
			// 	})
			// }
		},
		methods: {
			click(e) {
				this.type = e.type;
				this.loading_show = true;
				this.getlist()
			},
			change(e, item) {

				console.log(e, item, '选中的')
				goodsCategorySwitch({
					store_id: this.shopuser.id,
					id: item.id,
					switch: item.state
				}).then(res => {
					if (res.code == 1) {
						// this.getlist();
						this.addressList.forEach(item => {
							this.$set(item, 'show', true)
						})
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
					} else {
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
					}
				})
			},
			actionitem(e) {
				if (e.index == 0) {
					this.gopage('/pages/merchant/AddMB/addClass?id=' + e.name)
				} else if (e.index == 1) {
					this.fundelAddress(e.name)
				}
			},
			fundelAddress(item) {
				let that = this;
				uni.showModal({
					content: "确认删除该模板？",
					title: '删除模板',
					success(ress) {
						if (ress.confirm) {
							goodsCategoryDel({
								id: item,
								store_id: that.shopuser.id
							}).then(res => {
								if (res.code == 1) {
									that.getlist();
									uni.showToast({
										title: '删除成功~',
										icon: 'none'
									})
								} else {
									uni.showToast({
										title: res.msg,
										icon: 'none'
									})
								}
							})
						} else {
							// console.log(res);
						}
					}
				})
			},
			goodsCategorySwitch(item) {

			},
			async getlist(type) {
				let {
					code,
					data,
					msg
				} = await goodsCategory({
					page: this.page,
					store_id: this.shopuser.id,
					type: this.type
				})
				if (code == 1) {
					if (type) {
						this.addressList.push(...data);
					} else {
						this.addressList = data;
					}
					this.addressList.forEach(item => {
						this.$set(item, 'show', false)
					})
					this.loading_show = false;
					this.$forceUpdate()
					// this.last_page = data.last_page;
				}

				console.log('列表', data)
			},
			classdolwn() {
				console.log('到底分类')
			},
			choosetype(type) {
				this.drivetype = type;
			},
			gopage(url) {
				this.show = false
				uni.navigateTo({
					url
				})
			},
			fundel(item) {
				let that = this;
				uni.showModal({
					content: "确认删除模板？",
					title: '删除模板',
					success(ress) {
						if (ress.confirm) {
							tools.$api.store.DelGoodsTemplate({
								id: item.id
							}).then(res => {
								if (res.code == 1) {
									that.getlist();
									uni.showToast({
										title: '删除成功~',
										icon: 'none'
									})
								} else {
									uni.showToast({
										title: res.msg,
										icon: 'none'
									})
								}
							})
						} else {
							// console.log(res);
						}
					}
				})
			}
		}
	}



	// import Loading from "@/components/loading/loading.vue"
	// import tabbar from "@/components/tabbar/tabbar.vue"
	// import goodsItem from "@/components/goodsItem/goodsItem.vue"
	// import nodata from "@/components/nodata/nodata.vue"
	// import tools from "@/tools/index.js"
	// import config from "@/tools/config.js"

	// import {
	// 	reactive,
	// 	computed
	// } from 'vue';
	// import {
	// 	onLoad,
	// 	onPageScroll,
	// 	onPullDownRefresh,
	// 	onShow,
	// 	onReachBottom,
	// 	onUnload,
	// 	onHide,
	// 	onShareAppMessage
	// } from '@dcloudio/uni-app';
	// const wxinfo = computed(() => tools.$public.public.wxCapsuleInfo())

	// // 数据
	// const Data = reactive({
	// 	imgurl: config.serverimg,
	// 	loading_show: true,
	// 	addressList: [],

	// 	set_addressId: "",

	// 	options1: [{
	// 		text: '编辑',
	// 		style: {
	// 			backgroundColor: '#8E97FE '
	// 		}
	// 	}, {
	// 		text: '删除',
	// 		style: {
	// 			backgroundColor: '#f56c6c'
	// 		}
	// 	}],

	// 	Selected: null

	// })

	// setTimeout(() => {
	// 	Data.loading_show = false;
	// }, 500)

	// const Page = reactive({
	// 	page: 1,
	// 	limit: 10,
	// 	last_page: 0
	// })

	// onReachBottom(() => {
	// 	if (Page.last_page > Page.page) {
	// 		Page.page++;
	// 		getlist(1);
	// 	} else {
	// 		tools.$public.public.showToast('没有更多了~')
	// 	}
	// })

	// onLoad((opt) => {
	// 	if (JSON.stringify(opt) != "{}") {
	// 		if (opt.id) {
	// 			Data.set_addressId = opt.id;
	// 		}
	// 		if (opt.Selected) {
	// 			Data.Selected = opt.Selected;
	// 		}
	// 	}
	// })


	// onShow(() => {
	// 	// tools.$store('user').updateAddress()
	// 	getlist()
	// })

	// function Selected(item) {
	// 	if (Data.Selected) {
	// 		tools.$store('user').choose_address = item;
	// 		uni.navigateBack()
	// 	}
	// }



	// function editOrderAddress(id) {
	// 	tools.$api.user.editOrderAddress({
	// 		id: Data.set_addressId,
	// 		address_id: id
	// 	}).then(res => {
	// 		if (res.code == 1) {
	// 			uni.showToast({
	// 				title: "修改成功",
	// 				icon: "none"
	// 			})
	// 			setTimeout(() => {
	// 				uni.navigateBack()
	// 			}, 500)
	// 		} else {
	// 			uni.showToast({
	// 				title: res.msg,
	// 				icon: "none"
	// 			})
	// 			setTimeout(() => {
	// 				uni.navigateBack()
	// 			}, 500)
	// 		}
	// 	})
	// }


	// async function getlist(type) {
	// 	let {
	// 		code,
	// 		data,
	// 		msg
	// 	} = await tools.$api.user.addresslist({
	// 		page: Page.page
	// 	})
	// 	if (type) {
	// 		Data.addressList.push(...data.data);
	// 	} else {
	// 		Data.addressList = data.data;
	// 	}
	// 	Page.last_page = data.last_page;
	// 	console.log('初始地址', data)
	// }

	// const go = (page) => {
	// 	tools.$public.public.navigateTo(page)
	// }

	// const islogin = () => {
	// 	if (!uni.getStorageSync('token')) {
	// 		// tools.$public.public.goLogin()
	// 		return false
	// 	} else {
	// 		return true;
	// 	}
	// }

	// function chooseGoods(item) {
	// 	let flag = null;
	// 	// if (item.is_default) {
	// 	// 	flag = 0;
	// 	// } else {
	// 	// 	flag = 1;
	// 	// }
	// 	tools.$api.user.SetDefaultAddress({
	// 		id: item.id,
	// 	}).then(res => {
	// 		if (res.code == 1) {
	// 			console.log('修改默认地址');
	// 			getlist();
	// 		} else {
	// 			uni.showToast({
	// 				title: res.msg,
	// 				icon: "none"
	// 			})
	// 		}
	// 	})
	// 	// this.status = !this.status;
	// }

	// // function getlist() {
	// // 	let that = this;
	// // 	takeOverAddressList().then(res => {
	// // 		that.addressList = res.data.rows;
	// // 	})
	// // }



	// const chooseNewAddress = (item) => {
	// 	tools.$public.public.throttle(() => {
	// 		if (Data.set_addressId) {
	// 			uni.showModal({
	// 				title: '修改地址',
	// 				content: '收货地址只能修改一次，请确认是否修改？',
	// 				success(confirm) {
	// 					if (confirm.confirm) {
	// 						editOrderAddress(item.id)
	// 					} else {

	// 					}
	// 				}
	// 			})
	// 		} else {
	// 			tools.$store('user').choose_address = item;
	// 			uni.navigateBack()
	// 		}
	// 	}, 2000)
	// }
</script>
<style>
	page {
		background: #f5f5f5;
	}
</style>
<style lang="scss" scoped>
	.ml30 {
		margin-left: 30rpx !important;
	}

	.ListTabs {
		background: #fff;
	}

	::v-deep {
		.u-switch {
			width: 50px !important;
		}

		.u-switch__node {
			transform: translateX(-28px) !important;
		}

		.u-switch:before {
			width: 100%;
			height: 100%;
			content: '开启';
			font-size: 20rpx;
			color: #fff;
			white-space: nowrap;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.u-switch__bg {
			// width: 100% !important;
		}

		.u-switch__node--on {
			transform: translateX(-0px) !important;
		}

		.u-switch__bg:before {
			width: 100%;
			height: 100%;
			content: '禁用';
			font-size: 20rpx;
			color: #fff;
			white-space: nowrap;
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}

	.switch {
		margin-right: 10rpx;
		font-size: 24rpx;
	}

	.switchk {
		color: #E40030;
	}

	.switchg {
		color: red;
	}

	.bcbuts {
		width: 100%;
		position: fixed;
		z-index: 999;
		bottom: 30rpx;
	}

	.bcbut {
		width: 701rpx;
		height: 81rpx;
		background: #E40030;
		border-radius: 8rpx;
		font-size: 30rpx;
		color: #FFFFFF;


		.add {
			width: 28rpx;
			height: 28rpx;
			margin-right: 28rpx;
		}
	}

	.choose {
		.chooseicon {
			width: 36rpx;
			height: 36rpx;
		}
	}

	.default {
		font-size: 24rpx;
		color: #666666;
		margin-left: 10rpx;
	}

	.addreddList {
		// padding: 30rpx;
		box-sizing: border-box;

		.addressli {
			padding: 30rpx 30rpx;
			box-sizing: border-box;
			background: #fff;
			border-radius: 18rpx;
			border-bottom: 2rpx solid #E8E8E8;
			// margin-bottom: 30rpx;

			// .line {
			// 	width: 650rpx;
			// 	height: 2rpx;
			// 	background: #E8E8E8;
			// 	margin-top: 20rpx;
			// 	margin-bottom: 20rpx;
			// }

			.view1 {
				width: 85rpx;
				height: 85rpx;
				background: rgba(142, 151, 254, 0.1);
				font-size: 28rpx;
				color: #8E97FE;
				border-radius: 999px;
			}

			.view2 {
				width: 504rpx;
				margin: 0 0rpx 0 20rpx;

				.name {
					font-weight: bold;
					font-size: 28rpx;
					color: #232323;
					margin-right: 30rpx;
				}

				.phone {
					font-size: 28rpx;
					color: #232323;
					margin-left: 10rpx;
				}

				.detail {
					font-size: 24rpx;
					color: #666666;
					margin-top: 14rpx;
				}
			}

			.view3 {
				// width: 26rpx;
				// height: 26rpx;
				padding-right: 20rpx;
				box-sizing: border-box;
			}
		}
	}
</style>