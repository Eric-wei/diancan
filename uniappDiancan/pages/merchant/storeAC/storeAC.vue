<template>
	<view class="acimg" v-if="info.status==0">
		<image src="../static/ac6.svg" mode=""></image>
	</view>
	<view style="padding-bottom: 100rpx;" v-else>
		<view class="topTips" v-if="info.status==2">
			审核失败
		</view>
		<view class="topTips2" v-if="info.status==1">
			审核成功
		</view>
		<view class="tips" v-if="user.authentication==0">
			<view class="top flexc flexs jc">
				<!-- <view class="">
					店铺认证
				</view> -->
				<view class="">
					未认证
				</view>
			</view>
			<view class="bom">
				店铺认证时通过核实店铺和身份证,来证明店铺的真实性和类型
			</view>
		</view>

		<view class="title">
			个人信息
		</view>
		<view class="card">
			<view class="card_item flexc line">
				<view class="left">
					<text>*</text>姓名
				</view>
				<input type="text" :disabled="check" class="ipt" v-model="list.title" placeholder="请输入你的姓名">
			</view>
			<view class="card_item flexc line">
				<view class="left">
					<text>*</text>身份证号
				</view>
				<input type="number" maxlength="18" style="width: 400rpx;" :disabled="check" v-model="list.id_number"
					class="ipt" placeholder="请输入你的身份证号">
			</view>
			<view class="card_item flexc ">
				<view class="left">
					<text>*</text>联系号码
				</view>
				<input type="phone" maxlength="11" :disabled="check" v-model="list.mobile" class="ipt"
					placeholder="请输入你的联系号码">
			</view>
		</view>
		<view class="title">
			店铺认证
		</view>
		<view class="card_two">
			<view class="update flexc">
				<image :src="list.store_image" mode="aspectFill" v-if="list.store_image"
					@click="check?pre(list.store_image,0):upfront(1)"></image>
				<image src="../static/ac.png" mode="" @click="upfront(1)" v-else></image>
				<view class="">
					*请上传一张店铺照片
				</view>
			</view>
			<view class="update flexc">
				<image :src="list.yy_image" mode="aspectFill" v-if="list.yy_image"
					@click="check?pre(list.yy_image,0):upfront(2)">
				</image>
				<image src="../static/ac.png" mode="aspectFill" @click="upfront(2)" v-else></image>
				<view class="">
					*请上传营业执照
				</view>
			</view>
		</view>
		<view class="title">
			店铺认证
		</view>
		<view class="card_thr">
			<view class="title">
				*请上传法人身份证证件
			</view>
			<view class="content flexc flexw flexs">
				<view class="">
					<image :src="list.font_image" mode="aspectFill" v-if="list.font_image"
						@click="check?pre(list.font_image,0):upfront(3)"></image>
					<image v-else src="../static/ac2.png" mode="aspectFill" @click="upfront(3)"></image>
				</view>
				<view class="">
					<image :src="list.back_image" mode="aspectFill" v-if="list.back_image"
						@click="check?pre(list.back_image,0):upfront(4)"></image>
					<image v-else src="../static/ac3.png" @click="upfront(4)" mode="content"></image>
				</view>
				<view class="">
					<image :src="list.hard_image" mode="aspectFill" v-if="list.hard_image"
						@click="check?pre(list.hard_image,0):upfront(5)"></image>
					<image v-else src="../static/ac4.png" @click="upfront(5)" mode="content"></image>
				</view>
				<view class="">
					<image :src="list.self_image" mode="aspectFill" v-if="list.self_image"
						@click="check?pre(list.self_image,0):upfront(6)"></image>
					<image v-else src="../static/ac5.png" @click="upfront(6)" mode="content"></image>
				</view>
			</view>
		</view>
		<view class="fixed" v-if="check==false" @click="setinfo(1)">
			<view class="btn flex jc ac">
				申请认证
			</view>
		</view>

	</view>

</template>

