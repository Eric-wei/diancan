<template>
	<view>
		<view class="box" v-html="content.content||content">

		</view>
	</view>
</template>

<script>
	import {
		pact
	} from "@/api/user.js"
	import {
		point_rule
	} from "@/api/comm.js"
	export default {
		data() {
			return {
				int: {},
				content: ''
			};
		},
		onLoad(obj) {
			this.int = obj
			uni.setNavigationBarTitle({
				title: obj.name //这是修改后的导航栏文字
			})
			this.details()
		},
		methods: {
			async details() {
				if (this.int.state == 0) {
					let data = await pact({
						name: this.int.name
					})
					if (data.code == 1) {
						this.content = data.data
					} else {
						uni.showToast({
							title: data.msg,
							icon: "none"
						})
					}
				} else {
					let data = await point_rule()
					if (data.code == 1) {
						this.content = data.data
					} else {
						uni.showToast({
							title: data.msg,
							icon: "none"
						})
					}
				}
			}
		}
	}
</script>

<style lang="scss">
	.box {
		padding: 20rpx;
	}
</style>