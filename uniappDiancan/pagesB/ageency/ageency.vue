<template>
	<view class="box">
		<image class="box_img" src="@/static/Blue_background.png" mode="aspectFill" />
		<view class="countent-box">
			<view class="countent-box_haer">
				<view class="countent-box_haer_user">
					<image class="countent-box_haer_user_img" v-if="token" :src="centerlist.avatar" mode="aspectFill" />
					<image class="countent-box_haer_user_img" src="@/static/avatar.png" v-else mode=""></image>
					<view>
						<view class="beyond" style="width: 500rpx;" v-if="token">
							{{centerlist.username || '' }}
						</view>
						<view class="" v-else>
							请登录
						</view>
						<view class="countent-box_haer_rank" v-if="centerlist.proxy_level==1">
							<image class="countent-box_haer_haer_img" :src="centerlist.level_icon" mode="aspectFill" />
							<view class="countent-box_haer_rank_text"
								style="background-color: #886DAC; border: 1px solid #886DAC;  color: #B9ADCF; ">
								{{centerlist.level_title}}分销商
							</view>
						</view>
						<view class="countent-box_haer_rank" v-if="centerlist.proxy_level==2">
							<image class="countent-box_haer_haer_img" :src="centerlist.level_icon" mode="aspectFilla" />
							<view class="countent-box_haer_rank_text"
								style="background: linear-gradient(180deg, #ECF0F3 0%, #C0C9D8 100%); border: 1px solid #D1D9E5;color: #808998;">
								{{centerlist.level_title}}分销商
							</view>
						</view>
						<view class="countent-box_haer_rank" v-if="centerlist.proxy_level==3">
							<image class="countent-box_haer_haer_img" :src="centerlist.level_icon" mode="" />
							<view class="countent-box_haer_rank_text"
								style="background-color: #EAE99B;border: 1px solid #EAE99B; color: #F3A537;">
								{{centerlist.level_title}}分销商
							</view>
						</view>
					</view>
				</view>

			</view>
			<view class="commission">
				<image class="commission_1_img" src="@/static/sum.png" mode="" />
				<view class="commission_1">
					<view class="commission_1_prevd_left" @tap="routerGo('/pagesB/Commission_order/Commission_order')">
						<view class="commission_1_prevd_left_left ">
							累计佣金(元)
							<image class="arrow_right_img" src="@/static/arrow_right_gray.png" mode="" />
						</view>
						<view class="commission_1_prevd_left_right">
							{{centerlist.commissionAll || 0}}
						</view>
					</view>
					<view class="commission_1_prevd_right" @tap="routerGo('/pagesB/my_team/my_team')">
						<view class="commission_1_prevd_left_left ">
							下线总数
							<image class="arrow_right_img" src="@/static/arrow_right_gray.png" mode="" />
						</view>
						<view class="commission_1_prevd_left_right">
							{{centerlist.peopleCount || 0}}
						</view>
					</view>
				</view>
			</view>
			<view class="can_draw_cash_out">
				<view class="can_draw_cash_out_box">
					<view class="can_draw_cash_out_box_1">
						<view class="can_draw_cash_out_box_1_left">
							<view class="can_draw_cash_out_box_1_left_1">
								可提现(元)
							</view>
							<view class="can_draw_cash_out_box_1_left_2">
								{{centerlist.commission || 0}}
							</view>
						</view>
						<view class="can_draw_cash_out_box_1_right "
							@tap="routerGo('/pagesB/Withdrawal_record/Withdrawal_record?price=' + centerlist.commissionOver)">
							提现记录
							<image class="arrow_right_img" src="@/static/arrow_right_gray.png" mode="" />
						</view>
					</view>
					<view class="can_draw_cash_out_box_2">
						<view>
							<!-- bindtap="Withdrawal_record" -->
							<view class="can_draw_cash_out_box_2_left">
								待入账(元)
								<!-- <image style="width: 12rpx; height: 20rpx;" src="/img/arrow_right_gray.png" mode="" /> -->
							</view>
							<view class="can_draw_cash_out_box_2_right">
								{{centerlist.recorded || 0}}
							</view>
						</view>
						<view
							@tap="routerGo('/pagesB/Withdrawal_record/Withdrawal_record?price=' + centerlist.commissionOver+'&id=1')">
							<view class="can_draw_cash_out_box_2_left ">
								提现中(元)
								<image class="arrow_right_img" src="@/static/arrow_right_gray.png" mode="" />
							</view>
							<view class="can_draw_cash_out_box_2_right">
								{{centerlist.commissioning || 0}}
							</view>
						</view>
						<view
							@tap="routerGo('/pagesB/Withdrawal_record/Withdrawal_record?id=3' +'&price=' + centerlist.commissionOver)">
							<view class="can_draw_cash_out_box_2_left ">
								<span>已提现(元)</span>
								<image class="arrow_right_img" src="@/static/arrow_right_gray.png" mode="" />
							</view>
							<view class="can_draw_cash_out_box_2_right">
								{{centerlist.commissionOver || 0}}
							</view>
						</view>
					</view>
					<view class="can_draw_cash_out_box_3" @tap='setmodalshow'>
						立即提现
					</view>
				</view>
			</view>
			<view class="tool">
				常用工具
			</view>
			<view class="tool_class">
				<view style="height: 30rpx;">
				</view>
				<view class="tool_class_box">
					<view class="tool_class_box_conten" @tap="routerGo('/pagesB/my_team/my_team')">
						<image style="width: 70rpx;height: 70rpx;" src="@/static/linkman.png" mode="" />
						<view class="tool_class_box_conten_right">
							<view class="tool_class_box_conten_right_top">
								我的团队
							</view>
							<view class="tool_class_box_conten_right_bottom">
								{{centerlist.peopleCount || 0}}人
							</view>
						</view>
					</view>
					<view class="tool_class_box_conten" @tap="routerGo('/pagesB/Commission_order/Commission_order')">
						<image style="width: 70rpx;height: 70rpx;" src="@/static/loose.png" mode="" />
						<view class="tool_class_box_conten_right">
							<view class="tool_class_box_conten_right_top">
								佣金订单
							</view>
							<view class="tool_class_box_conten_right_bottom">
								详细佣金情况
							</view>
						</view>
					</view>
					<view class="tool_class_box_conten"
						@tap="routerGo('/pagesB/Promotional_poster/Promotional_poster')">
						<image style="width: 70rpx;height: 70rpx;" src="@/static/photo.png" mode="" />
						<view class="tool_class_box_conten_right">
							<view class="tool_class_box_conten_right_top">
								推广海报
							</view>
							<view class="tool_class_box_conten_right_bottom">
								查看海报
							</view>
						</view>
					</view>
					<view class="tool_class_box_conten" @tap="routerGo('/pagesB/explain/explain')">
						<image style="width: 70rpx;height: 70rpx;" src="@/static/rule.png" mode="" />
						<view class="tool_class_box_conten_right">
							<view class="tool_class_box_conten_right_top">
								规则说明
							</view>
							<view class="tool_class_box_conten_right_bottom">
								规则玩法
							</view>
						</view>
					</view>

				</view>
			</view>


		</view>
		<u-modal :show="modalshow" :showCancelButton='true' @cancel='modalshow=false' @confirm="withdraw_deposit"
			:title="'确定要提现吗'" :closeOnClickOverlay="true"></u-modal>
		<!-- <van-dialog id="van-dialog" /> -->
	</view>
