<template>
	<view class="box">
		<!-- <view class="tabs">
			<u-tabs :list="tablist" :current='current' lineWidth="40rpx" lineHeight='4rpx' @click="click"
				itemStyle="width:26%;height:60rpx;">
			</u-tabs>
		</view> -->
		<!-- <view class="title">
			请输入你的银行卡信息
		</view> -->
		<view class="card" v-if="type_id==1">
			<view class="card_item flexc">
				<view class="left">
					姓名
				</view>
				<input class="right" v-model="list.name" type="text" placeholder="请输入姓名">
			</view>
			<view class="card_item flexc">
				<view class="left">
					卡号
				</view>
				<input class="right" v-model="list.bank_code" type="text" placeholder="请输入银行卡卡号">
			</view>
			<view class="card_item flexc">
				<view class="left">
					手机号
				</view>
				<input class="right" maxlength="11" type="text" v-model="list.bank_mobile" placeholder="请输入银行预留手机号">
			</view>
			<view class="card_item flexc">
				<view class="left">
					验证码
				</view>
				<input class="yzm" v-model="list.code" maxlength="4" type="number" placeholder="请输入验证码">
				<view class="btn" v-if="yzmshow" @click="setTimer">
					获取验证码
				</view>
				<view class="gray" v-else>
					{{time}}s后重新获取
				</view>
			</view>
		</view>
		<view class="card" v-if="type_id==0">
			<view class="card_item flexc">
				<view class="left" style="margin-top: 2rpx;">
					账号
				</view>
				<input class="right" v-model="list.zh_code" type="text" placeholder="请输入您的微信账号">
			</view>
			<view class="title">
				请上传您的微信收款码
			</view>
			<image class="addimg" :src="list.img" mode="" v-if="list.img" @click="upfront(1)"></image>
			<image class="addimg" src="../static/ac.png" mode="" @click="upfront(1)" v-else></image>
		</view>
		<view class="card" v-if="type_id==2">
			<view class="card_item flexc">
				<view class="left" style="margin-top: 2rpx;">
					账号
				</view>
				<input class="right" v-model="list.zh_code" type="text" placeholder="请输入您的支付宝账号">
			</view>
			<view class="title">
				请上传您的支付宝收款码
			</view>
			<image class="addimg" :src="list.img" mode="" v-if="list.img" @click="upfront(1)"></image>
			<image class="addimg" src="../static/ac.png" mode="aspectFill" @click="upfront(1)" v-else></image>
		</view>
		<view class="qrbtn" @click="add">
			绑定银行卡
		</view>
	</view>
</template>

