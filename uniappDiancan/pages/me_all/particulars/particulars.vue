<template>
	<view>
		<image :src="content.image" mode="widthFix"></image>
		<view class="box" v-html="content.content">
		</view>
	</view>
</template>

<script>
	import {
		message_detail
	} from "@/api/user.js"
	export default {
		data() {
			return {
				int: {},
				content: ''
			};
		},
		onLoad(obj) {
			this.int = obj.id
			this.details()
		},
		methods: {
			async details() {
				let data = await message_detail({
					id: this.int
				})
				uni.setNavigationBarTitle({
					title: data.data.title //这是修改后的导航栏文字
				})
				this.content = data.data
			}
		}
	}
</script>

<style lang="scss">
	.box {
		padding: 20rpx;
	}
</style>