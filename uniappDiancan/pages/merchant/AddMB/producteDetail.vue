			<template>
				<view class="">
					<view class="inp boxsizing">
						<view class="contentBox">
							<!-- 富文本编辑器 -->
							<view class='wrapper'>
								<view class='toolbar' @tap="format">
									<view :class="formats.bold ? 'ql-active' : ''" class="iconfont icon-zitijiacu"
										data-name="bold">
									</view>
									<view :class="formats.italic ? 'ql-active' : ''" class="iconfont icon-zitixieti"
										data-name="italic">
									</view>
									<view :class="formats.underline ? 'ql-active' : ''" class="iconfont icon-zitixiahuaxian"
										data-name="underline"></view>
									<view :class="formats.strike ? 'ql-active' : ''" class="iconfont icon-zitishanchuxian"
										data-name="strike"></view>
									<!-- #ifndef MP-BAIDU -->
									<view :class="formats.align === 'left' ? 'ql-active' : ''" class="iconfont icon-zuoduiqi"
										data-name="align" data-value="left"></view>
									<!-- #endif -->
									<view :class="formats.align === 'center' ? 'ql-active' : ''"
										class="iconfont icon-juzhongduiqi" data-name="align" data-value="center"></view>
									<view :class="formats.align === 'right' ? 'ql-active' : ''" class="iconfont icon-youduiqi"
										data-name="align" data-value="right"></view>
									<view :class="formats.align === 'justify' ? 'ql-active' : ''"
										class="iconfont icon-zuoyouduiqi" data-name="align" data-value="justify"></view>
									<!-- #ifndef MP-BAIDU -->
									<view :class="formats.lineHeight ? 'ql-active' : ''" class="iconfont icon-line-height"
										data-name="lineHeight" data-value="2"></view>
									<view :class="formats.letterSpacing ? 'ql-active' : ''"
										class="iconfont icon-Character-Spacing" data-name="letterSpacing" data-value="2em">
									</view>
									<view :class="formats.marginTop ? 'ql-active' : ''"
										class="iconfont icon-722bianjiqi_duanqianju" data-name="marginTop" data-value="20px">
									</view>
									<view :class="formats.marginBottom ? 'ql-active' : ''"
										class="iconfont icon-723bianjiqi_duanhouju" data-name="marginBottom" data-value="20px">
									</view>
									<!-- #endif -->

									<view class="iconfont icon-clearedformat" @tap="removeFormat"></view>

									<!-- #ifndef MP-BAIDU -->
									<view :class="formats.fontFamily ? 'ql-active' : ''" class="iconfont icon-font"
										data-name="fontFamily" data-value="Pacifico"></view>
									<view :class="formats.fontSize === '24px' ? 'ql-active' : ''" class="iconfont icon-fontsize"
										data-name="fontSize" data-value="24px"></view>
									<!-- #endif -->
									<view :class="formats.color === '#0000ff' ? 'ql-active' : ''"
										class="iconfont icon-text_color" data-name="color" data-value="#0000ff"></view>
									<view :class="formats.backgroundColor === '#00ff00' ? 'ql-active' : ''"
										class="iconfont icon-fontbgcolor" data-name="backgroundColor" data-value="#00ff00">
									</view>
									<view class="iconfont icon-date" @tap="insertDate"></view>
									<view class="iconfont icon--checklist" data-name="list" data-value="check"></view>
									<view :class="formats.list === 'ordered' ? 'ql-active' : ''"
										class="iconfont icon-youxupailie" data-name="list" data-value="ordered"></view>
									<view :class="formats.list === 'bullet' ? 'ql-active' : ''" class="iconfont icon-wuxupailie"
										data-name="list" data-value="bullet"></view>

									<view class="iconfont icon-undo" @tap="undo"></view>
									<view class="iconfont icon-redo" @tap="redo"></view>

									<view class="iconfont icon-outdent" data-name="indent" data-value="-1"></view>
									<view class="iconfont icon-indent" data-name="indent" data-value="+1"></view>
									<view class="iconfont icon-fengexian" @tap="insertDivider"></view>
									<view class="iconfont icon-charutupian" @tap="insertImage"></view>
									<view :class="formats.header === 1 ? 'ql-active' : ''" class="iconfont icon-format-header-1"
										data-name="header" :data-value="1"></view>
									<view :class="formats.script === 'sub' ? 'ql-active' : ''" class="iconfont icon-zitixiabiao"
										data-name="script" data-value="sub"></view>
									<view :class="formats.script === 'super' ? 'ql-active' : ''"
										class="iconfont icon-zitishangbiao" data-name="script" data-value="super"></view>

									<view class="iconfont icon-shanchu" @tap="clear"></view>

									<view :class="formats.direction === 'rtl' ? 'ql-active' : ''"
										class="iconfont icon-direction-rtl" data-name="direction" data-value="rtl"></view>
								</view>

								<view class="toolsLine">

								</view>

								<view class="editor-wrapper">
									<editor id="editor" class="ql-container" placeholder="开始输入..." show-img-size show-img-toolbar
										show-img-resize @statuschange="onStatusChange" @input="editorInput" :read-only="readOnly"
										@ready="onEditorReady">
									</editor>
								</view>

							</view>
						</view>


					</view>
					<view class="bcbuts flex jc ac">
						<view class="bcbut flex jc ac" @tap="save">
							保存
						</view>
					</view>
				</view>

			</template>


			<script>
				import {
					mapState
				} from "vuex"
				import config from "@/config.js"
				// import tools from "@/tools/index.js"
				export default {
					onLoad(opt) {
						this.content = this.producteDetail;
						// uni.getStorageSync('producteDetail');
						// console.log(,'富文本')
					},
					onReady(opt) {},
					data() {
						return {
							content: '', //富文本编辑器内容(富文本形式)
							readOnly: false,
							formats: {},
						};
					},
					computed: {
						...mapState(['producteDetail'])
					},
					methods: {

						//以下是editor 富文本编辑器的方法
						//监听富文本编辑器的输入
						editorInput(e) {
							console.log('this.content', this.content);
							this.content = e.detail.html;
						},
						readOnlyChange() {
							this.readOnly = !this.readOnly
						},
						onEditorReady() {
							let that = this;
							//初始化要有延迟
							setTimeout(() => {
								uni.createSelectorQuery().select('#editor').context((res) => {
									this.editorCtx = res.context
									that.editorCtx.setContents({
										// tools.$store('user').editor ||
										html: that.content,
										success: result => {
											console.log('初始化内容成功 ', result)
										},
										fail: err => {
											console.log('初始化内容失败 ', err)
										}
									})

								}).exec()
							}, 500)
						},
						undo() {
							this.editorCtx.undo()
						},
						redo() {
							this.editorCtx.redo()
						},
						format(e) {
							let {
								name,
								value
							} = e.target.dataset
							if (!name) return
							this.editorCtx.format(name, value)
						},
						onStatusChange(e) {
							const formats = e.detail
							this.formats = formats
						},
						insertDivider() {
							this.editorCtx.insertDivider({
								success: function() {
									console.log('insert divider success')
								}
							})
						},
						clear() {
							uni.showModal({
								title: '清空编辑器',
								content: '确定清空编辑器全部内容？',
								success: res => {
									if (res.confirm) {
										this.editorCtx.clear({
											success: function(res) {
												console.log("clear success")
											}
										})
									}
								}
							})
						},
						removeFormat() {
							this.editorCtx.removeFormat()
						},
						insertDate() {
							const date = new Date()
							const formatDate = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`
							this.editorCtx.insertText({
								text: formatDate
							})
						},
						getImgSrc(tempSrc) {
							let that = this;
							that.uploadFilePromise(tempSrc, 1);
						},
						uploadFilePromise(url, type) {
							uni.showLoading({
								title: '上传中',
								mask: true,
							})
							let that = this;
							let types = '';
							if (type == 1) {
								types = 'image'
							} else {
								types = 'video'
							}

							return new Promise((resolve, reject) => {
								let a = uni.uploadFile({
									url: config.serverHost + '/addons/fzdc/common/upload', // 真实的接口地址
									fileType: types,
									filePath: url,
									name: 'file',
									header: {
										token: uni.getStorageSync('userinfo').token
									},
									success: (res) => {

										that.editorCtx.insertImage({
											//插入图片的地址
											src: JSON.parse(res.data).data.fullurl,
											alt: '图像',
											success: function() {
												console.log('insert image success')
											}
										})
										uni.hideLoading();

									},
									fail(err) {
										uni.showToast({
											title: '上传失败',
											icon: 'error'
										})
									}
								});
							})
						},
						insertImage() {
							uni.chooseImage({
								count: 1,
								success: (res) => {
									this.getImgSrc(res.tempFilePaths[0])
								}
							})
						},
						insertImage() {
							uni.chooseImage({
								count: 1,
								success: (res) => {
									this.getImgSrc(res.tempFilePaths[0])
								}
							})
						},
						save() {
							let that = this;

							// tools.$public.public.throttle(() => {

							this.$store.commit('setproducteDetail', that.content)
							setTimeout(() => {
								uni.navigateBack()
							}, 300)

							// }, 2000, true)
						}
					},
				}
			</script>
			<style lang="scss">
				@import "/style/editor.css";
				@import "/style/editor-icon.css";

				.inp {
					height: auto;
					padding: 30rpx;
					background: #F6F7FB;
					border-radius: 16rpx 16rpx 16rpx 16rpx;
					// margin-top: 30rpx;
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

				.iconfont {
					font-size: 24px !important;
					margin-right: 10rpx !important;
					margin-bottom: 10rpx !important;
				}

				.toolbar {
					box-sizing: border-box;
					border-bottom: 0;
					font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
					display: flex;
					flex-wrap: wrap;
				}
			</style>