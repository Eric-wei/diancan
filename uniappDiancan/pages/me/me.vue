<template>
	<view>
		<view class="" v-if="public.isnews==0">
			<oldme :user='user'></oldme>
		</view>
		<view class="" v-else>
			<newme ref='newme' :user='user'></newme>
		</view>
	</view>
</template>

<script>
	import {
		index_list,
		store_default,
		order_empty,
		seat
	} from "@/api/comm.js"
	import {
		userInfo,
		common
	} from "@/api/public.js"
	export default {
		data() {
			return {
				user: uni.getStorageSync('user'),
				public: uni.getStorageSync("public"),
				token: uni.getStorageSync("userinfo").token,
				list: []
			};
		},

		onLoad() {
			// this.commonApi()
			// let token = uni.getStorageSync("userinfo").token
			// if (token) {
			// 	this.user = uni.getStorageSync('user')
			// 	this.token = uni.getStorageSync("userinfo")
			// 	this.getUser()
			// } else {
			// 	this.user = null
			// }
		},
		onShow() {
			this.commonApi()
			let token = uni.getStorageSync("userinfo").token
			if (token) {
				this.user = uni.getStorageSync('user')
				this.token = uni.getStorageSync("userinfo")
				this.getUser()
			} else {
				this.$refs.newme.percentage = 0
				this.$refs.newme.token = null
				this.user = null
			}
		},
		methods: {
			commonApi() {
				console.log('触发了')
				common().then(data => {
					if (data.code == 1) {
						uni.setStorageSync('public', data.data)
					}
				})
			},
			async getUser() {
				let user = await userInfo()
				if (user.code == 1) {
					uni.setStorageSync('user', user.data)
					this.$refs.newme.getUser()
				}
			},
			contact(e) {},


		}
	}
</script>
<style>

</style>