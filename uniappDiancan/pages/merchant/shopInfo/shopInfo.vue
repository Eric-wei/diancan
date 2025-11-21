<template>
	<view style="padding-bottom: 120rpx;">
		<view class="card">
			<view class="title">
				准确填写联系电话,便于上线经营时顾客联系
			</view>
			<view class="card_item flexc">
				<view class="left">
					联系人
				</view>
				<input class="right" v-model="list.contacts" type="text" placeholder="请输入联系人姓名">
			</view>
			<view class="card_item flexc">
				<view class="left">
					联系号码
				</view>
				<input class="right" v-model="list.tel" type="text" placeholder="请输入手机号码">
			</view>
			<view class="card_item flexc">
				<view class="left">
					店铺名称
				</view>
				<input class="right" v-model="list.title" type="text" placeholder="请输入">
			</view>
			<view class="card_item flexc">
				<view class="left">
					店铺别名
				</view>
				<input class="right" v-model="list.alias_title" type="text" placeholder="请输入">
			</view>
			<view class="card_item flexc">
				<view class="left">
					店铺地址
				</view>
				<picker mode="region" @change='change'>
					<view class="picker" style="color: black;" v-if="list.address">{{list.address }}</view>
					<view class="picker" v-else>
						请选择
					</view>
				</picker>
			</view>
		</view>
		<view class="card_two flexc">
			<view class="" style="margin-right: 40rpx;">
				门脸图
			</view>
			<!-- <u-upload height='280rpx' width='480rpx' :fileList="fileList1" @afterRead="afterRead" @delete="deletePic"
				name="1" multiple :maxCount="1">
			</u-upload> -->
			<image :src="list.image" mode="aspectFill" @click="upfront" class="mlimg" v-if="list.image"></image>
			<view class="img" @click="upfront" v-else>
				<image src="../static/ac1.png" mode=""></image>
				<view class="">
					点击上传
				</view>
			</view>
		</view>
		<view class="card_thr">
			<view class="card_item flexc flexs line">
				<view class="left">
					营业开始时间
				</view>
				<view class="flexc" @click="setShow(1)">
					{{list.start_times || '请选择'}}
					<u-icon name="arrow-right" size="28"></u-icon>
				</view>
			</view>
			<view class="card_item flexc flexs">
				<view class="left">
					营业结束时间
				</view>
				<view class="flexc" @click="setShow(2)">
					{{list.end_times || '请选择'}}
					<u-icon name="arrow-right" size="28"></u-icon>
				</view>
			</view>
		</view>
		<u-datetime-picker visibleItemCount='10' :show="show" @confirm='confirm' @cancel='cancel' v-model="value1"
			mode="time">
		</u-datetime-picker>

		<view class="fixed">
			<view class="btn" @click="update">
				保存
			</view>
		</view>

	</view>
</template>

<script>
	import {
		store_update,
		store_info
	} from '@/api/merchant.js'
	import config from '@/config.js'
	export default {
		data() {
			return {
				show: false,
				value1: '',
				city: '',
				fileList1: [],
				list: {
					contacts: '',
					tel: "",
					title: '',
					alias_title: '',
					address: '',
					image: '',
					start_times: '',
					end_times: ''
				},
				mark: 1

			};
		},
		onLoad() {
			this.getinfo()
		},
		methods: {
			async getstore_info() {
				let res = await store_info()
				if (res.code !== 1) return
				uni.setStorageSync('shopuser', res.data)

			},
			confirm(e) {
				if (this.mark == 1) {
					this.list.start_times = e.value
				} else {
					this.list.end_times = e.value
				}
				this.show = false
			},
			cancel() {
				this.show = false
			},
			setShow(e) {
				this.show = true
				this.mark = e
			},
			// 
			getinfo() {
				let user = uni.getStorageSync('shopuser')
				this.list = {
					contacts: user.contacts,
					tel: user.tel,
					title: user.title,
					alias_title: user.alias_title,
					address: user.address,
					image: user.image,
					start_times: user.start_times,
					end_times: user.end_times
				}
			},
			// 修改信息
			async update() {
				let res = await store_update(this.list)
				if (res.code == 1) {
					this.getstore_info()
					uni.navigateBack()
				}

			},

			change(e) {
				this.list.address = e.detail.value[0] + e.detail.value[1] + e.detail.value[2]
			},
			// 上传图片
			upfront(e) {
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
								_that.list.image = group.data.fullurl

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
	.mlimg {
		width: 479rpx;
		height: 280rpx;
	}

	.fixed {
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		height: 120rpx;

		.btn {
			width: 700rpx;
			height: 98rpx;
			background: linear-gradient(90deg, #52C2F9 0%, #00ABFF 100%);
			border-radius: 14rpx;
			margin: 0 auto;
			line-height: 98rpx;
			text-align: center;
			font-size: 32rpx;
			color: #FFFFFF;
		}
	}

	page {
		background: #FAFAFA;
		padding: 24rpx;
		box-sizing: border-box;
	}

	.picker {
		font-size: 26rpx;
		color: #777777;
	}

	.card {
		padding: 20rpx 40rpx;
		box-sizing: border-box;
		background-color: #fff;

		.title {
			width: 620rpx;
			height: 64rpx;
			background: #A0E0FF;
			border-radius: 10rpx;
			text-align: center;
			font-size: 24rpx;
			color: #0097E1;
			line-height: 64rpx;
		}

		.card_item {
			padding: 34rpx 0;
			box-sizing: border-box;
			border-bottom: 1rpx solid #f5f5f5;

			.left {
				font-size: 28rpx;
				color: #333333;
				width: 140rpx;
			}

			.yzm {
				width: 300rpx;
			}

			.btn {
				width: 158rpx;
				height: 50rpx;
				background: #FFFFFF;
				border-radius: 25rpx;
				border: 2rpx solid #0CAFFE;
				font-size: 24rpx;
				color: #0CAFFE;
				text-align: center;
				line-height: 50rpx;
				margin-top: -10rpx;
				margin-left: 20rpx;
			}
		}
	}

	.card_two {
		width: 700rpx;
		height: 349rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		margin: 20rpx 0;
		padding: 40rpx 34rpx;
		box-sizing: border-box;

		.img {
			width: 479rpx;
			height: 280rpx;
			background: #f8f8f8;
			text-align: center;
			font-size: 26rpx;

			image {
				width: 100rpx;
				height: 100rpx;
				margin: 60rpx auto 0;
			}
		}
	}

	.card_thr {
		padding: 20rpx 40rpx;
		box-sizing: border-box;
		background-color: #fff;

		.card_item {
			padding: 34rpx 0;
			box-sizing: border-box;

			.left {
				font-size: 28rpx;
				color: #333333;
			}
		}
	}

	.line {
		border-bottom: 1rpx solid #f5f5f5;
	}
</style>