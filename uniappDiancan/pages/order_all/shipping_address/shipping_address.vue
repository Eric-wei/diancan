<template>
	<view>
		<height :hg='24'></height>
		<view class="shipping_address">
			<view class="hade_title  flex ac" v-if="ischoose&&address_lists.length>0">
				可配送地址
				<!-- 【当前商家】可配送至以下地址 -->
			</view>
			<!--  -->
			<view class="content" v-for="(item,index) in address_lists" :key="item"
				@tap.stop="exchange?select_site(item):''">
				<view class="content_right flex ac">
					<view class="checkboxs flex jc ac mr30" @click="choose(item)" v-if="ischoose">
						<image class="checkboxselected" :src="'/static/images/couponc.png'" mode=""
							v-if="addressids.includes(item.id)">
						</image>
						<view class="checkboxnotselected" v-else>

						</view>
					</view>
					<view class="f1">


						<view class="content_right_top ">


							<view class="content_right_top_1">
								{{item.address}}-{{item.remark}}
							</view>
							<view class="content_right_top_2" @tap.stop="alter(item.id,item)" v-if="show==0">
								<image src="@/static/Project_drawing 21.png" mode=""></image>
							</view>
							<view class="content_right_top_2" @tap.stop="delete_address(item.id)" v-if="show==1">
								<u-icon name="minus-circle-fill" color="#FD494C" size="44"></u-icon>
							</view>


						</view>
						<view class="content_right_bottom">
							<text v-if="item.isDefault==1">默认</text>
							<text v-else>{{item.tag}}</text>
							<text>{{item.title}} ({{item.suffix}})</text>
							<text>{{item.mobile}}</text>
						</view>
					</view>
				</view>
			</view>
			<view style="margin-top: 50rpx;margin-bottom: 50rpx;" v-if="address_lists.length<1">
				<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24'
					text='请添加收货地址'></u-empty>
			</view>

			<view class="" v-if="ischoose&&noaddress_list.length>0">

				<view class="hade_title flex ac" v-if="ischoose">
					以下地址超出店铺名称可配送范围内
					<!-- 【当前商家】可配送至以下地址 -->
				</view>
				<view class="content " v-for="(item,index) in noaddress_list" :key="item">
					<view class="content_right nochoose flex  ac">
						<view class="nochooseicon">

						</view>
						<view class="f1">
							<view class="content_right_top">
								<view class="content_right_top_1">
									{{item.address}}-{{item.remark}}
								</view>
								<view class="content_right_top_2" @tap.stop="alter(item.id,item)" v-if="show==0">
									<image src="@/static/Project_drawing 21.png" mode=""></image>
								</view>
								<view class="content_right_top_2" @tap.stop="delete_address(item.id)" v-if="show==1">
									<u-icon name="minus-circle-fill" color="#FD494C" size="44"></u-icon>
								</view>
							</view>
							<view class="content_right_bottom">
								<text v-if="item.isDefault==1">默认</text>
								<text v-else>{{item.tag}}</text>
								<text>{{item.title}} ({{item.suffix}})</text>
								<text>{{item.mobile}}</text>
							</view>
						</view>

					</view>
				</view>
				<!-- <view style="margin-top: 50rpx;margin-bottom: 50rpx;" v-if="noaddress_list.length<1">
					<u-empty mode="data" :iconSize='150' :textSize='24' text='请添加收货地址' icon=""></u-empty>
				</view> -->
			</view>

		</view>
		<height :hg='150'></height>
		<view class="button">
			<view class="button_left" @tap="delete_address_show" v-if="!ischoose">
				管理
			</view>
			<view class="button_left" @tap="routerTo('/pages/order_all/write_site/write_site?state=0')" v-else>
				新增
			</view>
			<view class=" btn flex jc ac" @tap="routerTo('/pages/order_all/write_site/write_site?state=0')"
				v-if="!ischoose">
				新增收货地址
			</view>
			<view class="btn flex jc ac" @tap="confirmAddress()" v-else>
				确认
			</view>
		</view>
	</view>
</template>

