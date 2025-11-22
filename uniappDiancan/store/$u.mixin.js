import {
	mapState
} from 'vuex'
import store from '@/store/index.js'


// 把vuex中的变量 全部加载在全局变量中 每一个页面都可以直接使用变量
let $uStoreKey = []
try {
	// 遍历出来vuex里面的所有key值
	$uStoreKey = store.state ? Object.keys(store.state) : []
} catch (e) {

}

// https://blog.csdn.net/qq_59747594/article/details/127700908   module.exports的解释
module.exports = {
	created() {
		// this.$u.vuex 在vuex存储数据  并判断是永久存到本地
		this.$u.vuex = (name, value) => {
			this.$store.commit('$uStore', {
				name,
				value
			})
		}
	},
	computed: {
		// 在这一步把vuex中的变量 全部加载在全局变量中 每一个页面都可以直接使用变量
		...mapState($uStoreKey)
	}
}

// 2.在main.js中引入
// 引入uView提供的对vuex的简写法文件  就是可以让vuex的变量在每个页面都可以直接使用
// let vuexStore = require('@/store/$u.mixin.js')
// Vue.mixin(vuexStore)