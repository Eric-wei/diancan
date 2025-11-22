<template>
	<view>
		<view :style="{'padding':mag}">
			<u-empty icon="/static/dc-empty.png" mode="data" :iconSize='150' :textSize='24' text='暂无数据'></u-empty>
		</view>
	</view>
</template>

<script>
	export default {
		name: "emptyPage",
		props: {
			mag: {
				type: String,
				default: '350rpx 0 20rpx'
			}
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss">

</style>