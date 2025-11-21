<template>
	<view>
		<!-- <height :hg='20'></height> -->
		<view class="content_box">
			<view class="content">
				<view class="addmessage_box">
					<view class="addmessage">
						<view class="addmessage_1">
							收货人
						</view>
						<view class="addmessage_2">
							<input type="text" placeholder="名字" v-model="name">
						</view>
						<view class="addmessage_3">
						</view>
					</view>
					<view class="addmessage">
						<view class="addmessage_1">
							称谓
						</view>
						<view class="addmessage_2">
							<radio-group @change="radioChange" style="display: flex; align-items: center;">
								<label class="uni-list-cell uni-list-cell-pd"
									style="display: flex; align-items: center; padding: 0rpx 60rpx 0 0;"
									v-for="(item, index) in items" :key="item.value">
									<view>
										<radio color='#E40030' style="transform:scale(0.7)" :value="item.value"
											:checked="index === current" />
									</view>
									<view>{{item.name}}</view>
								</label>
							</radio-group>
						</view>
						<view class="addmessage_3">
							<!-- 	<view class="addmessage_3_btn">
								自动填写
							</view> -->
						</view>
					</view>



					<view class="addmessage">
						<view class="addmessage_1">
							手机号
						</view>
						<view class="addmessage_2">
							<input type="number" v-model="cell" :maxlength="11" placeholder="手机号码">
						</view>
						<view class="addmessage_3">
							<!-- 	<view class="addmessage_3_btn">
								自动填写
							</view> -->
						</view>
					</view>

					<!-- <picker mode="region" @change="bindRegionChange" v-model="region" :custom-item="customItem"> -->
					<view class="addmessage" @click="getaddress" style="position: relative;">
						<view class="addmessage_1">
							地址
						</view>
						<view class="addmessage_2">
							<view class="">
								<text v-if="columnsels">
									{{columnsels}}
								</text>
								<text v-else>请选择地址</text>
							</view>
						</view>
						<view class="addmessage_3" style="position: absolute; right: 0rpx;">
							<u-icon name="arrow-right" color="#979797" size="32"></u-icon>
						</view>
					</view>
					<!-- </picker> -->

					<view class="addmessage" style="border-bottom: none;">
						<view class="addmessage_1">
							门牌号
						</view>
						<view class="addmessage_2">
							<input type="text" v-model="detailed_address" placeholder="例：5号楼203室">
						</view>
						<view class="addmessage_3">

						</view>
					</view>

					<view class="addmessage" style="position: relative;" @tap="show=true">
						<view class="addmessage_1">
							标签
						</view>
						<view class="addmessage_2"
							style="width: 500rpx; display: flex; align-items: center; justify-content: space-between;">
							<view style=" width: 103rpx;
							height: 52rpx;
							border-radius: 3rpx;
							border: 2rpx solid #DDDDDD; line-height: 52rpx; text-align: center;"
								:style="{border:tagindex==index?'2rpx solid #E40030':'',color:tagindex==index?'#E40030':''}"
								v-for="(item,index) in tag" @tap="tag_(index)" :key="item">
								{{item}}
							</view>
						</view>
					</view>
					<view class="addmessage" style="position: relative;" @tap="show=true">
						<view class="addmessage_1">
							是否默认
						</view>
						<view class="addmessage_2">
							<u-switch size='34' activeColor="#333" v-model="isDefault" @change="switchchange"></u-switch>
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 	<u-picker @cancel="cancel" :show="show" @close='show=false' ref="uPicker" :closeOnClickOverlay='true'
			:columns="columns" keyName="name" @confirm="confirm" @change="changeHandler"></u-picker> -->
		<view class="btns flex jc ac">
			<button class="btn" @tap="save">保存</button>
		</view>

	</view>
</template>

