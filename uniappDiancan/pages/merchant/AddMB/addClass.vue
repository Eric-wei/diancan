<template>
	<view class="">
		<view class="plank">

		</view>
		<view class="producteinfos">
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					分类标题
				</view>
				<!-- <view class="producteinfor">
					{{title.length}}/30
				</view> -->
			</view>
			<view class="title">
				<input class="titleinp" v-model="title" placeholder="请输入" type="text" />
			</view>
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					分类图片
				</view>
			</view>
			<view class="upimg">
				<u-upload width="160" height="160" uploadIcon="plus" uploadText="上传图片" :fileList="fileList1"
					@afterRead="afterRead" @delete="deletePic" name="1" multiple :maxCount="1">
				</u-upload>
			</view>

			<!-- <view class="producteinfo flex jsb ac bborder">
				<view class="producteinfot">
					门店是否通用
				</view>
				<view class="producteinfor tr">
					<view class="producteinfor tr flex ac">
						<u-radio-group v-model="radiovalue1" placement="row" @change="groupChange">
							<u-radio size="32" iconSize="18" activeColor="#E40030" labelSize="18"
								:customStyle="{marginRight: '8px'}" v-for="(item, index) in radiolist1" :key="index"
								:label="item.name" :name="item.type" @change="radioChange">
							</u-radio>
						</u-radio-group>
					</view>
				</view>
			</view> -->



		</view>



		<view class="bcbuts flex jc ac">
			<view class="bcbut flex jc ac" @tap="save">
				保存
			</view>
		</view>
		<view class="Bench">

		</view>
	</view>
</template>

<script>
	import config from '@/config.js'
	import {
		mapState
	} from "vuex"

	import {
		goodsCategoryAdd,
		goodsCategoryDetail
	} from "@/api/user.js"
	export default {
		data() {
			return {
				isScuess: false,
				isGeneral: false,
				title: "",
				desc: '',
				fileList1: [], //略缩图
				fileList2: [], //详情图
				tagt: '',
				tags: [],


				// 基本案列数据
				radiolist1: [{
						name: '是',
						disabled: false,
						type: 1
					},
					{
						name: '否',
						disabled: false,
						type: 2
					},
				],
				// u-radio-group的v-model绑定的值如果设置为某个radio的name，就会被默认选中
				radiovalue1: 1,
				setId: ''
			}
		},
		onLoad(obj) {
			if (JSON.stringify(obj) != "{}") {
				this.setId = obj.id;
				this.ClassDetail()
			}
		},
		onUnload() {
			this.$store.commit('setproducteDetail', '')
		},
		computed: {
			...mapState(['producteDetail']),
			shopuser() {
				return uni.getStorageSync('shopuser')
			}

		},
		methods: {
			ClassDetail() {
				goodsCategoryDetail({
					id: this.setId
				}).then(res => {
					if (res.code == 1) {
						this.title = res.data.name;
						if (res.data.icon) {
							this.fileList1 = [{
								url: res.data.icon,
							}]
						}
					}
					console.log(res)
				})
			},
			save() {
				console.log(this.fileList1)

				if (this.isScuess) {
					return
				}

				if (!this.fileList1.length) {
					uni.showToast({
						title: '请上传分类图片',
						icon: "none"
					})
					return
				}

				let obj = null;
				if (this.setId) {
					obj = {
						id: this.setId,
						store_id: this.shopuser.id,
						icon: this.fileList1[0].url,
						name: this.title
					}
				} else {
					obj = {
						store_id: this.shopuser.id,
						icon: this.fileList1[0].url,
						name: this.title
					}
				}


				goodsCategoryAdd(obj).then(res => {
					if (res.code == 1) {
						uni.showLoading()
						this.isScuess = true;
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
						setTimeout(() => {
							uni.hideLoading()
							uni.navigateBack()
						}, 500)
					} else {
						uni.hideToast()
						uni.showModal({
							title: '提示',
							content: res.msg,
						})
					}
				})
			},
			change() {

			},
			groupChange(n) {
				console.log('groupChange', n);
			},
			radioChange(n) {
				console.log('radioChange', n);
			},
			gopage(url) {
				this.show = false
				uni.navigateTo({
					url
				})
			},
			addTTT(index) {
				if (!this.tagt) {
					uni.showToast({
						title: '请输入标签~',
						icon: "none"
					})
					return
				}
				this.tags.push(this.tagt)
				this.tagt = '';
			},
			delTTT(index) {
				if (this.tags[index]) {
					this.tags.splice(index, 1)
				}
			},
			// 删除图片
			deletePic(event) {
				this[`fileList${event.name}`].splice(event.index, 1);
			},
			// 新增图片
			async afterRead(event) {
				// 当设置 multiple 为 true 时, file 为数组格式，否则为对象格式
				let lists = [].concat(event.file);
				let fileListLen = this[`fileList${event.name}`].length;
				lists.map((item) => {
					this[`fileList${event.name}`].push({
						...item,
						status: "uploading",
						message: "上传中",
					});
				});
				for (let i = 0; i < lists.length; i++) {
					const result = await this.uploadFilePromise(lists[i].url);
					let item = this[`fileList${event.name}`][fileListLen];
					this[`fileList${event.name}`].splice(
						fileListLen,
						1,
						Object.assign(item, {
							status: "success",
							message: "",
							url: result.fullurl,
						})
					);
					fileListLen++;
				}
			},
			uploadFilePromise(url) {
				// url: config.serverHost + '/api/common/upload', //上传图片api
				// filePath: url,
				// name: 'file',
				// header: {
				// 	token: uni.getStorageSync('userinfo').token
				// },
				// success: (resb) => {
				// 	let group = JSON.parse(resb.data)

				// 		_that.list.store_image = group.data.fullurl

				// },
				// fail(err) {}
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
								resolve(JSON.parse(res.data).data);
							}, 1000);
						},
					});
				});
			},
		}
	}