</template>

<script>
	import {
		Agency_centerlist,
		distributiondraw
	} from "@/api/spread.js";
	export default {
		data() {
			return {
				centerlist: {},
				int: {},
				token: uni.getStorageSync('userinfo').token || null,
				modalshow: false
			};
		},
		onLoad(options) {
			this.post_Agency_centerlist()
		},
		onShow() {
			this.token = uni.getStorageSync('userinfo').token
			this.post_Agency_centerlist()
		},
		methods: {
			setmodalshow() {
				let token = uni.getStorageSync('userinfo').token
				if (token) {
					if (this.centerlist.commission > 0) {
						this.modalshow = true
					} else {
						uni.$showMsg('没有可提现金额')
					}
				} else {
					uni.navigateTo({
						url: '/pages/login/login'
					})
				}
			},
			async post_Agency_centerlist() {
				let data = await Agency_centerlist({})
				if (data.code == 1) {
					this.centerlist = data.data
				}
				// Object.assign()
			},
			async withdraw_deposit() {
				if (this.token) {
					let data = await distributiondraw({
						withdraw_money: this.centerlist.commission,
						type: 1
					})
					if (data.code == 1) {
						this.post_Agency_centerlist()
						uni.$showMsg('提现成功')
					} else {
						uni.$showMsg(data.msg)
					}
					this.modalshow = false
				} else {
					uni.navigateTo({
						url: '/pages/login/login'
					})
				}

			},

		}
	}
</script>

