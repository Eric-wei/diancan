<template>

	<view class="addreddList">
		<u-empty icon="/static/dc-empty.png" mode="data" text="暂无模板" textSize="38" iconSize="190"
			v-if="addressList.length==0">
		</u-empty>
		<!-- <nodata :tips="'暂无模板'"></nodata> -->
		<view class="addressli" v-for="(item,index) in addressList" :key="index">
			<view class=" flex jsb ac">
				<image class="view1 flex jc ac" src="/static/images/myfunli9.png" mode=""></image>
				<!-- 	<view class="view1 flex jc ac">
					{{item?.name[0]}}
				</view> -->
				<view class="view2 f1">
					<view class="flex ac">
						<view class="flex ac">
							<text class="name">
								{{item.name||''}}
							</text>
						</view>
						<!-- 
						<view class="addressDefault" v-if="item?.default==1">
							默认
						</view> -->
					</view>

					<text class="detail">
						最后修改：{{item.updatetime | timerFormat}}
					</text>
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

				</view>
				<view class="flex ac">
					<view class="default flex ac" @tap="fundel(item)">
						<view class="mr10">
							<u-icon name="trash-fill" color="#232323" size="28"></u-icon>
						</view>
						删除
					</view>
					<view class="default flex ac ml30" @tap="gopage('/pages/merchant/AddMB/Add?type=edit&id='+item.id)">
						<view class="mr10">
							<u-icon name="edit-pen-fill" color="#232323" size="28"></u-icon>
						</view>
						编辑
					</view>
				</view>

			</view>
		</view>

		<view class="Bench">

		</view>
		<view class="bcbut flex jc ac" @tap.stop="gopage('/pages/merchant/AddMB/Add?type=add')">
			<!-- <image class="add" src="" mode=""></image> -->
			新建规格模板
		</view>
		<!-- <Loading :show="Data.loading_show"></Loading> -->
	</view>

</template>

<script>
	import {
		skuTemplateList,
		skuTemplate
	} from '@/api/user.js'
	export default {
		data() {
			// 配送设置
			return {
				drivetype: '',
				list1: [{
					name: '审核中',
					type: 1
				}, {
					name: '审核通过',
					type: 2
				}, {
					name: '审核拒绝',
					type: 3
				}],
				page: 1,
				limit: 10,
				last_page: 0,
				// imgurl: config.serverimg,
				loading_show: true,
				addressList: [],

				set_addressId: ""
			};
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		onLoad(e) {},
		onShow() {
			this.getlist()
		},
		onReachBottom() {

		},
		methods: {
			async getlist(type) {
				let {
					code,
					data,
					msg
				} = await skuTemplateList({
					// page: this.page,
					store_id: this.shopuser.id
				})
				if (code == 1) {
					if (type) {
						this.addressList.push(...data);
					} else {
						this.addressList = data;
					}
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
							skuTemplate({
								store_id: that.shopuser.id,
								id: item.id,
								operation_type: 'del'
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

	.bcbut {
		width: 701rpx;
		height: 81rpx;
		background: #E40030;
		border-radius: 8rpx;
		font-size: 30rpx;
		color: #FFFFFF;
		position: fixed;
		bottom: 40rpx;

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
		padding: 30rpx;
		box-sizing: border-box;

		.addressli {
			padding: 20rpx 25rpx;
			box-sizing: border-box;
			background: #fff;
			border-radius: 18rpx;
			margin-bottom: 30rpx;

			.line {
				width: 650rpx;
				height: 2rpx;
				background: #E8E8E8;
				margin-top: 20rpx;
				margin-bottom: 20rpx;
			}

			.view1 {
				width: 85rpx;
				height: 85rpx;
				// background: rgba(142, 151, 254, 0.1);
				font-size: 28rpx;
				color: #8E97FE;
				// border-radius: 999px;
			}

			.view2 {
				width: 504rpx;
				margin: 0 0rpx 0 20rpx;

				.name {
					font-weight: bold;
					font-size: 28rpx;
					color: #232323;
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