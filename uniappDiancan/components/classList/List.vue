<template>
	<scroll-view scroll-y class="addreddList">
		<u-empty mode="data" text="暂无分类" icon="/static/dc-empty.png" textSize="38" iconSize="190" v-if="list.length==0">
		</u-empty>
		<view class="addressli" v-for="(item,index) in list" :key="item.id" @click.stop="choose(item)">
			<view class=" flex jsb ac">
				<image class="view1 flex jc ac" :src="item.icon" mode="aspectFill"></image>
				<view class="view2 f1">
					<view class="flex ac">
						<view class="flex ac">
							<text class="name">
								{{item.name||''}}
							</text>
						</view>
					</view>

					<text class="detail">
						最后修改：{{item.updatetime | timerFormat }}
					</text>
				</view>
			</view>
			<!-- <view class="line"></view> -->
			<!-- 		<view class="flex jsb ac">
				<view class="flex ac">

				</view>
				<view class="flex ac">
					<view class="default flex ac" @tap="fundel(item)">
						<view class="mr10">
							<up-icon name="trash-fill" color="#232323" size="22"></up-icon>
						</view>
						删除
					</view>
					<view class="default flex ac ml30"
						@tap="go('/pagesA/UserAll/UserToolsFive/Store/AddMB/Add?type=edit&id='+item.id)">
						<view class="mr10">
							<up-icon name="edit-pen-fill" color="#232323" size="22"></up-icon>
						</view>
						编辑
					</view>
				</view>
			</view> -->

		</view>

		<!-- <view class="Bench">

		</view>
		<view class="bcbut flex jc ac" @tap.stop="go('/pagesA/UserAll/UserToolsFive/Store/AddMB/Add?type=add')">
			新建规格模板
		</view>
		<Loading :show="Data.loading_show"></Loading> -->
	</scroll-view>
</template>

<script>
	export default {
		data() {
			return {

			}
		},
		props: {
			list: {
				default: [],
				type: Array
			},
			butsType: {
				default: false,
				type: Boolean
			}
		},
		methods: {
			choose(item) {
				this.$emit('newuse', item)
			}
		}
	}
</script>
<style>
	page {
		background: #f5f5f5;
	}
</style>
<style lang="scss" scoped>
	.ml30 {
		margin-left: 30rpx !important;
	}

	.bcbut {
		width: 701rpx;
		height: 81rpx;
		background: #8E97FE;
		border-radius: 47rpx 47rpx 47rpx 47rpx;
		font-size: 30rpx;
		color: #FFFFFF;
		position: fixed;
		bottom: 100rpx;

		.add {
			width: 28rpx;
			height: 28rpx;
			margin-right: 28rpx;
		}
	}

	.choose {
		.chooseicon {
			width: 36rpx;
			height: 36rpx;
		}
	}

	.default {
		font-size: 24rpx;
		color: #666666;
		margin-left: 10rpx;
	}

	.addreddList {
		padding: 30rpx;
		box-sizing: border-box;
		height: 55vh;

		.addressli {
			padding: 20rpx 25rpx;
			box-sizing: border-box;
			background: #f5f5f5;
			border-radius: 18rpx;
			margin-bottom: 30rpx;

			.line {
				width: 650rpx;
				height: 2rpx;
				background: #E8E8E8;
				margin-top: 20rpx;
				margin-bottom: 20rpx;
			}

			.view1 {
				width: 85rpx;
				height: 85rpx;
				// background: rgba(142, 151, 254, 0.1);
				font-size: 28rpx;
				color: #8E97FE;
				border-radius: 999px;
			}

			.view2 {
				width: 504rpx;
				margin: 0 0rpx 0 20rpx;

				.name {
					font-weight: bold;
					font-size: 28rpx;
					color: #232323;
				}

				.phone {
					font-size: 28rpx;
					color: #232323;
					margin-left: 10rpx;
				}

				.detail {
					font-size: 24rpx;
					color: #666666;
					margin-top: 14rpx;
				}
			}

			.view3 {
				// width: 26rpx;
				// height: 26rpx;
				padding-right: 20rpx;
				box-sizing: border-box;
			}
		}
	}
</style>