<style lang="scss">
	.box {
		width: 750rpx;
		// height: 1524rpx;
		background: #EFF1F3;
		position: relative;
	}

	.box_img {
		width: 100%;
		height: 639rpx;
		position: absolute;
		top: 0;
	}

	.countent-box {
		width: 95%;
		position: absolute;
		margin-top: 40rpx;
	}

	.countent-box_haer {
		margin-left: 60rpx;
		display: flex;
		align-content: center;
	}

	.countent-box_haer_user {
		display: flex;
		align-items: center;
		font-size: 26rpx;
		font-weight: 600;
		color: #FFFFFF;
	}

	.countent-box_haer_user_img {
		width: 70rpx;
		height: 70rpx;
		border-radius: 50%;
		margin-right: 20rpx;
	}

	.countent-box_haer_haer_img {
		width: 45rpx;
		height: 40rpx;
		z-index: 99;
	}

	.countent-box_haer_rank {
		display: flex;
		align-items: center;
		position: relative;
		margin-top: 10rpx;
	}

	.countent-box_haer_rank_text {
		width: 100rpx;
		height: 20rpx;
		/* background: linear-gradient(180deg, #ECF0F3 0%, #C0C9D8 100%); */
		border-radius: 13rpx;
		border: 1rpx solid #E5FFFF;
		font-size: 16rpx;
		font-weight: 400;
		color: #FFFFFF;
		line-height: 20rpx;
		text-align: right;
		position: absolute;
		left: 25rpx;
		padding-right: 10rpx;
	}

	.commission {
		position: absolute;
		left: 45rpx;
		margin-top: 30rpx;
	}

	.commission_1 {
		width: 667rpx;
		height: 227rpx;
		background: linear-gradient(180deg, #FDFAFF 0%, #F1EFFB 100%);
		border-radius: 18rpx;
	}

	.commission_1_img {
		width: 259rpx;
		height: 227rpx;
		position: absolute;
		right: 0;
	}

	.commission_1 {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.commission_1_prevd_left {
		margin-left: 50rpx;
	}

	.commission_1_prevd_right {
		margin-right: 180rpx;
		z-index: 9;
	}

	.commission_1_prevd_left_left {
		font-size: 24rpx;
		font-weight: 400;
		color: #010200;
		line-height: 33rpx;
	}

	.commission_1_prevd_left_right {
		font-size: 46rpx;
		font-weight: 600;
		color: #010200;
		line-height: 65rpx;
	}

	.can_draw_cash_out {
		position: absolute;
		left: 30rpx;
		margin-top: 200rpx;
		width: 695rpx;
		height: 462rpx;
		background: #FFFFFF;
		border-radius: 0rpx 0rpx 20rpx 20rpx;

	}

	.can_draw_cash_out_box {
		width: 90%;
		margin: 0 auto;
	}

	.can_draw_cash_out_box_1 {
		margin-top: 40rpx;
		display: flex;
		justify-content: space-between;
	}

	.can_draw_cash_out_box_1_left_1 {
		font-size: 24rpx;
		font-weight: 400;
		color: #010200;
	}

	.can_draw_cash_out_box_1_left_2 {
		font-size: 36rpx;
		font-weight: 600;
		color: #EF4841;
		line-height: 50rpx;
	}

	.can_draw_cash_out_box_1_right {
		font-size: 24rpx;
		font-weight: 400;
		color: #010200;
		letter-spacing: 2px;
	}

	.can_draw_cash_out_box_2 {
		margin-top: 50rpx;
		display: flex;
		justify-content: space-between;
	}

	.can_draw_cash_out_box_2_left {
		font-size: 24rpx;
		font-weight: 400;
		color: #010200;
		line-height: 33rpx;


	}

	.can_draw_cash_out_box_2_right {
		font-size: 34rpx;
		font-weight: 500;
		color: #010101;
		line-height: 48rpx;
	}

	.can_draw_cash_out_box_3 {
		width: 598rpx;
		height: 96rpx;
		background: linear-gradient(90deg, #0CA1FE 0%, #1F73F4 100%);
		border-radius: 48rpx;
		margin-top: 60rpx;
		font-size: 30rpx;
		font-weight: 600;
		color: #EFFFFF;
		line-height: 96rpx;
		text-align: center;
	}

	.tool {
		width: 90%;
		margin: 0 auto;
		margin-top: 700rpx;
		height: 45rpx;
		font-size: 32rpx;
		font-family: PingFangSC-Medium, PingFang SC;
		font-weight: 600;
		color: #000002;
	}

	.tool_class {
		width: 695rpx;
		margin-left: 30rpx;
		margin-top: 20rpx;
		margin-bottom: 50rpx;
		background: #FFFFFF;
		border-radius: 14rpx;
		padding-top: 20rpx;
		padding-bottom: 10rpx;
	}

	.tool_class_box {
		width: 90%;
		margin: 0 auto;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}

	.tool_class_box_conten {
		display: flex;
		align-items: center;
		width: 50%;
		height: 70rpx;
		margin-bottom: 50rpx;
	}

	.tool_class_box_conten_right {
		margin-left: 20rpx;
	}

	.tool_class_box_conten_right_top {
		font-size: 26rpx;
		font-weight: 400;
		color: #000002;
	}

	.tool_class_box_conten_right_bottom {
		font-size: 24rpx;
		font-weight: 400;
		color: #95959E;
		line-height: 33rpx;
	}
</style>