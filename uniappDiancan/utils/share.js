// 默认分享
// export default {
// 	created() {
// 		//#ifdef MP-WEIXIN
// 		wx.showShareMenu({
// 			withShareTicket: true,
// 			menus: ['shareAppMessage', 'shareTimeline']
// 		});
// 		//#endif
// 	},
// }
// main.js导入
// import share from "@/utils/share.js"
// Vue.mixin(share)
// 设置分享内容
module.exports = {
	onShow() {
		// let pages = getCurrentPages();
		// if (pages.length >= 1) {
		// 	this.$x.pageRouter = (pages[pages.length - 1]).route
		// 	if (this.$x.pageRouter) {
		// 		this.$x.mpShare = {
		// 			title: uni.getStorageSync('public').share_title, // 分享标题
		// 			path: uni.getStorageSync('public').share_route, // 默认为当前页面路径
		// 			imageUrl: uni.getStorageSync('public').img // 默认为当前页面的截图
		// 		}
		// 	}
		// 	return;
		// }
	},
	// 分享到好友
	onShareAppMessage(el) {
		let userInfo = uni.getStorageSync('user')
		let share_path;
		if (userInfo) {
			share_path = '/pages/index/index?pid=' + userInfo.id
		} else {
			share_path = '/pages/index/index'
		}
		return {
			title: uni.getStorageSync('public').share_title,
			imageUrl: uni.getStorageSync('public').img,
			path: share_path
		}
	},
	// 分享到朋友圈
	onShareTimeline() {
		let userInfo = uni.getStorageSync('user')
		let share_path;
		if (userInfo) {
			share_path = '/pages/index/index?pid=' + userInfo.id
		} else {
			share_path = '/pages/index/index'
		}
		return {
			title: uni.getStorageSync('public').share_title,
			imageUrl: uni.getStorageSync('public').img,
			path: share_path
		}
	}
}
// 2.在main.js中引入
// 全局分享
// const $x = {};
// Vue.prototype.$x = $x;
// let share = require('common/share.js');
// Vue.mixin(share);