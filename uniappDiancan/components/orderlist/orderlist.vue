<template>
	<!-- 左边侧边 -->
	<view>
		<view class="content" :class="nopadding?'':'contentpb'">
			<scroll-view :class="nopadding?'':'contentscroll'" style="width: 100%; height: 100%;" scroll-y="true"
				:scroll-into-view="rightId" :scroll-with-animation="true">
				<block v-for="(item,index) in content" :key="item">
					<view class=" text_1" :class="{'active': indexc==index}" @tap="select(index,item.goods)" :id="'class'+index">
						<image class="left_img" :src="item.icon" mode="aspectFill"></image>
						<view class="text_name">
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
		width: 200rpx;
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
			padding: 20rpx 4rpx;
			font-size: 24rpx;
			font-weight: 400;
			color: #777;
			text-align: center;
			box-sizing: border-box;
			position: relative;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: flex-start;
			min-height: 80rpx;
			border-bottom: 1rpx solid #E5E5E5;
			transition: background-color 0.3s ease;
			
			&.active {
				background-color: #F5F5F5;
			}
		}

		.text_name {
			position: relative;
			z-index: 1;
			padding: 8rpx 4rpx;
			transition: color 0.3s;
			margin-top: 8rpx;
			color: #777;
			width: 100%;
			max-width: 120rpx;
			line-height: 1.4;
			word-break: break-all;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		
		.text_1.active .text_name {
			color: #000000 !important;
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
			width: 50rpx;
			height: 50rpx;
			border-radius: 10rpx;
			margin-bottom: 12rpx;
			position: relative;
			z-index: 2;
		}
	}
</style>