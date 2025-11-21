<template>
	<view class="AddAddress">
		<view class="bx">

			<view class="flex jsb ac mb50">
				<view class="bigtt  bigttll">
					模板名称
					<text class="bigtt_bi" style="color:red;margin-right:10rpx;">*</text>
				</view>
				<view class=" textarea1 boxsizing ml20">
					<input class="inp" type="text" v-model="namec" placeholder="请输入" />
				</view>
			</view>

			<view class="flex jsb ac mb50">
				<view class="bigtt  bigttll">
					商品类型
					<text class="bigtt_bi" style="color:red;margin-right:10rpx;">*</text>
				</view>
				<view class=" textarea1 boxsizing ml20">
					<u-radio-group v-model="radiovalue1" placement="row" @change="groupChange">
						<u-radio size="32" iconSize="18" activeColor="#E40030" labelSize="18"
							:customStyle="{marginRight: '8px'}" v-for="(item, index) in radiolist1" :key="index"
							:label="item.name" :name="item.type" @change="radioChange">
						</u-radio>
					</u-radio-group>
				</view>
			</view>


			<view class="flex jsb ac mb50">

				<view class="bigtt  bigttll">
					商品属性<text class="bigtt_bi" style="color:red;margin-right:10rpx;opacity: 0;">*</text>
				</view>
				<view class=" textarea1 boxsizing ml20">
					<input class="inp" type="text" v-model="name" placeholder="请输入" />
				</view>
			</view>
			<view class=" flex je ac mb50">
				<view class="lAddBut" @click="addTT()">
					新增属性
				</view>
			</view>

			<view class="spulist" v-for="(item,index) in spu" :key="index">
				<view class="flex jsb ac mb50" style="background: #fff;">
					<view class="bigtt bigtt_bib  flex ac">
						属性名：<text class="mr30">{{item.name}}</text> <u-icon name="close-circle-fill" color="red"
							size="38" @click="delTT(index)"></u-icon>
					</view>
				</view>
				<view class="flex jsb ac w100 mb50">
					<view class="bigtt  bigttll ">
						商品规格
					</view>
					<view class=" textarea1B boxsizing f1">
						<input class="inp" type="text" v-model="items[index].itemt" placeholder="请输入" />
					</view>
				</view>
				<view class=" flex je ac ">
					<view class="lAddBut" @click="addTTT(index)">
						新增规格
					</view>
				</view>
				<view class="flex flexwrap  w100 mt30">
					<view class=" textarea1c boxsizing flex ac" v-for="(ite,inde) in item.item" :key="inde">
						<text class="addTTT_t">{{ite}}</text>
						<u-icon name="close-circle-fill" color="red" size="32" @click="delTTT(index,inde)"></u-icon>
					</view>
				</view>
			</view>



			<view class="bcbut flex jc ac" @click="addAddress">
				保存
			</view>
		</view>


		<view class="Bench"></view>
		<!-- <Loading :show="Data.loading_show"></Loading> -->
	</view>
</template>

