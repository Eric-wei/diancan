define(['jquery', 'bootstrap', 'backend', 'table', '/assets/addons/fzdc/libs/vue.min.js', 'echarts', 'echarts-theme'], function($, undefined, Backend,Table, Vue, Echarts) {

    var Controller = {
        index: function() {
            var myChart = Echarts.init($('#echarts_order')[0], 'walden');
            myChart.setOption({
                title: {text: '订单统计',subtext: '24小时'},
                legend: {data: ['订单额', '订单数']},
                tooltip: {trigger: 'axis',formatter: "{b}<br>{a0} : {c0} 个 <br>{a1} : {c1} 元"},
                toolbox: {show: true,feature: {magicType: {show: true,type: ['line', 'bar']},restore: {show: true},saveAsImage: {show: true}}},
                calculable: true,
                xAxis: [
                    {type: 'category',data: Config.orderSaleCategory}
                ],
                yAxis: [
                    {type: 'value'}
                ],
                series: [
                    {name: '订单数',type: 'line',data: Config.orderSaleNums,markPoint: {data: [{type: 'max',name: '最大值'},{type: 'min',name: '最小值'}]},markLine: {data: [{type: 'average',name: '平均值'}]}},
                    {name: '订单额',type: 'bar',smooth: true,symbol: 'none',data: Config.orderSaleAmount,markPoint: {data: [{type: 'max',name: '最大值'},{type: 'min',name: '最小值'}]},markLine: {data: [{type: 'average',name: '平均值'}]}}
                ]
            });
            $(window).resize(function () {
                myChart.resize();
            });
            // 载入Vue
            new Vue({
                el:'#fzdc',
                data: {
                    refundList: Config.servicesRefundList,
                    shopAuthList: Config.shopAuthList,
                    refundState: ['申请退款','卖家同意','卖家拒绝','申请平台','成功退款','退款已关闭','已提交物流'],
                    shopState: ['个人','企业','旗舰'],
                    shopVerify: ['提交资质','提交店铺','提交审核','通过','未通过'],
                },
                methods: {





                }
            });
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
