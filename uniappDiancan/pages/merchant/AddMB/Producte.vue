<template>
	<view class="">
		<view class="plank">

		</view>
		<view class="producteinfos">
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					商品标题
				</view>
				<view class="producteinfor">
					{{title.length}}/30
				</view>
			</view>
			<view class="title">
				<textarea maxlength="30" auto-height class="titleinp" v-model="title" placeholder="请输入" type="text"
					@input="titleinp">
				</textarea>
			</view>
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					商品缩略图
				</view>
			</view>
			<view class="upimg">
				<u-upload width="160" height="160" uploadIcon="plus" uploadText="上传图片" :fileList="fileList1"
					@afterRead="afterRead" @delete="deletePic" name="1" multiple :maxCount="1">
				</u-upload>
			</view>
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					详情图
				</view>
			</view>
			<view class="upimg">
				<u-upload width="160" height="160" uploadIcon="plus" uploadText="上传图片" :fileList="fileList2"
					@afterRead="afterRead" @delete="deletePic" name="2" multiple :maxCount="10">
				</u-upload>
			</view>
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					商品描述
				</view>
			</view>
			<view class="title">
				<textarea auto-height class="titleinp" v-model="desc" placeholder="请输入" type="textarea" />
			</view>
			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					商品标签
				</view>
			</view>
			<view class="title">
				<view class=" flex jsb ac">
					<input auto-height class="titleinp" v-model="tagt" placeholder="请输入" type="textarea" />
					<view class="tagAdd" @click="addTTT">
						添加
					</view>
				</view>
				<view class="flex flexwrap  w100 mt30">
					<view class=" textarea1c boxsizing flex ac" v-for="(ite,inde) in tags" :key="inde">
						<text class="addTTT_t">{{ite}}</text>
						<u-icon name="close-circle-fill" color="red" size="32" @click="delTTT(inde)"></u-icon>
					</view>
				</view>
			</view>

			<view class="producteinfo flex jsb ac">
				<view class="producteinfot">
					商品分类
				</view>
				<view class="producteinfor tr flex ac" @click="classshow=true">
					<text v-if="JSON.stringify(classObj)=='{}'">请选择</text>
					<text v-else>{{classObj.name}}</text>
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>

				</view>
			</view>

		</view>
		<view class="plank">

		</view>
		<view class="producteinfos">
			<view class="producteinfo flex jsb ac ">
				<view class="producteinfot">
					商品规格
				</view>
				<view class="producteinfor tr flex ac">
					<u-radio-group v-model="skuVal" placement="row" @change="skuChange">
						<u-radio size="32" iconSize="18" activeColor="#E40030" labelSize="18"
							:customStyle="{marginBottom: '8px',marginRight: '8px'}" v-for="(item, index) in skuType"
							:key="index" :label="item.name" :name="item.type">
						</u-radio>
					</u-radio-group>
				</view>
			</view>
			<!-- <view class="title">
				<input class="titleinp" v-model="title" placeholder="请输入商品标题" type="text" />
			</view> -->
		</view>
		<view class="plank">

		</view>
		<view class="producteinfos">

			<view class="flex jsb ac w100 producteinfo" v-if="isexist">
				<!-- active_numprice_l -->
				<view class="producteinfot flex ac jsb ">
					属性模板
				</view>
				<view class="producteinfor flex ac" @click="openTemplate">
					<text v-if="chhoseGGobj.id==0 || chhoseGGobj.id==undefined">请选择</text>
					<text v-else>{{chhoseGGobj.name}}</text>
					<u-icon name="arrow-right" color="#333" size="28"></u-icon>
				</view>
			</view>

			<view class="producteinfo flex jsb ac bborder" v-if="!isexist">
				<view class="producteinfot">
					商品价(￥)
				</view>
				<view class="producteinfor tr">
					<input class="titleinp" v-model="price" placeholder="请输入" type="digit" />
				</view>
			</view>
			<view class="producteinfo flex jsb ac bborder" v-if="!isexist">
				<view class="producteinfot">
					划线价(￥)
				</view>
				<view class="producteinfor tr">
					<input class="titleinp" v-model="line_price" placeholder="需高于售卖价" type="digit" />
				</view>
			</view>

			<view class="producteinfo flex jsb ac bborder" v-if="!isexist">
				<view class="producteinfot">
					商品库存
				</view>
				<view class="producteinfor tr">
					<input class="titleinp" v-model="stock" placeholder="请输入" type="number" />
				</view>
			</view>


			<view class="mb30" v-if="GGlistObj.length">

			</view>
			<view class="uni-container" v-if="GGlistObj.length">
				<uni-table border stripe emptyText="暂无更多数据">
					<!-- 表头行 -->
					<uni-tr>
						<uni-th align="center">属性</uni-th>
						<uni-th align="center">原价</uni-th>
						<uni-th align="center">现价</uni-th>

						<uni-th align="center">库存</uni-th>

						<uni-th align="center">商品编码</uni-th>
						<uni-th align="center">图片</uni-th>
						<uni-th align="center">状态</uni-th>
					</uni-tr>
					<!-- 表格数据行 -->

					<uni-tr v-for="(item,index) in GGlistObj" :key="index">
						<uni-td width="200">{{item.attr}}</uni-td>

						<uni-td>
							<input type="digit" v-model="item.market_price" placeholder="请输入" />
						</uni-td>
						<uni-td>
							<input type="digit" v-model="item.price" placeholder="请输入" />
						</uni-td>

						<uni-td>
							<input type="number" v-model="item.stock" placeholder="请输入" />
						</uni-td>

						<uni-td>
							<input type="text" v-model="item.sn" placeholder="请输入" />
						</uni-td>
						<uni-td>
							<image class="GGimg" :src="item.image" v-if="item.image" mode="aspectFit">
							</image>
							<view class="GGimg flex jc ac" @click.stop="ChooseImg(item)" v-else>
								<u-icon name="photo" color="#333" size="28"></u-icon>
							</view>
						</uni-td>
						<uni-td>
							<u-switch :activeValue="1" :inactiveValue="0" activeColor="#E40030"
								v-model="item.status"></u-switch>
						</uni-td>
						<!-- <uni-td>{{item.lease_price}}</uni-td> -->
					</uni-tr>
				</uni-table>

			</view>

			<!-- <view class="active_numprice_line"></view> -->

		</view>
		<view class="plank" v-if="!isexist">

		</view>
		<view class="producteinfos">


			<view class="producteinfo flex jsb ac ">
				<view class="producteinfot">
					出货类型
				</view>
				<view class="producteinfor tr flex ac">
					<u-radio-group v-model="shipment" placement="row" @change="groupChange">
						<u-radio size="32" iconSize="18" activeColor="#E40030" labelSize="18"
							:customStyle="{marginBottom: '8px',marginRight: '8px'}" v-for="(item, index) in radiolist1"
							:key="index" :label="item.name" :name="item.type" @change="radioChange">
						</u-radio>
					</u-radio-group>
				</view>
			</view>


			<!-- <view class="title">
				<input class="titleinp" v-model="title" placeholder="请输入商品标题" type="text" />
			</view> -->
		</view>
		<view class="plank">

		</view>
		<view class="producteinfos">
			<view class="producteinfo bborder">
				<view class=" flex jsb ac ">
					<view class="producteinfot">
						商品详情
					</view>
					<view class="producteinfor flex ac" @click="gopage('/pages/merchant/AddMB/producteDetail')">
						去编辑 <u-icon name="arrow-right" color="#333" size="28"></u-icon>
					</view>
				</view>
				<view class="producteDetail" v-if="producteDetail">
					<u-parse :content="producteDetail"></u-parse>
					<!-- <input class="titleinp" v-model="title" placeholder="请输入商品标题" type="text" /> -->
				</view>
			</view>

		</view>

		<view class="bcbuts flex jc ac">
			<view class="bcbut flex jc ac" @tap="save">
				保存
			</view>
		</view>
		<view class="Bench">

		</view>
		<u-popup :show="show" closeable :round="10" mode="bottom" @close="show=false">
			<view class="productelist ">
				<view class="productelist_t flex jc ac">
					属性模板
				</view>
				<GGList :list="GGlist" @newuse="newuse"></GGList>
				<view class="cancle flex jc ac" @click="show=false">
					关闭
				</view>
				<view class="GoAddTextBut" @click="gopage('/pages/merchant/AddMB/Add?type=add')">
					去添加
				</view>
				<!-- <view class="" style="width: 100%;height: 40rpx;" v-if="!tools.$public.public.isIos()"></view> -->
			</view>
		</u-popup>
		<u-popup :show="classshow" closeable :round="10" mode="bottom" @close="classshow=false">
			<view class="productelist ">
				<view class="productelist_t flex jc ac">
					分类列表
				</view>

				<classList :list="classList" @newuse="classnewuse"></classList>
				<view class="cancle flex jc ac" @click="classshow=false">
					关闭
				</view>
				<view class="GoAddTextBut" @click="gopage('/pages/merchant/AddMB/addClass')">
					去添加
				</view>
				<!-- <view class="" style="width: 100%;height: 40rpx;" v-if="!tools.$public.public.isIos()"></view> -->
			</view>
		</u-popup>
	</view>
