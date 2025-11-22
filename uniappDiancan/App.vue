<script>
	import {
		auto_login,
		get_openid
	} from '@/api/user.js'
	import {
		userInfo,
		common
	} from "@/api/public.js"
	export default {
		onLaunch() {
			setTimeout(() => {
				uni.setStorageSync('option', 1)
				uni.setStorageSync('tableNumber', false)
				uni.setStorageSync('addAdish', false)
			}, 10800000)
			uni.setStorageSync('option', 1)

			uni.login({
				success: (res) => {
					// 静默登录
					auto_login({
						code: res.code
					}).then(loginres => {
						if (loginres.code == 1) {
							if (loginres.data) {
									uni.setStorageSync('userinfo', loginres.data.userinfo)
									this.getopenid()
									this.userInfoApi()
							}
						}
						uni.hideToast()
					})
					uni.hideToast()
				},
				fail: (err) => {
					console.log('eerr', err);
				}
			})
		},
		onLoad() {
			setTimeout(() => {
				uni.setStorageSync('option', 1)
				uni.setStorageSync('tableNumber', false)
				uni.setStorageSync('addAdish', false)
			}, 10800000)
			uni.setStorageSync('option', 1)
		},
		methods: {
			// 获取openid
			getopenid() {
				uni.login({
					success: (res) => {
						// 获取get_openid
						get_openid({
							code: res.code
						}).then(res => {
							if (res.code == 1) {
								uni.setStorageSync('openid', res.data.openid)
							}
						})
					},
					fail: (err) => {
						console.log('eerr', err);
					}
				})
			},
			async commonApi() {
				let data = await common()
				if (data.code == 1) {
					uni.setStorageSync('public', data.data)
					if (!uni.getStorageSync('user')) {
						uni.setStorageSync('user', data.data)
					}
				}
			},
			// 手机或微信登录成功后执行
			async userInfoApi() {
				let that = this
				let data = await userInfo()
				if (data.code == 1) {
					uni.setStorageSync('user', data.data)
					// 页面重载
					const pages = getCurrentPages()
					console.log('页面页面', pages)
					// 声明一个pages使用getCurrentPages方法
					const curPage = pages[pages.length - 1]
					// 声明一个当前页面
					curPage.onLoad(curPage.options) // 传入参数
					curPage.onShow()
					curPage.onReady()
					// that.commonApi()
				} else {
					uni.showToast({
						title: data.msg,
						icon: "none"
					})
				}
			},
		},

		onShow: function() {},
		onHide: function() {}
	}
</script>

<style lang="scss">
	@import "uview-ui/index.scss";

	@import url("utils/default.scss");
	@import '@/style/index.scss';

	page {
		background: #FAFAFA !important;
	}

	::v-deep .u-picker__view__column__item {
		padding-top: 10rpx;
		box-sizing: border-box;
	}

	image {
		width: 100%;
		height: 100%;
	}

	button::after {
		border: none;
	}

	button {
		background-color: transparent;
		padding-left: 0;
		padding-right: 0;
		line-height: inherit;
	}

	button {
		border-radius: 0;
	}
</style>