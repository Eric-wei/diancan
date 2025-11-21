<template>
	<view class="manageProdeuccte flex jsb">
		<!-- lineWidth="60" -->
		<view class="manageTop flex jsb ac">
			<view class="manageTopl">
				<u-tabs lineColor="#333333" :activeStyle="{
					color:'#333333',
					fontWeight:'bold'
				}" :inactiveStyle="{
					color:'#aaa',
				}" lineheight='8' :list="list1" @click="click"></u-tabs>
			</view>

			<view class="manageTopr">
				<u-icon name="search" color="#333" size="40" v-if="!issearch" @click="issearch=true"></u-icon>
				<u-search placeholder="请输入" :showAction="false" v-model="keyword" v-else @custom="searchlist"
					@search="searchlist" @blur="searchblur"></u-search>
			</view>
		</view>
		<view class="center flex jsb  f1">
			<view class="centerClass">
				<scroll-view class="w100h100" scroll-y @scrolltolower="classdolwn">
					<!-- <view class="centerClassli centerClasslic">
						<image src="/static/dl.png" mode=""></image>
						<view class="centerClasslit">
							种草新品
						</view>
					</view> -->
					<view class="centerClassli" :class="CategoryId==item.id?'centerClasslic':''"
						v-for="item in Category" @click="chooseCategory(item)">
						<image :src="item.icon" mode="aspectFill"></image>
						<view class="centerClasslit">
							{{item.name}}
						</view>
					</view>
				</scroll-view>
			</view>
			<view class="centerProductes f1">
				<scroll-view class="w100h100" scroll-y="true" @scrolltolower="scrolltolower">
					<u-empty icon="/static/dc-empty.png" mode="data" text="暂无商品" textSize="18" iconSize="100"
						v-if="glist.length==0">
					</u-empty>
					<view class="productelis flex" v-for="item in glist">
						<view class="producteli flex  ac">
							<image :src="item.image" mode="aspectFill"></image>
							<view class="productInfo flex jsb f1">
								<view class="productInfot me-text-beyond-multi1">
									{{item.title}}
								</view>
								<view class="flex  ">
									<view class="productInfotags">
										{{item.tags}}
									</view>
								</view>

								<view class="productInfodesc me-text-beyond-multi1">
									{{item.desc}}
								</view>
								<view class="productInfoPrice">
									<text class="productInfoPriceA">
										￥
									</text>
									<text class="productInfoPriceB">
										{{item.price}}
									</text>
									<text class="productInfoPriceA">
										起
									</text>
									<text class="productInfoPriceC">
										￥{{item.line_price}}起
									</text>
								</view>
							</view>
						</view>
						<view class="operate flex je ac">
							<view class="operatebut flex jc ac" @click="goodsdel(item)">
								删除商品
							</view>
							<view class="operatebut flex jc ac"
								@click="gopage('/pages/merchant/AddMB/Producte?id='+item.id)">
								编辑商品
							</view>
							<view class="operatebut  flex jc ac" v-if="item.state==1" @click="updown(item,0)">
								下架商品
							</view>
							<view class="operatebut  flex jc ac" v-if="item.state==0" @click="updown(item,1)">
								上架商品
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
		<view class="managebottom flex jsb ac">
			<view class="managebottomlil flex jc ac" @click="gopage('/pages/merchant/AddMB/class')">
				管理分类
			</view>
			<view class="managebottomlir flex jc ac" @click="gopage('/pages/merchant/AddMB/Producte')">
				<u-icon name="plus" color="#fff" size="28"></u-icon>
				新建商品
			</view>
		</view>
	</view>
</template>

