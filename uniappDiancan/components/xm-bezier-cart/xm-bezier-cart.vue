<template>
	<view class="add__box" @click="handleClick">
		<!-- <image class="plus__img" src="../../static/plus.png"></image> -->
		<view class="cubic__bezier" :class="animate==false?'':'animate__bezier'" :style="[{ left: '0.25rem', top: '0.25rem',transform:animate==false?'none':transform_x}]">
			<view v-if="showMs == 'text'" :class="animate==false?'':'animate__bezier_y'" class="badge__bezier" :style="[{width:width,height:height,backgroundColor:getBgColor,transform:transform_y}]">				{{num}}			</view>
			<view v-if="showMs == 'img'" :class="animate==false?'':'animate__bezier_y'" class="badge__bezier" :style="[{width:width,height:height,transform:transform_y}]">
				<image class="plus__img" :src="imgSrc"></image>
			</view>
		</view>
	</view>
	
</template>

<script>
	export default {
		name: "XmBezierCart",
		props: {
			//显示文本 text,显示图片 img
			showMs:{
				type:String,
				default:'text'
			},
			//要显示的图片路径
			imgSrc:{
				type:String,
				default:''
			},
			//要显示的文本内容
			num:{
				type:Number,
				default:0
			},
			// 动画显示内容宽度	
			width: {
				type: String,
				default: "40rpx"
			},
			// 动画显示内容高度	
			height: {
				type: String,
				default: "40rpx"
			},
			// 动画显示内容背景颜色	
			backgroundColor: {
				type: String,
				default: ""
			},
			// 动画显示内容left值	
			left: {
				type: String,
				default: "0"
			},
			// 动画显示内容top值	
			top: {
				type: String,
				default: "0"
			},
			// 是否自定义动画显示内容	
			custom: {
				type: Boolean,
				default: false
			},
			// 动画方向
			direction: {
				type: String,
				default: "down"
			},
			// 根据方向传值，终点的位置点，一般为元素中心位置
			position: {
				type: Object,
				default: () => ({
					top: 50,
					bottom: 80,
					left: 30,
					right: 40
				})
			},
			// 列表中索引 index
			index: {
				type: Number,
				default: 0
			},
			// 自定义参数	
			params: {
				type: [Number, String],
				default: 0
			}
		},
		computed: {
			getBgColor() {
				return this.backgroundColor || "#EB0909"
			}
		},
		data: () => ({
			time: 0,
			animate: false,
			transform_x: "",
			transform_y: "",
			windowHeight:'',
			windowWidth:''
		}),
		methods: {
			handleClick(t) {
				let that = this
				uni.getSystemInfo({
					success: function (res) {
						that.windowHeight = res.screenHeight
						that.windowWidth = res.screenWidth
					}
				});
				
				(new Date).getTime() - that.time <= 550 || (that.time = (new Date).getTime(),
					setTimeout((() => {
						that.time = 0
					}), 550),
					that.bezierEffect(t),
					that.$emit("click", {
						index: that.index,
						params: that.params
					}))
			},
			bezierEffect(t) {
				let i = null;
				i = t.touches[0];
				let e = {
					x: 0,
					y: 0
				};
				
				switch (this.direction) {
					case "up":
						e.x = this.windowWidth - i.clientX - this.position.right,
							e.y = this.position.top - i.clientY;
						break;
					case "down":
						e.x = this.windowWidth - i.clientX - this.position.right,
							e.y = this.windowHeight - i.clientY - this.position.bottom;
						break;
					case "bottom":
						e.x = this.position.left - i.clientX,
							e.y = this.windowHeight - i.clientY - this.position.bottom;
						break;
					case "top":
						e.x = this.position.left - i.clientX,
							e.y = this.position.top - i.clientY
				}
				this.animate = true;
					this.transform_x = `translate3d(${e.x}px,0,0)`;
					this.transform_y = `translate3d(0,${e.y}px,0) rotate(350deg) scale(0.8)`;
					
					setTimeout((() => {
						this.animate = false,
							this.transform_x = "none",
							this.transform_y = "none"
					}), 550)
			}
		}
	}
</script>

<style>
	.cubic__bezier{
		/* position: absolute; */
		opacity: 1;
		/* z-index: 20; */
	}
	.badge__bezier {
	    color: #fff;
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    font-size: .8125rem;
		border-radius: 40rpx;
		width: 48rpx !important;
		height: 48rpx !important;
	}
	.animate__bezier{
	    opacity: 1;
	    z-index: 990;
	    transition: opacity .1s, transform cubic-bezier(0,0,0,0) .5s;
	}
	.animate__bezier_y{
	    opacity: 1;
	    z-index: 990;
	    transition: opacity .1s, transform cubic-bezier(.3,-.2,1,0) .5s;
	}
	.add__box{
	    position: relative;
	    margin-left: auto;
	    flex-shrink: 0;
		
	}
	.plus__img{
	    width: 100%;
	    height: 100%;
	    margin-left: auto;
	}
</style>