<script>
	import {
		store_authentication
	} from '@/api/merchant.js'
	import config from '@/config.js'
	export default {
		data() {
			return {
				check: false, //审核成功不能点 下面的按钮也得消失
				list: {
					type: 'view',
					title: "",
					id_number: '',
					mobile: "",
					store_image: '',
					yy_image: '',
					font_image: '',
					back_image: '',
					hard_image: '',
					self_image: ""
				},
				info: {},
				user: uni.getStorageSync('shopuser')
			};
		},
		onLoad() {
			this.getinfo()
		},
		methods: {
			pre(url, e) {
				uni.previewImage({
					urls: [url],
					current: e
				});
			},
			async setinfo(e) {
				let c = this.$way.hideIdCardMiddle(this.list.id_number)
				let p = this.$way.validatePhoneNumber(this.list.mobile)
				if (!c) return uni.$showMsg('请输入正确身份证')
				if (!p) return uni.$showMsg('请输入正确手机号')
				this.list.type = 'apply'
				let res = await store_authentication(this.list)
				if (res.code !== 1) return uni.$showMsg(res.msg)
				uni.navigateBack()
			},
			async getinfo(e) {
				if (e == 1) {
					this.list.type = 'apply'
				}
				let res = await store_authentication(this.list)
				if (res.code !== 1) return uni.$showMsg(res.msg)
				this.info = res.data
				let id_number = res.data.id_number
				let mobile = res.data.mobile
				if (this.info.status == 1) {
					this.check = true
					id_number = this.$way.hideIdCardNumber(res.data.id_number)
					mobile = this.$way.hidePhoneNumber(res.data.mobile)
				}
				if (this.info.status == 1 || this.info.status == 2) {
					this.list = {
						type: 'apply',
						title: res.data.title,
						id_number,
						mobile,
						store_image: res.data.store_image,
						yy_image: res.data.yy_image,
						font_image: res.data.font_image,
						back_image: res.data.back_image,
						hard_image: res.data.hard_image,
						self_image: res.data.self_image
					}
				}
			},
			// 上传图片
			upfront(e) {
				if (this.check) return
				let _that = this
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album', 'camera'], //从相册选择
					success: function(res) {
						let image = res.tempFilePaths
						uni.uploadFile({
							url: config.serverHost + '/api/common/upload', //上传图片api
							filePath: image[0],
							name: 'file',
							header: {
								token: uni.getStorageSync('userinfo').token
							},
							success: (resb) => {
								let group = JSON.parse(resb.data)
								if (e == 1) {
									_that.list.store_image = group.data.fullurl
								} else if (e == 2) {
									_that.list.yy_image = group.data.fullurl
								} else if (e == 3) {
									_that.list.font_image = group.data.fullurl
								} else if (e == 4) {
									_that.list.back_image = group.data.fullurl
								} else if (e == 5) {
									_that.list.hard_image = group.data.fullurl
								} else if (e == 6) {
									_that.list.self_image = group.data.fullurl
								}
							},
							fail(err) {}
						})
					}
				})
			},

		}

	}
</script>

<style lang="scss">
	.topTips {
		background-color: #ff140c;
		text-align: center;
		color: #fff;
		height: 100rpx;
		line-height: 100rpx;
	}

	.topTips2 {
		background-color: #b2ffd6;
		text-align: center;
		color: #fff;
		height: 100rpx;
		line-height: 100rpx;
	}

	.acimg {
		height: 100vh;
		background-color: #fff;

		image {
			width: 750rpx;
			height: 450rpx;
			margin-top: 400rpx;
		}
	}

	.fixed {
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		padding: 20rpx 0 30rpx 0;
		box-sizing: border-box;

		.btn {
			width: 671rpx;
			height: 80rpx;
			background: #E40030;
			border-radius: 8rpx;
			text-align: center;
			font-size: 32rpx;
			color: #FFFFFF;
			margin: 0 auto;
		}
	}


	.card_two {
		width: 750rpx;
		height: 350rpx;
		background: #FFFFFF;
		padding: 20rpx 40rpx;
		box-sizing: border-box;

		.update {
			margin: 20rpx 0 0;
			line-height: 136rpx;

			image {
				width: 136rpx;
				height: 136rpx;
				margin-right: 50rpx;
			}

		}
	}


	.title {
		font-size: 28rpx;
		color: #2F3337;
		margin: 20rpx 24rpx;

	}

	.line {
		border-bottom: 1rpx solid #f4f4f4;
	}

	.tips {
		width: 750rpx;
		height: 161rpx;
		background: rgba(221, 180, 92, 0.1);
		padding: 35rpx 40rpx;
		box-sizing: border-box;

		.top {
			font-size: 34rpx;
			color: #E40030;
			margin-bottom: 20rpx;
			text-align: center;
		}

		.bom {
			font-size: 24rpx;
			color: #2F3337;
			text-align: center;
		}
	}

	.card {
		background-color: #fff;
		padding: 0 40rpx;
		box-sizing: border-box;

		.card_item {
			padding: 30rpx 0;
			box-sizing: border-box;

			.left {
				width: 200rpx;

				text {
					color: red;
				}
			}
		}
	}

	.card_thr {
		width: 750rpx;
		background: #FFFFFF;
		padding: 40rpx 44rpx;
		box-sizing: border-box;

		.content {
			image {
				width: 326rpx;
				height: 260rpx;
				margin: 20rpx 0 0;
			}
		}
	}
</style>