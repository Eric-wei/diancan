<template>
	<view class="">


		<view class="Drive">
			<view class="Drivetitle">
				配送设置
			</view>
			<view class="Driveli flex jsb ac">
				<view class="Driveli_lable">
					配送方式
				</view>
				<view class="Driveli_funtype flex ac">
					<!-- <view class="Driveli_funtypeli  flex jc ac" :class="ps_type==1?'Driveli_funtypelic':''"
						@click="choosetype(1)">
						平台配送
					</view> -->
					<view class="Driveli_funtypeli  flex jc ac" :class="ps_type==2?'Driveli_funtypelic':''"
						@click="choosetype(2)">
						商家配送
					</view>
				</view>
			</view>
			<view class="Driveli flex jsb ac">
				<view class="Driveli_lable">
					打包费
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp" placeholder="请输入" type="digit" v-model="db_price" />
					<view class="Driveli_funicon">
						元
					</view>
				</view>
			</view>
			<view class="Driveli flex ac jsb">
				<view class="Driveli_lable">
					配送费
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp" placeholder="请输入" type="digit" v-model="ps_price" />
					<view class="Driveli_funicon">
						元
					</view>
				</view>
			</view>
			<view class="Driveli  flex jsb ac">
				<view class="Driveli_lable">
					起送价
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp" placeholder="请输入" type="digit" v-model="qs_price" />
					<view class="Driveli_funicon">
						元
					</view>
				</view>
			</view>
		</view>
		<view class="savebuts">
			<view class="savebut flex jc ac" @click="sub">
				保存
			</view>
		</view>
	</view>
</template>

<script>
	import {
		distributionEdit
	} from '@/api/user.js'
	import {
		store_info,
	} from '@/api/merchant.js'
	export default {
		data() {
			// 配送设置
			return {
				ps_type: '2',
				ps_price: '',
				db_price: '',
				qs_price: '',
				user: {}
			};
		},
		onLoad(e) {
			this.getinfo()
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		methods: {
			async getinfo() {
				let res = await store_info()
				this.user = res.data;
				this.ps_type = this.user.ps_type || '';
				this.ps_price = this.user.ps_price || '';
				this.db_price = this.user.db_price || '';
				this.qs_price = this.user.qs_price || '';
				uni.setStorageSync('shopuser', this.user)
			},
			choosetype(type) {
				this.ps_type = type;
			},
			sub() {
				distributionEdit({
					store_id: this.shopuser.id,
					ps_type: this.ps_type,
					ps_price: this.ps_price,
					db_price: this.db_price,
					qs_price: this.qs_price
				}).then(res => {
					if (res.code == 1) {
						uni.showToast({
							title: res.msg,
							icon: "none"
						})
						this.getinfo()
					} else {
						uni.showToast({
							title: res.msg,
							icon: "none"
						})
					}
					console.log(res)
				})
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
	.savebuts {
		width: 100%;
		padding: 24rpx 32rpx 30rpx 32rpx;
		box-sizing: border-box;
		position: fixed;
		bottom: 0;
		left: 0;

		.savebut {
			width: 100%;
			height: 80rpx;
			background: #E40030;
			border-radius: 8rpx 8rpx 8rpx 8rpx;
			font-weight: 500;
			font-size: 30rpx;
			color: #FFFFFF;
		}
	}

	.Drive {
		padding: 24rpx 32rpx;
		box-sizing: border-box;
		background: #fff;
		margin: auto;
		border-radius: 20rpx;

		.Drivetitle {
			font-weight: bold;
			font-size: 34rpx;
			color: #333333;
		}

		.Driveli {
			padding: 36rpx 0;
			box-sizing: border-box;

			.Driveli_lable {
				font-size: 28rpx;
				color: #333333;
			}
		}

		.Driveli_funinps {
			.Driveli_funinp {
				// font-weight: bold;
				font-size: 34rpx;
				color: #333333;
				text-align: right;
			}

			.Driveli_funicon {
				font-size: 28rpx;
				color: #333333;
				margin-left: 24rpx;
			}
		}


		.Driveli_funtype {
			.Driveli_funtypeli {
				width: 160rpx;
				height: 56rpx;
				background: #F2F2F2;
				border-radius: 58rpx 58rpx 58rpx 58rpx;
				font-size: 24rpx;
				color: #333333;
				margin-left: 94rpx;
			}

			.Driveli_funtypelic {
				background: #E40030 !important;
				color: #FFFFFF !important;
			}

		}


	}
</style>