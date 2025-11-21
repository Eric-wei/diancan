<template>
	<view class="">
		<view class="topplank">

		</view>
		<view class="Drive">
			<view class="Drivetitle">
				基本信息
			</view>
			<view class="Driveli flex jsb ac">
				<view class="Driveli_lable">
					头像
				</view>
				<view class="flex ac">
					<view class="Driveli_funavatar flex ac userset" @click.stop="chooseAvatar()">
						<image class="w100h100 " :src="image" mode="aspectFill"></image>
					</view>
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>
				</view>
			</view>
			<view class="Driveli flex jsb ac">
				<view class="Driveli_lable">
					店铺名
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp" v-model="title" type="digit" />
				</view>
			</view>
			<view class="Driveli  flex jsb ac ">
				<view class="Driveli_lable">
					门店电话
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp userset" v-model="tel" type="number" />
					<!-- <u-icon name="arrow-right" color="#333" size="28"></u-icon> -->
				</view>
			</view>
			<view class="Driveli flex  jsb Drivelibottom">
				<view class="Driveli_lable">
					门店地址
				</view>
				<view class="Driveli_funinps flex ac" @click="chooseaddress">
					<view class="userset">
						{{address}}
					</view>
					<!-- <input class="Driveli_funinp userset" type="digit" /> -->
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>
				</view>
			</view>

		</view>
		<view class="topplank">

		</view>
		<view class="Drive pb0">
			<view class="Drivetitle ">
				营业信息
			</view>
			<view class="Driveli flex jsb ac Drivelibottom">
				<view class="Driveli_lable">
					营业时间
				</view>
				<view class="Driveli_funinps flex ac">
					<text @click.stop="showstart=true">{{start_times}}</text>
					-
					<text @click.stop="showend=true"> {{end_times}}</text>
					<!-- <input class="Driveli_funinp" type="digit" /> -->
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>
				</view>
			</view>

			<view class="Driveli flex jsb ac Drivelibottom" v-if="shopuser.user_type==1">
				<view class="Driveli_lable">
					提现账号
				</view>
				<view class="Driveli_funinps flex ac" @click="gopage('/pages/merchant/cashID/cashID')">
					去管理
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>
				</view>
			</view>

			<!-- 	<view class="Driveli flex ac jsb Drivelibottom">
				<view class="Driveli_lable">
					外卖起送价
				</view>
				<view class="Driveli_funinps flex ac">
					<input class="Driveli_funinp" type="digit" />
					<view class="Driveli_funicon">
						元
					</view>
				</view>
			</view> -->

		</view>
		<view class="savebuts">
			<view class="savebut flex jc ac" @click="sub">
				保存
			</view>
		</view>

		<u-datetime-picker :show="showstart" v-model="start_times" mode="time" @confirm='confirmA()'
			@cancel='cancelA()'></u-datetime-picker>
		<u-datetime-picker :show="showend" v-model="end_times" mode="time" @confirm='confirmB()'
			@cancel='cancelB()'></u-datetime-picker>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		store_info
	} from '@/api/merchant.js'
	import {
		storeEdit
	} from '@/api/user.js'

	export default {
		data() {
			// 配送设置
			return {
				drivetype: '',
				user: {},
				image: '',
				title: '',
				address: '',
				lon: '',
				lat: '',
				tel: '',
				start_times: '',
				end_times: '',
				showstart: false,
				showend: false
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
			chooseAvatar() {
				let that = this;
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: async function(res) {
						let url = await that.uploadFilePromise(res.tempFilePaths[0])
						if (url.code == 1) {
							that.image = url.data.fullurl;
						}
						console.log(url);
					}
				});
			},
			uploadFilePromise(url) {
				return new Promise((resolve, reject) => {
					let a = uni.uploadFile({
						url: config.serverHost + '/addons/fzdc/common/upload', //上传图片api
						filePath: url,
						name: 'file',
						header: {
							token: uni.getStorageSync('userinfo').token
						},
						success: (res) => {
							setTimeout(() => {
								resolve(JSON.parse(res.data));
							}, 1000);
						},
					});
				});
			},
			confirmA() {
				this.showstart = false;
			},
			cancelA() {
				this.showstart = false;
			},
			confirmB() {
				this.showend = false;
			},
			cancelB() {
				this.showend = false;
			},
			sub() {
				storeEdit({
					store_id: this.user.id,
					title: this.title,
					address: this.address,
					tel: this.tel,
					start_times: this.start_times,
					end_times: this.end_times,
					image: this.image,
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

				})
			},
			chooseaddress() {
				let that = this;
				uni.chooseLocation({
					success(res) {
						console.log(res)
						that.address = res.address + res.name;
						that.lon = res.longitude;
						that.lat = res.latitude;
					}
				})
			},
			async getinfo() {
				let res = await store_info()
				this.user = res.data;
				this.title = this.user.title;
				this.address = this.user.address;
				this.tel = this.user.tel;
				this.start_times = this.user.start_times;
				this.end_times = this.user.end_times;
				this.image = this.user.image;
				uni.setStorageSync('shopuser', this.user)
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
	.topplank {
		width: 100%;
		height: 24rpx;
	}

	.pb0 {
		padding-bottom: 0 !important;
	}

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


	.userset {
		margin-right: 10rpx;
	}

	.Drive {
		padding: 24rpx 20rpx;
		box-sizing: border-box;
		background: #fff;
		width: 686rpx;
		margin: auto;
		border-radius: 20rpx;

		.Drivetitle {
			font-weight: bold;
			font-size: 34rpx;
			color: #333333;
		}

		.Drivelibottom {
			border-width: 0 !important;
		}

		.Driveli {

			padding: 24rpx 0;
			box-sizing: border-box;
			border-bottom: 1rpx solid #F2F2F2;


			.Driveli_lable {
				font-size: 28rpx;
				color: #333333;
			}

			.Driveli_funavatar {
				width: 64rpx;
				height: 64rpx;
				background: #D9D9D9;
				border-radius: 0rpx 0rpx 0rpx 0rpx;
				border-radius: 999%;
				overflow: hidden;


			}
		}

		.Driveli_funinps {
			max-width: 260px;
			text-align: right;

			.Driveli_funinp {
				font-size: 28rpx;
				color: #777777;
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