<script>
	import {
		skuTemplate,
		skuTemplateDetail
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
				// imgurl: config.serverimg,
				loading_show: true,

				name: '',
				namec: '',
				item: '',
				spu: [],
				spus: [],


				editid: null,
				items: [],


				radiolist1: [{
						name: '单规格',
						disabled: false,
						type: 1
					},
					{
						name: '多规格',
						disabled: false,
						type: 2
					},

				],
				// u-radio-group的v-model绑定的值如果设置为某个radio的name，就会被默认选中
				radiovalue1: 1,
			};
		},
		onLoad(opt) {
			if (JSON.stringify(opt) != '{}') {
				this.AddOrEdit = opt.type;
				if (opt.type == 'edit') {
					this.editid = opt.id;
					skuTemplateDetail({
						id: this.editid
					}).then(res => {
						if (res.code == 1) {
							this.namec = res.data.name;
							this.radiovalue1 = res.data.type;
							if (res.data.spu) {
								console.log('存在')
								let lsarr = res.data.spu;
								let arrs = [];
								let arr = JSON.parse(JSON.stringify(lsarr))
								arr.forEach(item => {
									arrs.push({
										name: item.name,
										item: item.item ? item.item.split(',') : [],
										// itemt: ''
									})
									this.items.push({
										itemt: ''
									})
								})
								this.spu = arrs;
							}
						} else {}
					})

					return
				}
			}
		},
		computed: {
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		methods: {
			groupChange(n) {
				if (n == 1) {
					if (this.spu.length > 1) {
						// this.spus = JSON.parse(JSON.stringify(this.spu))
						this.spu = [this.spu[0]]
					}
				}
				//恢复多规格
				if (n == 2) {
					// if (this.spus.length > 1) {
					// 	// this.spu = JSON.parse(JSON.stringify(this.spus))
					// }
				}
				console.log('groupChange', n);
			},
			radioChange(n) {
				console.log('radioChange', n);
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
			addTT() {
				if (this.radiovalue1 == 1 && this.spu.length == 1) {
					uni.showToast({
						title: '单规格不可新增~',
						icon: "none"
					})
					return
				}
				console.log(this.name)
				if (!this.name) {
					uni.showToast({
						title: '请输入属性~',
						icon: "none"
					})
					return
				}
				this.spu.push({
					name: this.name,
					item: [],
					// itemt: ''
				})
				this.items.push({
					itemt: ''
				})
				this.name = '';
			},
			addTTT(index) {
				console.log(this.items[index].itemt)
				if (!this.items[index].itemt) {
					uni.showToast({
						title: '请输入规格~',
						icon: "none"
					})
					return
				}
				this.spu[index].item.push(this.items[index].itemt)
				this.items[index].itemt = '';
			},
			delTT(index) {
				if (this.spu[index]) {
					this.spu.splice(index, 1)
				}
			},
			delTTT(index, inde) {
				if (this.spu[index].item[inde]) {
					this.spu[index].item.splice(inde, 1)
				}
			},
			addAddress() {
				// this.$public.public.throttle(() => {
				let obj = [];
				let arr = JSON.parse(JSON.stringify(this.spu))

				arr.forEach(ite => {
					obj.push({
						name: ite.name,
						item: ite.item.join(',')
					})
				})

				console.log(obj, '存')
				// return
				if (this.AddOrEdit == 'add') {
					skuTemplate({
						store_id: this.shopuser.id,
						operation_type: 'add',
						name: this.namec,
						type: this.radiovalue1,
						spu: obj,
					}).then(res => {
						if (res.code == 1) {
							if (this.AddOrEdit == 'add') {
								uni.showToast({
									title: '添加成功',
									icon: "none"
								})
							} else {
								uni.showToast({
									title: '修改成功',
									icon: "none"
								})
							}
							setTimeout(() => {
								uni.navigateBack()
							}, 500)

						} else {
							uni.showToast({
								title: res.msg,
								icon: "none"
							})
						}
					})
				}
				if (this.AddOrEdit == 'edit') {
					skuTemplate({
						store_id: this.shopuser.id,
						operation_type: 'edit',
						name: this.namec,
						type: this.radiovalue1,
						spu: obj,
						id: this.editid
					}).then(res => {
						if (res.code == 1) {
							if (this.AddOrEdit == 'add') {
								uni.showToast({
									title: '添加成功',
									icon: "none"
								})
							} else {
								uni.showToast({
									title: '修改成功',
									icon: "none"
								})
							}
							setTimeout(() => {
								uni.navigateBack()
							}, 500)

						} else {
							uni.showToast({
								title: res.msg,
								icon: "none"
							})
						}
					})
				}

				// }, 2000, true)

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
	.mb50 {
		margin-bottom: 50rpx;
	}

	.mt30 {
		margin-top: 30rpx;
	}

	.mr30 {
		margin-right: 30rpx;
	}



	.spulist {
		padding: 30rpx;
		box-sizing: border-box;
		border-radius: 12rpx;
		background: #fff;
		margin-bottom: 30rpx;
	}

	.bigtt_bib {
		// font-weight: bold;
	}

	.addTTT_t {
		margin-right: 10rpx;
	}

	.lAddBut {
		padding: 10rpx 30rpx;
		box-sizing: border-box;
		color: #fff;
		background: #E40030;
		border-radius: 8rpx;
	}

	.addresslists {
		background: #f5f5f5;
		padding: 50rpx 30rpx 30rpx 30rpx;
		box-sizing: border-box;
		border-radius: 12rpx 12rpx 0 0;

		.cc {
			margin-bottom: 30rpx;

			.close {
				font-size: 32rpx;
				color: #999;
			}

			.confirm {
				font-size: 23rpx;
				color: #8E97FE;
			}
		}


		.addresslists_t {
			margin-bottom: 30rpx;
		}


		.addresslists_tt {
			font-size: 38rpx;

		}

		.addresslists_ttc {
			font-weight: bold;
			color: #8E97FE;
		}
	}

	.addresslist {
		width: 100%;
		height: 600rpx;
		// padding: 30rpx;
		box-sizing: border-box;
		background: #f5f5f5;

		.addresslist_li {
			padding: 30rpx;
			box-sizing: border-box;
			border-radius: 8rpx;
			background: #FFFFFF;
			margin-bottom: 30rpx;
		}

		.addresslist_lic {

			background: #8E97FE !important;
			color: #fff !important;

		}
	}

	.bigttll {
		width: 75px;
		white-space: nowrap;
		font-weight: 0 !important;
		font-size: 28rpx;
		color: #3D3D3D;
		margin-right: 20rpx;
	}

	.bcbut {
		width: 701rpx;
		height: 81rpx;
		background: #E40030;
		border-radius: 8rpx;
		font-size: 30rpx;
		color: #FFFFFF;
		position: fixed;
		bottom: 60rpx;
	}

	.textarea1 {
		width: 537rpx;
		padding: 30rpx;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #232323;
		background: #fff;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
		// margin-left: 50rpx;
	}

	.textarea1B {
		// width: 537rpx;
		padding: 30rpx;
		// background: #ffffff;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #232323;
		background: #F9F9F9;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
		// margin-left: 50rpx;
	}

	.textarea1c {
		padding: 20rpx 30rpx;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #000;
		background: #F9F9F9;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
		margin-left: 20rpx;
		margin-bottom: 20rpx;

	}

	.textarea3 {
		width: 537rpx;
		padding: 30rpx;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #232323;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
	}

	.textarea2 {
		width: 537rpx;
		padding: 30rpx;
		height: 161rpx;
		// background: #ffffff;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #232323;
		background: #F9F9F9;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
		// margin-left: 20rpx;
	}

	.bx {
		padding: 36rpx 30rpx;
		box-sizing: border-box;

		.inp {
			width: 100%;
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
			margin-left: 14rpx;
			margin-right: 20rpx;
		}
	}
</style>