<script>
	import {
		goodsCategory,
		goodsList,
		goods_switch,
		delgoods
	} from '@/api/user.js'
	import {

	} from '@/api/merchant.js'
	export default {
		data() {
			// 配送设置
			return {
				issearch: false,
				drivetype: '',
				list1: [{
						name: '售卖中',
						type: 1
					}, {
						name: '已下架',
						type: 0
					},
					// {
					// 	name: '审核拒绝',
					// 	type: 3
					// },
				],
				glist: [],
				Category: [],
				CategoryId: '',
				page: 1,
				limit: 10,
				lastPage: 0,
				state: 1,
				keyword: ''
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
			this.goodsCategorys()
		},
		methods: {
			goodsdel(item) {
				let that = this;
				uni.showModal({
					title: '删除',
					content: "确认删除？",
					success(ress) {
						if (ress.confirm) {
							delgoods({
								goods_id: item.id
							}).then(res => {
								if (res.code == 1) {
									uni.showToast({
										icon: "none",
										title: res.msg
									})
									that.page = 1;
									that.goodslists()
								} else {
									uni.hideToast()
									uni.showModal({
										title: '提示',
										content: res.msg,
									})
								}
							})
						}

					}
				})

			},
			searchlist() {
				this.page = 1;
				this.goodslists()
			},
			searchblur() {
				// this.page = 1;
				// this.goodslists()
				if (this.keyword) {
					this.issearch = true;
				} else {
					this.issearch = false;
				}
			},
			updown(item, switchs) {
				let that = this;
				uni.showModal({
					title: `${switchs?'上架':'下架'}`,
					content: `是否${switchs?'上架':'下架'}商品？`,
					success(res) {
						if (res.confirm) {
							goods_switch({
								store_id: that.shopuser.id,
								goods_id: item.id,
								switch: switchs
							}).then(res => {
								if (res.code == 1) {
									that.goodslists()
								}
								uni.showToast({
									icon: "none",
									title: res.msg
								})
							})
						}
					}
				})

			},
			scrolltolower() {
				if (this.lastPage > this.page) {
					this.page++;
					this.goodslists(1)
				} else {
					uni.showToast({
						icon: "none",
						title: '没有更多了~'
					})
				}
			},
			click(e) {
				console.log(e)
				this.state = e.type;
				this.page = 1;
				this.goodslists()
			},
			chooseCategory(item) {
				if (item) {
					this.CategoryId = item.id;
					this.page = 1;
					this.goodslists()
				}
			},
			goodsCategorys() {
				goodsCategory({
					store_id: this.shopuser.id,
					type: 'normal'
				}).then(res => {
					if (res.code == 1) {
						this.Category = res.data;
						if (this.Category.length) {
							let carrs = this.Category.filter(item => item.id == this.CategoryId)
							if (carrs.length) {

							} else {
								this.CategoryId = this.Category[0].id;
							}
							this.page = 1;
							this.goodslists()
						} else {
							this.glist = [];
							this.$forceUpdate()
							// this.Category
						}
					}
					console.log(this.Category, '数据更新')
					this.$forceUpdate()
				})
				this.$forceUpdate()
			},
			goodslists(type) {
				goodsList({
					store_id: this.shopuser.id,
					type_id: this.CategoryId,
					state: this.state,
					page: this.page,
					limit: this.limit,
					keyword: this.keyword
				}).then(res => {
					if (res.code == 1) {
						if (type) {
							this.glist.push(...res.data.data);
						} else {
							this.glist = res.data.data;
						}
						this.lastPage = res.data.last_page;
					}
				})
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
		}
	}
</script>

<style lang="scss" scoped>
	.productelis {
		margin-bottom: 40rpx;
		flex-direction: column;
	}

	.manageTop {
		.manageTopl {}

		.manageTopr {
			max-width: 250px;
		}
	}


	.centerProductes {
		padding: 24rpx 20rpx;
		box-sizing: border-box;
		background: #fff;
		// min-width: 560rpx;

		.operate {
			margin-top: 30rpx;
		}

		.operatebut {
			width: 136rpx;
			height: 56rpx;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			border: 2rpx solid #777777;
			margin-left: 48rpx;
			font-size: 24rpx;
			color: #777777;
		}


		.producteli {
			image {
				width: 176rpx;
				height: 176rpx;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				margin-right: 16rpx;
			}

			.productInfo {
				height: 176rpx;
				flex-direction: column;
			}

			.productInfot {
				font-weight: bold;
				font-size: 28rpx;
				color: #333333;
				line-height: 1;
			}

			.productInfotags {
				padding: 4rpx 8rpx;
				box-sizing: border-box;
				font-size: 20rpx;
				color: #777777;
				border: 2rpx solid #DADADA;
				border-radius: 4rpx;
				line-height: 1;
			}

			.productInfodesc {
				font-size: 20rpx;
				color: #AAAAAA;
			}

			.productInfoPrice {
				line-height: 1;

				.productInfoPriceA {
					font-size: 24rpx;
					color: #FA5151;
				}

				.productInfoPriceB {
					font-weight: bold;
					font-size: 34rpx;
					color: #FA5151;
					margin-left: 5rpx;
					margin-right: 5rpx;
				}

				.productInfoPriceC {
					font-size: 24rpx;
					color: #AAAAAA;
					text-decoration: line-through;
					margin-left: 10rpx;
				}
			}


		}
	}

	.manageProdeuccte {
		flex-direction: column;
		height: 100vh;

		.manageTop {
			padding: 24rpx 32rpx;
			box-sizing: border-box;
			background: #FFFFFF;
		}

		.center {
			overflow: hidden;

			.centerClass {
				height: 100%;
				background: #FAFAFA;

				.centerClassli {
					width: 160rpx;
					padding: 14rpx 10rpx;
					box-sizing: border-box;

					image {
						width: 40rpx;
						height: 40rpx;
						display: block;
						margin: auto;
					}

					.centerClasslit {
						font-size: 24rpx;
						color: #777777;
						margin-top: 12rpx;
						text-align: center;
					}
				}

				.centerClasslic {
					background: #fff !important;

					.centerClasslit {
						color: #333333 !important;
					}
				}

			}

		}

		.managebottom {
			padding: 24rpx 32rpx 30rpx 32rpx;
			box-sizing: border-box;
			background: #fff;

			.managebottomlil {
				width: 334rpx;
				height: 80rpx;
				border-radius: 8rpx 8rpx 8rpx 8rpx;
				border: 2rpx solid #777777;
				font-size: 30rpx;
				color: #777777;
			}

			.managebottomlir {
				width: 334rpx;
				height: 80rpx;
				background: #E40030;
				border-radius: 8rpx 8rpx 8rpx 8rpx;
				font-size: 30rpx;
				color: #FFFFFF;
			}
		}
	}
</style>