<script>
	import {
		add_withdrawal_account,
		withdrawal_account_detail
	} from '@/api/merchant.js'
	import {
		send
	} from '@/api/user.js'
	import config from '@/config.js'
	export default {
		data() {
			return {
				current: 1,
				yzmshow: true,
				time: 60,
				timer: null,
				list: {
					type: 1,
					bank_code: '',
					// bank_name: "",
					bank_mobile: "",
					code: "",
					zh_code: '',
					img: ''
				},
				type_id: 1,
				tablist: [],
				user: uni.getStorageSync('shopuser'),
				issuccess: false,
				editId: ''
			};
		},
		onLoad(obj) {
			let that = this;
			if (JSON.stringify(obj) != '{}') {
				this.editId = obj.id;
				withdrawal_account_detail({
					id: this.editId
				}).then(res => {
					if (res.code == 1) {
						that.$set(that.list, 'name', res.data.name)
						that.$set(that.list, 'bank_code', res.data.bank_code)
						that.$set(that.list, 'bank_mobile', res.data.bank_mobile)
					}
				})
			}
			let common = uni.getStorageSync('public')
			this.$nextTick(() => {
				// common.tx_type.forEach((item, index) => {
				// 	if (index == 0) {
				// 		this.type_id = item
				// 	}
				// 	this.tablist.push({
				// 		id: item,
				// 		name: item == 0 ? "添加微信" : item == 1 ? '添加银行卡' : "添加支付宝"
				// 	})
				// })

			})

		},
		methods: {
			click(e) {
				this.current = e.index
				this.type_id = e.id
				this.list = {
					type: '',
					bank_code: '',
					bank_name: "",
					bank_mobile: "",
					code: "",
					zh_code: '',
					img: ''
				}
			},
			async add() {
				if (this.issuccess) return
				if (this.user.user_type !== '1') return uni.$showMsg('您没有权限')
				if (this.type_id == 1) {
					this.list.type = 1
					this.list.bank_code = this.list.bank_code.replace(/\s+/g, '')
					let bank = this.$way.isValidBankCard(this.list.bank_code)
					let mobile = this.$way.validatePhoneNumber(this.list.bank_mobile)
					if (!this.list.name) return uni.$showMsg('请输入姓名')
					if (!bank) return uni.$showMsg('请输入正确卡号')
					if (!mobile) return uni.$showMsg('请输入正确手机号')
				} else if (this.type_id == 0) {
					// 微信
					if (!this.list.zh_code) return uni.$showMsg('请输入账号')
					if (!this.list.img) return uni.$showMsg('请上传收款码')
					this.list.type = 2
				} else {
					// 支付宝
					if (!this.list.zh_code) return uni.$showMsg('请输入账号')
					if (!this.list.img) return uni.$showMsg('请上传收款码')
					this.list.type = 3
				}
				if (this.editId) {
					this.$set(this.list, 'id', this.editId)
				}
				let res = await add_withdrawal_account(this.list)
				if (res.code == 1) {
					this.issuccess = true;
					uni.showToast({
						title: '提交成功,请等待审核',
						icon: "none"
					})
					setTimeout(() => {
						uni.navigateBack()
					}, 1000)
				} else {
					uni.$showMsg(res.msg)
				}
			},
			setTimer() {
				send({
					mobile: this.list.bank_mobile
				}).then(res => {
					if (res.code == 1) {
						if (this.yzmshow) {
							this.yzmshow = false
							this.timer = setInterval(() => {
								this.time--
								if (this.time < 0) {
									clearInterval(this.timer)
									this.yzmshow = true
									this.time = 60
								}
							}, 1000)
						}
					}
				})

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
								_that.list.img = group.data.fullurl
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
	.addimg {
		width: 200rpx;
		height: 200rpx;
		margin: 20rpx 0;
	}

	.right {
		width: 200px;
	}

	.box {
		padding-top: 30rpx;
		box-sizing: border-box;
	}

	.tabs {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		background-color: #fff;
		padding: 20rpx;
		box-sizing: border-box;
	}

	.gray {

		height: 50rpx;
		background: #FFFFFF;
		border-radius: 25rpx;
		border: 1rpx solid #ffffff;
		font-size: 24rpx;
		color: #777777;
		text-align: center;
		line-height: 50rpx;
		margin-top: -10rpx;
		margin-left: 20rpx;
	}

	page {
		padding: 0rpx 26rpx;
		box-sizing: border-box;
	}


	.title {
		font-size: 30rpx;
		font-weight: 600;
		color: #333333;
		margin: 16rpx 0 0;
	}

	.card {
		padding: 0 40rpx;
		box-sizing: border-box;
		background-color: #fff;
		border-radius: 20rpx;
		margin-bottom: 40rpx;

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
				border: 2rpx solid #E40030;
				font-size: 24rpx;
				color: #E40030;
				text-align: center;
				line-height: 50rpx;
				margin-top: -10rpx;
				margin-left: 20rpx;
			}
		}
	}

	.qrbtn {
		width: 700rpx;
		height: 98rpx;
		background: #E40030;
		border-radius: 8rpx;
		text-align: center;
		line-height: 98rpx;

		font-size: 32rpx;
		color: #FFFFFF;
	}
</style>