<template>
	<!-- 左边侧边 -->
	<view>
		<view class="content" :class="nopadding?'':'contentpb'">
			<scroll-view :class="nopadding?'':'contentscroll'" style="width: 100%; height: 100%;" scroll-y="true"
				:scroll-into-view="rightId" :scroll-with-animation="true">
				<block v-for="(item,index) in content" :key="item">
					<view class=" text_1" @tap="select(index,item.goods)" :id="'class'+index"
						:style="{'background-color':indexc==index?' #E40030':'', 'color':indexc==index?'#ffffff':''}">
						<image class="left_img" :src="item.icon" mode="aspectFill"></image>
						<view class="">
							{{item.name}}
						</view>
					</view>
				</block>
				<!-- <view class="" style="width: 100%;height: 65px;">
				
				</view> -->
				<view class="text_2" v-if="!contentscroll">
				</view>

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
			indexc: {
				type: Number,
				default: 0,
			},
			rightId: {
				type: String,
				default: '',
			},
			nopadding: {
				type: Boolean,
				default: false,
			}
		},
		data() {
			return {
				select_index: 0,
				leftId: '', //右边滚动到左边左边的id
			};
		},
		watch: {
			// index: function(vlo, rol) {
			// 	this.select_index = 0
			// }
		},
		methods: {
			changeIndex(index) {
				// this.currentIndex = index
			},
			select(index, goods) {
				this.leftId = "goods" + index;
				this.select_index = index;
				this.$emit('aid_mgss', goods, index)
				this.$emit('aid_mgs', this.leftId, index)
			}
		}
	}
</script>

<style lang="scss">
	.contentpb {
		padding-bottom: 104rpx !important;
	}

	.content {
		width: 190rpx;
		height: 1200rpx;
		background: #FAFAFA;
		border-radius: 0rpx 18rpx 0rpx 0rpx;
		// position: fixed;
		box-sizing: border-box !important;



		.contentscroll {
			padding-bottom: 57px !important;
			box-sizing: border-box;
		}

		.text_1 {
			width: 100%;
			padding: 30rpx 8rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #777;
			text-align: center;
			box-sizing: border-box;



		}

		.text_2 {
			width: 100%;
			padding-top: 57px;
			font-size: 24rpx;
			font-weight: 400;
			color: #777;
			text-align: center;
			box-sizing: border-box;
			height: 57px;

		}


		.left_img {
			width: 40rpx;
			height: 40rpx;
			border-radius: 10rpx;
			margin-right: 4rpx;
			margin-bottom: 12rpx;
		}
	}
</style>