import App from './App'
import store from '@/store'
// #ifndef VUE3
import uView from "uview-ui";
Vue.use(uView);
import Vue from 'vue'
import vuePrototype from "./utils/wxApi.js"
import fnc from '@/utils/function.js'
Vue.prototype.$way = fnc
// 过滤器
import * as filters from './utils/filter.js'
Object.keys(filters).forEach(key => {
	Vue.filter(key, filters[key])
})

// 全局分享
const $x = {};
Vue.prototype.$x = $x;
let share = require('./utils/share');
Vue.mixin(share);

import {
	router,
	RouterMount
} from './router/router.js' //路径换成自己的

// 如此配置即可
Vue.use(router)
uni.$u.config.unit = 'rpx'
Vue.config.productionTip = false
App.mpType = 'app'
uni.$showMsg = function(title = '数据加载失败！', duration = 2000) {
	uni.showToast({
		title,
		duration,
		icon: 'none',
	})
}
// 扩展vue原型方法
Vue.prototype.$utils = {}
Object.keys(vuePrototype).forEach(k => {
	Vue.prototype[k] = vuePrototype[k]
})

// 引入uView提供的对vuex的简写法文件  就是可以让vuex的变量在每个页面都可以直接使用
let vuexStore = require('@/store/$u.mixin.js')
Vue.mixin(vuexStore)
try {
	function isPromise(obj) {
		return (
			!!obj &&
			(typeof obj === "object" || typeof obj === "function") &&
			typeof obj.then === "function"
		);
	}
	// 统一 vue2 API Promise 化返回格式与 vue3 保持一致
	uni.addInterceptor({
		returnValue(res) {
			if (!isPromise(res)) {
				return res;
			}
			return new Promise((resolve, reject) => {
				res.then((res) => {
					if (res[0]) {
						reject(res[0]);
					} else {
						resolve(res[1]);
					}
				});
			});
		},
	});
} catch (error) {}

const app = new Vue({
	store,
	...App
})
app.$mount()
// #endif

// #ifdef VUE3
import {
	createSSRApp
} from 'vue'
export function createApp() {
	const app = createSSRApp(App)
	return {
		app
	}
}
// #endif