</script>

<style lang="scss" scoped>
	.bborder {
		border-bottom: 2rpx solid #F2F2F2;
	}


	.bcbuts {
		width: 100%;

		margin-top: 100rpx;

		.bcbut {
			width: 701rpx;
			height: 81rpx;
			background: #E40030;
			border-radius: 8rpx;
			font-size: 30rpx;
			margin: auto;
			color: #FFFFFF;

		}
	}

	.tr {
		text-align: right;
	}

	.plank {
		width: 100%;
		height: 24rpx;
	}

	.upimg {
		margin-top: 24rpx;
		padding-bottom: 24rpx;
		border-bottom: 2rpx solid #F2F2F2;

		::v-deep .u-icon__icon {
			// font-size: 11px !important;
			// margin-bottom: 20rpx;
		}
	}

	.addTTT_t {
		margin-right: 10rpx;
	}

	.textarea1c {
		padding: 20rpx 30rpx;
		border-radius: 14rpx;
		font-size: 26rpx;
		color: #000;
		background: #F9F9F9;
		border-radius: 18rpx 18rpx 18rpx 18rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		margin-top: 20rpx;

	}

	.tagAdd {
		padding: 15rpx 30rpx;
		box-sizing: border-box;
		border-radius: 8rpx;
		background: #E40030;
		color: #fff;
	}

	.producteinfos {
		padding: 0rpx 20rpx 0 20rpx;
		box-sizing: border-box;
		background: #FFFFFF;
		border-radius: 20rpx;
		width: 686rpx;
		margin: auto;

		.title {
			padding: 24rpx 0rpx;
			box-sizing: border-box;
			border-bottom: 2rpx solid #F2F2F2;
		}

		.titleinp {
			font-size: 28rpx;
			color: #AAAAAA;
		}

		.producteinfo {
			padding: 24rpx 0rpx;
			box-sizing: border-box;

			.producteinfot {
				font-size: 28rpx;
				color: #333333;
				font-weight: bold;
			}

			.producteinfor {
				font-size: 28rpx;
				color: #AAAAAA;
			}
		}
	}
</style>