<script>
	import {
		address_add,
		address_exec,
	} from "@/api/comm.js"
	import {
		forEach
	} from "lodash";
	export default {
		data() {
			return {
				name: '', //名字
				items: [{
						value: '0',
						name: '先生',
						checked: 'true'
					},
					{
						value: '1',
						name: '女士'
					}
				],
				current: 0,
				cell: '', //手机号
				detailed_address: '', //详细地址
				columnsels: '',
				tag: ['家', '公司', '学校', '其他'],
				tagindex: 0, //标签选中的
				state: '', //区分判断新增修改
				order_id: '',
				region: ['', '', ''],
				addressInfo: {},

				isDefault: false,
			};

		},
		onLoad(obj) {
			this.state = obj.state
			this.order_id = obj.order_id
			if (this.vuex_address) {
				let data = this.vuex_address
				if (data.id) {
					this.name = data.title
					this.cell = data.mobile
					this.columnsels = data.address
					this.detailed_address = data.remark
					this.isDefault = data.isDefault == 1 ? true : false
					this.addressInfo = {
						name: data.address,
						latitude: data.lat,
						longitude: data.lon
					}

					data.suffix == '先生' ? this.current = 0 : this.current = 1
					switch (data.tag) {
						case '家':
							this.tagindex = 0
							break;
						case '公司':
							this.tagindex = 1
							break;
						case '学校':
							this.tagindex = 2
							break;
						case '其他':
							this.tagindex = 3
							break;
						default:
							默认代码块
					}
				}
			}
		},
		computed: {},
		onUnload() {
			this.$store.state.vuex_address = ''
		},
		methods: {
			switchchange(e) {
				console.log('ee', e);
			},
			getaddress() {
				uni.chooseLocation({
					success: (res) => {
						this.addressInfo = res
						this.columnsels = res.name
					}
				});

			},
			radioChange: function(evt) {
				for (let i = 0; i < this.items.length; i++) {
					if (this.items[i].value === evt.detail.value) {
						this.current = i;
						break;
					}
				}
			},
			save() {
				this.add_deleteApi()
			},
			async add_deleteApi() {
				if (this.state == 0) {
					let data = await address_add({
						address_id: this.order_id,
						name: this.name,
						suffix: this.items[this.current].name,
						mobile: this.cell,
						address: this.columnsels,
						remark: this.detailed_address,
						tag: this.tag[this.tagindex],
						isDefault: this.isDefault,
						lon: this.addressInfo.longitude,
						lat: this.addressInfo.latitude,
					})
					if (data.code == 1) {
						uni.showToast({
							title: data.msg,
							icon: "success",
							success() {
								uni.navigateBack({
									delta: 1
								})

							}
						})
					} else {
						uni.showToast({
							title: data.msg,
							icon: "error"
						})
					}
				} else {
					let data = await address_exec({
						address_id: this.order_id,
						name: this.name,
						suffix: this.items[this.current].name,
						mobile: this.cell,
						address: this.columnsels,
						remark: this.detailed_address,
						tag: this.tag[this.tagindex],
						isDefault: this.isDefault,
						lon: this.addressInfo.longitude,
						lat: this.addressInfo.latitude,
					})
					if (data.code == 1) {
						uni.showToast({
							title: data.msg,
							icon: "success",
							success() {
								uni.navigateBack({
									delta: 1
								})

							}
						})
					} else {
						uni.showToast({
							title: data.msg,
							icon: "error"
						})
					}
				}
			},
			tag_(index) {
				this.tagindex = index
			},
			//点击确定按钮
			bindRegionChange: function(e) {
				this.columnsels = ''

				this.region = e.detail.value
				e.detail.value.forEach((res, index) => {
					if (index < 2) {
						this.columnsels += res + '-'
					} else {
						this.columnsels += res
					}

				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content_box {
		width: 750rpx;
		background: #FFFFFF;

		.content {
			width: 90%;
			margin: 0 auto;

			.content_name {
				height: 201rpx;
				display: flex;
				align-items: center;

				.content_name_left {
					font-size: 32rpx;
					font-weight: 500;
					color: #000000;
				}

				.content_name_right {
					width: 537rpx;
					margin-left: 20rpx;

					.content_name_right_top {
						padding-top: 30rpx;
						padding-bottom: 20rpx;
						border-bottom: 1rpx solid #EBEBEB;

						input {
							width: 100%;
							height: 100%;
						}
					}

					.content_name_right_bottom {
						padding: 34rpx 0;
					}
				}

			}

			.addmessage {
				display: flex;
				align-items: center;
				border-top: 1rpx solid #EBEBEB;
				padding: 30rpx 0;

				.addmessage_1 {
					width: 130rpx;
					font-size: 32rpx;
					font-weight: 500;
					color: #000000;
				}

				.addmessage_2 {
					font-size: 26rpx;
					font-weight: 400;
					color: #959595;
					line-height: 37rpx;
					margin-left: 40rpx;

					input {
						width: 100%;
						height: 100%;
					}
				}

				.addmessage_3 {
					.addmessage_3_btn {}
				}
			}
		}
	}

	.btns {
		width: 100%;
		position: fixed;
		bottom: 0;
		padding: 20rpx 0rpx 40rpx 0;
		box-sizing: border-box;
		background: #FFFFFF;

		.btn {
			width: 686rpx;
			height: 80rpx;
			background: #E40030;
			border-radius: 6rpx;
			margin: 0 auto;
			font-size: 32rpx !important;
			font-weight: 400 !important;
			color: #FFFFFF;
			line-height: 80rpx;
			text-align: center;
			// margin-top: 30rpx;
		}
	}
</style>