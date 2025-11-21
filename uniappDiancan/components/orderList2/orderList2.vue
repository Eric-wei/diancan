<template>
	<!-- 左边侧边 -->
	<view>
		<view class="content">
			<scroll-view style="width: 100%; height: 100%;" scroll-y="true">
				<block v-for="(item,index) in content" :key="item">
					<view class="text" :style="{'background-color':select_index==index?' #ffffff':''}"
						@tap="select(index,item.type)">
						{{item.type}}
					</view>
				</block>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		name: "orderlist",
		props: {
			content: {
				type: Array,
				default: [],
				required: true
			},
			index: {
				type: Number
			}
		},
		data() {
			return {
				select_index: 0
			};
		},
		watch: {
			index: function(vlo, rol) {
				this.select_index = 0
			}
		},
		methods: {
			select(index, goods) {
				this.select_index = index
				this.$emit('aid_mgs', goods, index)
			}
		}
	}
</script>

<style lang="scss">
	.content {
		width: 179rpx;
		height: 1300rpx;
		background: #F4F8F9;
		border-radius: 0rpx 18rpx 0rpx 0rpx;
		position: fixed;

		.text {
			width: 179rpx;
			height: 145rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #87888B;
			line-height: 145rpx;
			text-align: center;
		}
	}
</style>