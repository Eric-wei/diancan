<template>
	<view>

		<u-popup :show="localSpecShow" zIndex='10073' :safeAreaInsetTop='false' mode="center" :round="20"
			@close="close">
			<view class="specification">
				<view class="specification_title">请选择分类</view>
				<view class="specification_title_1">
					<scroll-view scroll-y="true" style="height: 100%;">
						<view v-for="(itemall,indexall) in specification_list" :key="itemall">
							<height :hg='30'></height>
							<text class="specification_title_1_title">{{itemall.name}}</text>
							<!-- 多规格 -->
							<view class="" v-if="type==2">
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
									<view class="d_box_itemtt">
										{{item.name}}
									</view>
									<view class="">
										<u-number-box min='0' v-model="item.value" :name='item.id' @change='focus'>
											<view slot="minus" class="minus">
												<u-icon name="minus" size="26"></u-icon>
											</view>
											<text slot="input" style="width: 40rpx;text-align: center;"
												class="input">{{item.value}}</text>
											<view slot="plus" class="plus">
												<u-icon name="plus" color="#fff" size="26"></u-icon>
											</view>
										</u-number-box>
									</view>

								</view>
							</view>

						</view>
					</scroll-view>
				</view>

				<view class="" v-if="type==2">
					<view class="selected">
						<text>已选规格:</text>
						<text v-for="(item,index) in style" :key="item">{{item.name +' 、'}}</text>
					</view>
				</view>
				<view class="" v-else>
					<view class="selected" v-if="count>0">
						<text>已选规格:</text>
						<template v-for="(item,index) in spu_info">
							<text v-if="item.count>0">{{item.name}}x{{item.count}}</text>
						</template>
					</view>
				</view>

				<view class="sublist">
					<view class="sublist_left">
						<text>总计 </text>
						<text>
							<text>¥</text>
							{{orderprive}}</text>
					</view>
					<view class="sublist_right" @tap="add_joinCar()">
						<text>+</text> <text>加入菜单</text>
					</view>
				</view>
				<view class="close" @tap="close(),style=[]">
					<u-icon name="close-circle" color="#FFFAF4" size="80"></u-icon>
				</view>
			</view>
		</u-popup>


	</view>
</template>

<script>
	import {
		store_itemPrice,
		store_item,
		order_joinCar,
	} from "@/api/comm.js"
	export default {
		name: "specification",
		props: ['specshow', 'order_id', 'store_id', 'activate_data', 'type'],
		data() {
			return {
				orderprive: '',
				style: [], //规格动态样式
				specification_list: [],
				spu_info: [],
				count: 0,
				localSpecShow: this.specshow

			};
		},
		watch: {
			specshow(newVal) {
				this.localSpecShow = newVal;
			},
			orderprive(newval, oldval) {
				deep: true // 开启深度监听 专门对付复杂数据类型
				immediate: true // 首次监听 一打开页面就想监听
				this.count = 0
				this.spu_info.forEach(item => {
					this.count = this.count + item.count
				})

			}
		},

		methods: {
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
			async selective_specification(index, id, name) {
				this.$set(this.style[index], 'id', id)
				this.$set(this.style[index], 'name', name)
				this.store_itemPriceApi()
			},
			async specification(e) {
				this.specification_list = []
				let data = await store_item({
					goods_id: this.order_id
				})
				if (data.code == 1) {
					this.style = []
					this.spu_info = []
					if (this.type == 2) {
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

					this.specification_list.push(...data.data)
					this.store_itemPriceApi()
				}

			},
			// 价格计算
			async store_itemPriceApi() {
				let str = ''
				let spu = []
				if (this.type == 2) {
					this.style.forEach((res, index) => {
						// if (index == (this.style.length - 1)) {
						// 	str += res.id
						// } else {
						str += res.id + '_'
						// }
					})
					str = str.substring(0, str.length - 1)
				} else {
					this.spu_info.forEach(item => {
						if (item.count > 0) {
							spu.push({
								count: item.count,
								spu_id: item.spu_id
							})
						}
					})
				}

				let data = await store_itemPrice({
					goods_id: this.order_id,
					spu_id: str,
					spu_info: spu
				})
				if (data.code == 1) {
					let num = data.data.price
					this.orderprive = num
				}

			},
			// 添加购物车
			async add_joinCar() {
				if (!uni.getStorageSync('userinfo')) {
					this.localSpecShow = false
					this.$emit('close')
					this.$emit('login')
					return
				}
				let str = ''
				let spu = []
				if (this.type == 2) {
					// 多规格
					this.style.forEach((res, index) => {
						if (index == (this.style.length - 1)) {
							str += res.id
						} else {
							str += res.id + '_'
						}
					})
				} else {
					if (this.specification_list.length > 0) {
						console.log(this.spu_info)
						let flag = this.spu_info.every(item => item.count == 0)
						if (flag) {
							uni.showToast({
								title: '请添加规格',
								icon: 'none'
							})
							return
						}
						// return
					}
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
				let seat_id = uni.getStorageSync('scene')
				let data = await order_joinCar({
					store_id: this.store_id,
					goods_id: this.order_id,
					spu_id: str,
					count: 1,
					order_type: this.activate_data,
					spu_info: spu,
					seat_id
				})
				if (data.code == 1) {
					setTimeout(() => {
						this.$emit('order')
					}, 500)
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
					this.spu_info = []
					this.style = []
					this.close()
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},


			close() {
				this.spu_info = []
				this.style = []
				this.$emit('close')
			}
		}
	}
</script>

<style lang="scss">
	.d_box {
		.d_box_item {
			font-size: 34rpx;
			color: #3d3d3d;
			// width: 100%;
			margin-bottom: 20rpx;
			margin-right: 30rpx;

			.d_box_itemtt {
				margin-right: 30rpx;
			}
		}
	}

	.specification {
		width: 688rpx;
		background: #FFFFFF;
		border-radius: 12rpx;
		position: relative;
		z-index: 10074;
		padding-top: 30rpx;
		box-sizing: border-box;

		.specification_title {
			text-align: center;
			font-size: 38rpx;
			font-weight: 600;
			color: #353535;
			margin: 0 auto;
		}

		.specification_title_1 {
			width: 95%;
			height: 504rpx;
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
		margin-top: 30rpx;

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
				color: #FF0000;

				text:nth-child(1) {
					font-size: 28rpx;
					font-weight: 600;
					color: #FF0000;
					font-size: 24rpx;
				}
			}
		}

		.sublist_right {
			width: 234rpx;
			// height: 62rpx;
			background: #E40030;
			border-radius: 8rpx;
			font-size: 32rpx;
			font-weight: 500;
			color: #FFF;
			line-height: 62rpx;
			text-align: center;
			padding: 10rpx 0;
			box-sizing: border-box;
		}
	}


	.minus {
		width: 25px;
		height: 25px;
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
	}

	.plus {
		width: 25px;
		height: 25px;
		border-width: 1px;
		background-color: #333;
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
</style>