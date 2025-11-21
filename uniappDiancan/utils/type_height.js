let barHeight = 0
let StatusBar = 0
let barHightTop = ''
export function cellphone() {
	let systemInfo = uni.getSystemInfoSync();

	let custom = wx.getMenuButtonBoundingClientRect();

	var statusBarHeight = systemInfo.statusBarHeight

	//手机状态栏的高度statusBarHeight
	let CustomBar = custom.bottom + custom.top - systemInfo.statusBarHeight;

	barHeight = CustomBar || 0
	StatusBar = statusBarHeight || 0
	//StatusBar + 'px' barHeight - StatusBar+ 'px'  barHeight + StatusBar+ 'px'
	barHightTop = StatusBar * 2

	return barHightTop + 10
}