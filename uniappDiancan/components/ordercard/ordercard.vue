<template>
	<!-- 商品卡牌 -->
	<view>
		<block>
			<view class="box" v-for="(item,index) in content" :key="item">
				<view class="box_left">
					<view class="box_left_img"
						@tap="routergo('/pages/order_all/goodsordersearch/goodsordersearch?id='+item.id+'&store_id='+store_id+'&activate_data='+activate_data+'&type='+item.type)">
						<image :src="item.image" mode="aspectFill"></image>
						<!-- <view class="box_left_img_text" v-if="item.tags"> {{item.tags}}</view> -->
					</view>
				</view>
				<view class="box_right flex jsb">
					<text class="box_right_title"
						@tap="routergo('/pages/order_all/goodsordersearch/goodsordersearch?id='+item.id+'&store_id='+store_id+'&activate_data='+activate_data+'&type='+item.type)">
						{{item.title||''}}</text>
					<view class="flex ">
						<view class="box_right_ranking me-text-beyond-multi1" v-if="item.tags">
							{{item.tags||''}}
						</view>
					</view>
					<view class="box_right_sell me-text-beyond-multi1">
						{{item.desc||''}}
					</view>
					<view class="box_right_price flex ac">
						<view class="flex ae">
							<view class="box_right_price_left">
								<text class="pricelittle">¥</text>
								{{item.minprice||''}}
								<text class="pricelittle">起</text>
							</view>
							<text class="closelineprice">￥{{item.line_price||''}}</text>
						</view>
						<view v-if="item.sku_count>0" class="box_right_price_right flex jc ac"
							@tap="stop(item.id,item.type)">
							<image class="addcar" :src="Httpimg+'addcar.png'" mode="" v-if="Httpimg"></image>
							<!-- <text> 选规格</text> -->
						</view>


						<!--@click="addCart" :index="index" :num="num"  -->
						<view @click="add_joinCar(item.id)" class="box_right_price_right flex jc ac" v-else>
							<!-- <image class="addcar" :src="Httpimg+'addcar.png'" mode="" ></image> -->
							<XmBezierCart :showMs="'img'" :imgSrc="Httpimg+'addcar.png'" left="8rpx" top="8rpx"
								:position="{ left: 120, bottom: 50 }" direction="bottom" v-if="Httpimg">
							</XmBezierCart>
						</view>


					</view>
				</view>
			</view>
		</block>
		<login v-if="enter" @loadpage="unloadpage" @closepage='closepage'></login>
	</view>
</template>

<script>
	import XmBezierCart from '@/components/xm-bezier-cart/xm-bezier-cart.vue';
	import config from '@/config.js'
	import {
		order_joinCar,
	} from "@/api/comm.js"
	export default {
		name: "ordercard",
		props: {
			content: {
				type: Array,
				default: [],
				required: true
			},
			store_id: {
				type: String,
				default: ' ',
				required: true
			},
			activate_data: {
				type: Number,
				default: '',
				required: true
			},
		},
		data() {
			return {
				enter: '',
				Httpimg: config.HttpImg,
			};
		},
		components: {
			XmBezierCart
		},
		methods: {
			// 添加购物车
			async add_joinCar(id) {
				if (!uni.getStorageSync('userinfo')) {
					this.$emit('login')
					return
				}

				let data = await order_joinCar({
					store_id: this.store_id,
					goods_id: id,
					count: 1,
					order_type: this.activate_data,
				})

				if (data.code == 1) {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
					this.$emit('order')
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},

			routergo(url) {
				uni.navigateTo({
					url: url
				})
			},
			stop(e, type) {
				let obj = {
					e,
					type
				}
				this.$emit('specification', obj)
			},
			//未登录关闭弹出层需要关掉组件
			closepage() {
				this.enter = false
			},
		}
	}
</script>

<style lang="scss">
	.box {
		padding-bottom: 40rpx;
		display: flex;

		.addcar {
			width: 48rpx;
			height: 48rpx;
		}

		.box_left {
			.box_left_img {
				width: 176rpx;
				height: 176rpx;
				position: relative;

				image {
					border-radius: 11rpx;
				}

				.box_left_img_text {
					width: 63rpx;
					height: 36rpx;
					background: #05B6F6;
					border-radius: 8rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FDFEFF;
					line-height: 36rpx;
					text-align: center;
					padding: 3rpx 5rpx;
					position: absolute;
					top: -10rpx;
					right: -5rpx;
				}
			}
		}

		.box_right {
			width: 300rpx;
			margin-left: 20rpx;
			flex-direction: column;

			.box_right_title {
				font-size: 28rpx;
				color: #333333;
				font-weight: bold;

				display: inline-block;
				// margin: 10rpx 0;
			}

			.box_right_ranking {
				border-radius: 4rpx 4rpx 4rpx 4rpx;
				border: 2rpx solid #DADADA;
				font-size: 20rpx;
				color: #777777;
				padding: 4rpx 8rpx;
				box-sizing: border-box;
				// width: 182rpx;
				// height: 34rpx;
				// background: #ECF3FF;
				// border-radius: 6rpx;
				// line-height: 34rpx;
				// text-align: center;
				// font-size: 24rpx;
				// font-weight: 400;
				// color: #05B6F6;
				// margin-bottom: 20rpx;
			}

			.box_right_sell {
				font-size: 20rpx;
				color: #AAAAAA;
				// font-size: 24rpx;
				// font-weight: 400;
				// color: #8A8A8A;
				// margin-bottom: 20rpx;
			}

			.box_right_price {
				display: flex;
				align-items: center;
				justify-content: space-between;

				.box_right_price_left {
					font-weight: bold;
					font-size: 34rpx;
					color: #FA5151;

					.pricelittle {
						font-size: 24rpx !important;
						color: #FA5151;
					}


				}

				.closelineprice {
					font-size: 24rpx;
					color: #AAAAAA;
					margin-left: 20rpx;
					text-decoration: line-through;
					font-weight: 0 !important;
				}

				.box_right_price_right_1 {
					// width: 150rpx;
					// height: 47rpx;
					// background: #05B6F6;
					border-radius: 17rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FDFEFF;
					line-height: 47rpx;
					text-align: center;
					position: relative;
				}

				.box_right_price_right {
					// width: 101rpx;
					// height: 47rpx;
					// background: #05B6F6;
					border-radius: 17rpx;
					font-size: 24rpx;
					font-weight: 400;
					color: #FDFEFF;
					line-height: 47rpx;
					text-align: center;
					position: relative;

					.box_right_price_right_count {
						width: 30rpx;
						height: 30rpx;
						background: #F65329;
						border-radius: 50%;
						text-align: center;
						line-height: 30rpx;
						font-size: 24rpx;
						font-weight: 600;
						color: #FFF8F6;
						position: absolute;
						top: -10rpx;
						right: -10rpx;
					}

				}
			}
		}
	}
</style>