</template>

<script>
	import GGList from "@/components/GGList/List.vue"
	import classList from "@/components/classList/List.vue"
	import config from '@/config.js'
	import {
		mapState
	} from "vuex"
	import {
		skuTemplateList,
		skuTemplate,
		goodsAdd,
		goodsCategory,
		goodsDetail
	} from '@/api/user.js'
	export default {
		data() {
			return {
				issuccess: false,
				classshow: false,
				classObj: {},
				price: '',
				line_price: '',
				stock: '',
				Httpimg: config.uploadImgUrl,
				isexist: true,
				show: false,
				classList: [],
				GGlist: [], //规格模板列表
				chhoseGGobj: {}, //选中的模板
				GGlistObj: [], //sku 规格表  ，填数据的

				spu: [],

				title: "",
				desc: '',
				fileList1: [], //略缩图
				fileList2: [], //详情图
				tagt: '',
				tags: [],


				// 基本案列数据
				radiolist1: [{
						name: '自取',
						disabled: false,
						type: 1
					},
					{
						name: '外卖',
						disabled: false,
						type: 2
					},
					{
						name: '均可',
						disabled: false,
						type: 3
					}
				],
				// u-radio-group的v-model绑定的值如果设置为某个radio的name，就会被默认选中
				shipment: 3,
				editId: '',
				goodsDetail: '',

				// 商品规格选择
				skuType: [
					{ name: '无属性单规格', type: 1 },
					{ name: '单规格', type: 2 },
					{ name: '多规格', type: 3 },
				],
				skuVal: 2
			}
		},
		onLoad(obj) {
			if (JSON.stringify(obj) != '{}') {
				this.editId = obj.id;
				this.getgoodsDetail()
			}
		},
		onUnload() {
			this.$store.commit('setproducteDetail', '')
		},
		onShow() {
			// this.getlist()
			this.goodsCategorys()
		},
		computed: {
			...mapState(['producteDetail']),
			shopuser() {
				return uni.getStorageSync('shopuser')
			}
		},
		components: {
			GGList,
			classList
		},
		methods: {
			titleinp(e) {
				if (e.detail.cursor < 30) {
					this.title = e.detail.value;
				} else {
					this.title = this.title.substring(0, 30)
				}
				console.log(this.title)
			},
			getgoodsDetail() {
				goodsDetail({
					goods_id: this.editId
				}).then(res => {
					if (res.code == 1) {
						this.goodsDetail = res.data;
						this.title = res.data.title || '';
						this.desc = res.data.desc || '';
						this.tags = res.data.tags || '';
						this.shipment = res.data.shipment * 1;
						if (res.data.image) {
							this.fileList1.push({
								url: res.data.image
							})
						}
						if (res.data.images) {
							let arr = res.data.images.split(',')
							arr.forEach(item => {
								this.fileList2.push({
									url: item.includes('http') ? item : this.Httpimg + item
								})
							})
						}
						this.$set(this.chhoseGGobj, 'type', res.data.type)
						this.$set(this.chhoseGGobj, 'spu', res.data.spu)
						this.$set(this.chhoseGGobj, 'id', res.data.template_id)
						this.$set(this.chhoseGGobj, 'name', res.data.template_name)
						this.$set(this.classObj, 'name', res.data.type_name)

						if (this.chhoseGGobj.spu.length == 0) {
							this.GGlistObj = {};
							this.spu = [];
							this.isexist = false;
							this.price = res.data.price;
							this.line_price = res.data.line_price;
							this.stock = res.data.stock;
							this.skuVal = 1
						} else {
							if (res.data.type == 1) {
								this.skuVal = 2
							} else {
								this.skuVal = 3
							}
						}

						this.$set(this.classObj, 'id', res.data.type_id)

						this.GGlistObj = res.data.sku;
						// this.$set(this.GGlistObj, 'id', res.data.type_id)
						this.$store.commit('setproducteDetail', res.data.json)
						// if () {

						// }
					}
				})
			},
			goodsCategorys() {
				goodsCategory({
					store_id: this.shopuser.id,
					type: 'normal'
				}).then(res => {
					if (res.code == 1) {
						this.classList = res.data;
					}
				})
			},
			save() {
				if (this.issuccess) {
					return
				}
				let obj = null;
				if (!this.fileList1.length) {
					uni.showToast({
						title: "请上传图片",
						icon: "none"
					})
					return
				}
				if (JSON.stringify(this.classObj) == '{}') {
					uni.showToast({
						title: "请选择商品分类",
						icon: "none"
					})
					return
				}
				let image = [];
				let images = [];
				this.fileList1.forEach(item => {
					image.push(item.url)
				})
				this.fileList2.forEach(item => {
					images.push(item.url)
				})
				if (!this.fileList2.length) {
					uni.showToast({
						title: "请上传图片",
						icon: "none"
					})
					return
				}
				if (this.skuVal == 1) {
					if (!this.price) {
						uni.showToast({
							title: "请输入价格",
							icon: "none"
						})
						return
					}
					if (!this.line_price) {
						uni.showToast({
							title: "请输入划线价",
							icon: "none"
						})
						return
					}
					if (!this.stock) {
						uni.showToast({
							title: "请输入库存",
							icon: "none"
						})
						return
					}
					if (this.line_price * 1 < this.price * 1) {
						uni.showToast({
							title: "划线价必须大于商品价",
							icon: "none"
						})
						return
					}
				} else {
					if (JSON.stringify(this.chhoseGGobj) == "{}" || this.chhoseGGobj.id == 0) {
						uni.showToast({
							title: "请选择规格",
							icon: "none"
						})
						return
					}
				}
				if (!this.title) {
					uni.showToast({
						title: "请选填写名称",
						icon: "none"
					})
					return
				}
				// if (this.line_price * 1 < this.price * 1) {
				// 	uni.showToast({
				// 		title: "划线价必须大于商品价",
				// 		icon: "none"
				// 	})
				// 	return
				// }


				if (this.chhoseGGobj.type == 1) {
					//是否是由属性的单规格，false 为 无规格
					if (this.isexist) {
						//单规格
						obj = {
							store_id: this.shopuser.id,
							type_id: this.classObj.id,
							title: this.title,
							desc: this.desc,
							json: this.producteDetail,
							type: this.chhoseGGobj.type,
							price: this.price,
							line_price: this.line_price,
							stock: this.stock,
							image: image[0],
							images: images,
							tags: this.tags,
							spu: this.chhoseGGobj.spu,
							sku: this.GGlistObj,
							template_id: this.chhoseGGobj.id,
							shipment: this.shipment
						}
					} else {
						//无规格
						obj = {
							store_id: this.shopuser.id,
							type_id: this.classObj.id,
							title: this.title,
							desc: this.desc,
							json: this.producteDetail,
							type: this.chhoseGGobj.type,
							price: this.price,
							line_price: this.line_price,
							stock: this.stock,
							image: image[0],
							images: images,
							tags: this.tags,
							spu: [],
							sku: [],
							template_id: this.chhoseGGobj.id,
							shipment: this.shipment
						}
					}

				} else {
					obj = {
						store_id: this.shopuser.id,
						type_id: this.classObj.id,
						title: this.title,
						desc: this.desc,
						json: this.producteDetail,
						type: this.chhoseGGobj.type,
						price: this.price,
						line_price: this.line_price,
						stock: this.stock,
						image: image[0],
						images: images,
						tags: this.tags,
						spu: this.chhoseGGobj.spu,
						sku: this.GGlistObj,
						template_id: this.chhoseGGobj.id,
						shipment: this.shipment
					}
				}
				if (this.editId) {
					obj.id = this.editId;
				}
				// console.log(obj)
				// return
				goodsAdd(obj).then(res => {
					if (res.code == 1) {
						uni.showLoading()
						uni.showToast({
							title: res.msg,
							icon: "none"
						})
						this.issuccess = true;
						setTimeout(() => {
							uni.navigateBack()
							uni.hideLoading()
						}, 500)
					} else {
						uni.hideToast()
						uni.showModal({
							title: '提示',
							content: res.msg,
						})
						// uni.showToast({
						// 	title: res.msg,
						// 	icon: "none"
						// })
					}
					console.log(res, '属性')
				})
			},
			ChooseImg(item) {
				let that = this;
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: function(res) {
						that.uploadFilePromise(res.tempFiles[0].path).then(ress => {
							item.image = ress.fullurl;
							console.log(ress, '换图')
						})
					}
				});
			},
			classnewuse(e) {
				this.classshow = false;
				this.classObj = e;
			},
			newuse(e) {
				this.show = false;
				this.chhoseGGobj = e;
				console.log(this.chhoseGGobj)
				// this.FromData.tempalte_id = e.id;
				// this.FromData.spu = JSON.parse(this.chhoseGGobj.spu);
				if (this.chhoseGGobj.spu.length) {
					if (this.chhoseGGobj.sku) {
						this.isexist = true;
						this.GGlistObj = JSON.parse(JSON.stringify(this.chhoseGGobj.sku))
						this.spu = this.chhoseGGobj.spu;
					}
				} else {
					this.GGlistObj = {};
					this.spu = [];
					this.isexist = false;
				}


			},
			async getlist(type) {
				let val = ''
				if (this.skuVal == 2) {
					val = 1
				}
				if (this.skuVal == 3) {
					val = 2
				}
				let {
					code,
					data,
					msg
				} = await skuTemplateList({
					// page: this.page,
					store_id: this.shopuser.id,
					type: val
				})
				if (code == 1) {
					if (type) {
						this.GGlist.push(...data);
					} else {
						this.GGlist = data;
					}
					// this.last_page = data.last_page;
				}
				console.log('列表', data)
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
				return new Promise((resolve, reject) => {
					let a = uni.uploadFile({
						url: config.serverHost + '//addons/fzdc/common/upload', //上传图片api
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

			// 商品规格选择
			skuChange(e) {
				// console.log(e)
				if (e == 1) {
					this.isexist = false
					this.$set(this.chhoseGGobj, 'type', 1)
					this.$set(this.chhoseGGobj, 'spu', [])
					this.$set(this.chhoseGGobj, 'id', 0)
					this.$set(this.chhoseGGobj, 'name', '')
					this.GGlistObj = []
				} else {
					this.isexist = true
					this.price = ''
					this.line_price = ''
					this.stock = ''
				}
			},
			openTemplate() {
				this.show = true
				this.getlist()
			}
		}
	}
</script>

<style lang="scss" scoped>
	.bborder {
		border-bottom: 2rpx solid #F2F2F2;
	}

	.producteDetail {
		margin-top: 30rpx;
	}

	.GGimg {
		width: 30px;
		height: 30px;
	}


	.GoAddTextBut {
		color: #E40030;
		font-size: 26rpx;
		margin-top: 10rpx;
		text-align: center;
	}


	.cancle {
		width: 690rpx;
		margin: auto;
		padding: 20rpx 0;
		border-radius: 8rpx;
		color: #fff;
		font-size: 26rpx;
		background: #E40030;

	}

	.productelist {
		width: 100%;
		box-sizing: border-box;

		.productelist_t {
			font-size: 38rpx;
			font-weight: bold;
			margin-bottom: 20rpx;
			padding-top: 30rpx;
			box-sizing: border-box;
		}

		.productelist_list {
			.productelist_list_li {
				padding: 20rpx;
				box-sizing: border-box;
				border-radius: 12rpx;
				background: #fff;
			}

			.productelist_list_li_img {
				width: 120rpx;
				height: 120rpx;
				border-radius: 8rpx;
				margin-right: 20rpx;
			}

			.productelist_list_li_tt {
				flex-direction: column;
				height: 120rpx;
				margin-right: 20rpx;
			}

			.productelist_list_li_tt1 {
				width: 100%;
				font-size: 26rpx;
				font-weight: bold;

			}

			.productelist_list_li_tt2 {
				font-size: 26rpx;
				color: orangered;
			}
		}

		.productelist_but {
			width: 100%;
			border-radius: 8rpx;
			padding: 20rpx;
			box-sizing: border-box;
			background: #E40030;
			color: #fff;
		}
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