<script>
	import {
		address_list,
		address_del
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				addressids: [],
				addressid: {},
				select_index: '',
				address_lists: [],
				noaddress_list: [],
				show: 0,
				go_back: '',
				mall_content: {}, //积分商城内容
				ischoose: 0,
				exchange: 0
			};
		},
		onLoad(obj) {

			if (obj.ischoose) {
				this.ischoose = obj.ischoose;
			}
			if (obj.query) {
				if (obj.query.exchange) {
					// console.log('存在')
					this.exchange = obj.query.exchange;
				}
				this.go_back = obj.query.goback
				if (obj.query.item) {
					this.mall_content = JSON.parse(obj.query.item)
				}
			} else {
				this.go_back = obj.goback
			}

			// this.address_listApi()
		},
		onShow() {
			this.address_listApi()
		},
		computed: {
			shopid() {
				return uni.getStorageSync('shop')
			},
		},
		methods: {
			confirmAddress() {
				if (this.addressids.length == 0) {
					uni.showToast({
						icon: "none",
						title: '请选择地址'
					})
					return
				}
				this.select_site(this.addressid)
			},
			choose(item) {
				if (this.addressids.includes(item.id)) {
					if (this.addressids[0] == item.id) {
						this.addressid = {};
						this.addressids = [];
					} else {
						this.addressid = item;
						this.addressids = [item.id];
					}
				} else {
					this.addressid = item;
					this.addressids = [item.id];
				}
			},
			select_site(item) {
				// this.select_index = e
				if (this.go_back == 0) {
					let pages = getCurrentPages(); // 获取当前的页面栈 
					let prevPage = pages[pages.length - 2]; //  获取上一页面
					prevPage.$item = JSON.stringify(item)
					uni.navigateBack({
						delta: 1 //表示回到上一页面
					})
				} else if (this.go_back == 1) {
					if (this.exchange) {
						// uni.redirectTo({
						// 	url: '/pages/me_all/confirm/confirm?address=' + JSON.stringify(item) + '&item=' + JSON
						// 		.stringify(this.mall_content)
						// })
					}


					let pages = getCurrentPages(); // 获取当前的页面栈
					let prevPage = pages[pages.length - 2]; //  获取上一页面
					prevPage.$item = {
						address: item,
						item: this.mall_content
					}
					uni.navigateBack({
						delta: 1 //表示回到上一页面
					})
				} else {
					uni.switchTab({
						url: '/pages/me/me'
					})
				}


			},
			alter(id, item) {
				this.$store.state.vuex_address = item
				uni.navigateTo({
					url: `/pages/order_all/write_site/write_site?state=1&order_id=${id}`
				})

			},
			async address_listApi() {
				let data = await address_list({
					page: 1,
					limit: 10,
					store_id: this.ischoose ? this.shopid : ''
				})
				if (data.code == 1) {
					if (this.ischoose) {
						this.noaddress_list = data.data.no;
						this.address_lists = data.data.yes;
					} else {
						this.address_lists = data.data.rows
					}
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
			delete_address_show() {
				if (this.show) {
					this.show = 0
				} else {
					this.show = 1
				}
			},
			async delete_address(id) {

				let data = await address_del({
					address_id: id
				})
				uni.showToast({
					title: data.msg,
					icon: "none",
				})
				this.address_listApi()
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
	.mr30 {
		margin-right: 30rpx;
	}

	.checkboxs {
		width: 48rpx;
		height: 48rpx;

		.checkboxnotselected {
			width: 34rpx;
			height: 34rpx;
			border: 2rpx solid #999;
			border-radius: 999%;
		}

		.checkboxselected {
			width: 40rpx;
			height: 40rpx;
			border: 2rpx solid #fff;
			border-radius: 999%;
		}
	}

	.hade_title {
		height: 87rpx;
		font-size: 24rpx;
		font-weight: 400;
		color: #AAAAAA;
		line-height: 1;
		text-indent: 30rpx;
		border-radius: 20rpx 0;
		background: #fff;
		padding: 24rpx 0 0 0;
		box-sizing: border-box;
	}

	.nochooseicon {
		width: 40rpx;
		height: 40rpx;
		background: rgba(170, 170, 170, 0.1);
		border-radius: 999%;
		border: 2rpx solid rgba(170, 170, 170, 0.2);
		margin-right: 30rpx;
	}

	.shipping_address {
		width: 690rpx;
		margin: auto;
		// background: #FFFFFF;
		border-radius: 20rpx;
		overflow: hidden;
	}

	.nochoose {
		background: rgba(222, 220, 220, 0.3) !important;
		border-radius: 20rpx;
	}

	.button {
		width: 100%;
		height: 137rpx;
		background: #FFFFFF;
		position: fixed;
		bottom: 0;
		display: flex;
		align-items: center;
		padding: 0 20rpx 32rpx;

		.button_left {
			font-size: 26rpx;
			font-weight: 400;
			color: #333333;
			padding: 0 20rpx;

		}

		.btn {
			width: 593rpx;
			height: 80rpx;
			background: #E40030;
			border-radius: 8rpx;
			font-size: 32rpx;
			font-weight: 400;
			color: #FFFFFF;
			text-align: center;

		}
	}



	.content {
		background: #FFFFFF;
		margin-bottom: 24rpx;
		border-radius: 20rpx;

		.content_right {
			padding: 34rpx;
			box-sizing: border-box;

			.content_right_top {
				display: flex;
				justify-content: space-between;
				max-width: 400px;


				.content_right_top_1 {
					font-size: 28rpx;
					font-weight: 500;
					color: #333333;
					// max-width: 260px;

					-webkit-line-clamp: 2; //设置几行
					display: -webkit-box; //设置为伸缩盒弹性盒子展示
					overflow: hidden; //超出隐藏
					text-overflow: ellipsis; //设置超出部分以省略号展示
					-webkit-box-orient: vertical; //伸缩盒弹性盒子的排列方式
				}

				.content_right_top_2 {
					width: 30rpx;
					height: 30rpx;
					padding: 0 20rpx;
				}
			}

			.content_right_bottom {
				margin-top: 20rpx;

				text:nth-child(1) {
					width: 52rpx;
					border-radius: 5rpx;
					border: 1rpx solid #E40030;
					font-size: 20rpx;
					font-weight: 400;
					color: #E40030;
					text-align: center;
					display: inline-block;
					margin-right: 8rpx;
				}

				text:nth-child(2) {
					font-size: 24rpx;
					font-weight: 400;
					color: #696969;
					padding: 0 10rpx;
				}

				text:nth-child(3) {
					font-size: 24rpx;
					font-weight: 400;
					color: #696969;
					padding: 0 10rpx;
				}
			}

		}
	}
</style>