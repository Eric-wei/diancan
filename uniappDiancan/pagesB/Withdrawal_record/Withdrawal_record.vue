<template>
	<view class="box">
		<u-sticky bgColor='#fafafa'>
			<view class="content_time" v-if="detailed[0]">
				<view class="content_time_left">
				</view>
				<view class="content_time_right">
					<view class="content_time_right_left">
						已提现
					</view>
					<view class="content_time_right_right">
						￥{{price}}
					</view>
				</view>
			</view>
		</u-sticky>
		<view class="content">
			<emptyPage v-if="detailed.length<1"></emptyPage>
			<view class="main_content" v-for="item in detailed" :key="item.id">
				<view class="main_content_box">
					<view class="main_content_1">
						<view class="main_content_1_top">
							{{item.username}}
						</view>
						<view class="main_content_1_bottom">
							{{item.createtime | Formatone}}
						</view>
					</view>
					<view class="main_content_2">

						<view v-if="item.status==0">
							未审核
						</view>
						<view v-if="item.status==1">
							待到账,
						</view>
						<view v-if="item.status==2">
							审核拒绝
						</view>
						<view v-if="item.status==3">
							已到账
						</view>
					</view>
					<view class="main_content_3">
						<view class="main_content_3_1">
							{{item.amount}}
						</view>
						<view class="main_content_3_2">
							余额：{{item.residue_amount}}
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		distributiondrawLog
	} from "@/api/spread.js";

	export default {
		data() {
			return {
				detailed: [],
				date: '',
				time: new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000,
				id: '',
				price: '',
				page: 1
			};
		},
		onLoad(options) {
			this.id = options.id
			this.price = options.price
			this.post_brokeragedetailed()
		},
		onReachBottom() {
			this.page++
			this.post_brokeragedetailed(1)
		},
		methods: {
			async post_brokeragedetailed(e) {
				let data = await distributiondrawLog({
					status: this.id,
					page: this.page
				})
				if (e == 1) {
					this.detailed = [...this.detailed, ...data.data.data]
				} else {
					this.detailed = data.data.data
				}
			},

			formatDate(date) {
				date = new Date(date);
				return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
			},

			go_back() {
				uni.navigateBack({
					delta: 1
				})
			},
		}
	}
</script>

<style lang="scss">
	.content {
		width: 95%;
		margin: 0 auto;
	}

	.content_hadr {
		display: flex;
		align-items: center;
	}

	.content_hadr_img {
		width: 25rpx;
		height: 35rpx;
	}

	.content_hadr_tltle {
		font-size: 38rpx;
		font-weight: 600;
		color: #171717;
		line-height: 53rpx;
		letter-spacing: 1px;
		margin-left: 20rpx;
	}

	.content_time {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 20rpx 0;
		box-sizing: border-box;
	}

	.content_time_left {
		display: flex;
		align-items: center;
		justify-content: space-around;
		width: 186rpx;
		height: 51rpx;
		/* background: #FFFFFF; */
		border-radius: 26rpx;
		margin-left: 10rpx;
	}

	.content_time_left_time {
		font-size: 24rpx;
		font-weight: 400;
		color: #616161;
		text-align: center;
		margin-left: 30rpx;
	}

	.content_time_left_img {
		width: 23rpx;
		height: 23rpx;
		margin-right: 10rpx;
	}

	.content_time_right {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-right: 10rpx;
		background-color: #FFFFFF;
		border-radius: 35rpx;
		padding: 0 20rpx;
	}

	.content_time_right_left {
		font-size: 28rpx;
		font-weight: 400;
		color: #616161;
	}

	.content_time_right_right {
		font-size: 38rpx;
		font-weight: 500;
		color: #010101;
	}

	.main_content {
		background: #FFFFFF;
		border-radius: 14rpx;
		margin-bottom: 20rpx;
	}

	.main_content_box {
		width: 90%;
		height: 143rpx;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20rpx;
		position: relative;
	}

	.main_content_1_top {
		font-size: 28rpx;
		font-weight: 400;
		color: #010101;
		margin-bottom: 10rpx;
	}

	.main_content_1_bottom {
		font-size: 24rpx;
		font-weight: 400;
		color: #888B90;
	}

	.main_content_2 {
		font-size: 28rpx;
		font-weight: 400;
		color: #4F99F6;
		line-height: 40rpx;
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
	}

	.main_content_3 {
		text-align: center;
	}

	.main_content_3_1 {
		font-size: 34rpx;
		font-weight: 600;
		color: #010101;
	}

	.main_content_3_2 {
		font-size: 24rpx;
		font-weight: 400;
		color: #888B90;
	}
</style>