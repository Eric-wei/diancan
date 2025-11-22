<template>
	<view>
		<view style="height: 20rpx;">
		</view>
		<view class="box">
			<u-parse :content="commonplaylist"></u-parse>
		</view>
	</view>
</template>

<script>
	import {
		pact,
		common
	} from "@/api/spread.js";
	export default {
		data() {
			return {
				commonplaylist: '',
				idad: ''
			};
		},
		methods: {
			play(id) {
				pact({
					name: '规则说明'
				}).then(res => {
					this.commonplaylist = res.data.content
				})
			},
		},
		onLoad(options) {
			common({}).then(res => {
				this.play(res.data.play)
			})
		}
	}
</script>

<style lang="scss">
	.box {
		width: 95%;
		margin: 0 auto;
		height: 100rpx;
		color: #000002;
	}
</style>