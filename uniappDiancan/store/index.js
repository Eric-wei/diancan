import Vue from "vue"
import Vuex from 'vuex'
Vue.use(Vuex)
import createPersistedState from 'vuex-persistedstate'



let lifeData = {}

try {
	// 尝试获取本地是否存在lifeData变量，第一次启动APP是不存在的
	lifeData = uni.getStorageSync('lifeData')
} catch (e) {

}

// 需要永久存储，且下次APP启动需要取出来的用的，在state中 的变量名字
let saveStatekeys = [
	'vuex_token',
	'islogin',
	'producteDetail'
]
// 保存变量到本地存储
const saveLifeData = (key, value) => {
	// indexOf返回搜索到的下标  没有返回-1
	// 判断变量名是否在需要永久存储的数组中 有的话存储到本地 没有则忽略
	if (saveStatekeys.indexOf(key) != -1) {
		let tmp = uni.getStorageSync('lifeData')
		// 判断是否本地有lifeData没有就放一个{}  因为第一次进入小程序没有lifeDate  
		tmp = tmp ? tmp : {}
		// 把传过来的变量放在tmp中 下一把存在本地 永久存储
		tmp[key] = value
		// 把所有的变量重新存在本地
		uni.setStorageSync('lifeData', tmp);
	}
}

// saveStatekeys在saveStatekeys中写下参数名  并本地存储在lifeData就可以永久存在

const store = new Vuex.Store({
	state: {
		vuex_token: lifeData.vuex_token ? lifeData.vuex_token : '',
		vuex_address: null,
		islogin: false,
		producteDetail: ''
	},
	mutations: {
		setproducteDetail(state, val) {
			state.producteDetail = val;
			// uni.setStorageSync('producteDetail', val)
			// console.log(state.producteDetail, val, '触发信息')
		},
		$uStore(state, payload) {
			// 判断是否多层级调用，state中为对象存在的情况，诸如user.info.score = 1
			let nameArr = payload.name.split('.'); //根据.分割 判断他有几级
			let saveKey = '';
			let len = nameArr.length; //传过来的参数的层级
			if (len >= 2) { //单层级变量
				let obj = state[nameArr[0]]; //第一层级
				for (let i = 1; i < len - 1; i++) {
					obj = obj[nameArr[i]];
				}
				obj[nameArr[len - 1]] = payload.value;
				saveKey = nameArr[0];
			} else {
				// 单层级变量，在state就是一个普通变量的情况  
				state[payload.name] = payload.value; //直接存到state中
				saveKey = payload.name; //给saveKey赋值  在下面判断是否存到本地中
			}
			// 判断是否保存变量到本地，见顶部函数定义
			// saveKey变量名    state[saveKey]存储的内容 value
			saveLifeData(saveKey, state[saveKey])
		},
		setislogin(state, flag) {
			state.islogin = flag;
		},
		// 获取胶囊高度
		cellphone(state) {
			let barHeight = 0
			let StatusBar = 0
			let barHightTop = ''
			let systemInfo = uni.getSystemInfoSync();
			let custom = uni.getMenuButtonBoundingClientRect();
			var statusBarHeight = systemInfo.statusBarHeight
			//手机状态栏的高度statusBarHeight
			let CustomBar = custom.bottom + custom.top - systemInfo.statusBarHeight;
			barHeight = CustomBar || 0
			StatusBar = statusBarHeight || 0
			//StatusBar + 'px' barHeight - StatusBar+ 'px'  barHeight + StatusBar+ 'px'
			barHightTop = StatusBar * 2

			state.cellphone = barHightTop
		}
	},
	plugins: [
		createPersistedState({
			storage: {
				getItem: key => uni.getStorageSync(key), // 读取缓存
				setItem: (key, value) => uni.setStorageSync(key, value), // 写入缓存
				removeItem: key => uni.removeStorageSync(key) // 删除缓存
			},
			paths: ['producteDetail'], // 只持久化指定模块
		})
	]
})

export default store;