define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/order/order/index' + location.search,
                    // add_url: 'fzdc/order/order/add',
                    // edit_url: 'fzdc/order/order/edit',
                    del_url: 'fzdc/order/order/del',
                    multi_url: 'fzdc/order/order/multi',
                    import_url: 'fzdc/order/order/import',
                    table: 'fzdc_order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                        {field: 'order_no', title: __('Order_no'), operate: 'LIKE'},
						{field: 'store.title', title: __('门店名称'), operate: 'LIKE'},
                        {field: 'type', title: __('类型'), searchList: {"1":__('支付订单'),"2":__('充值订单')}, formatter: Table.api.formatter.normal},
                        {field: 'order_type', title: __('Order_type'), searchList: {"0":__('暂无'),"1":__('Order_type 1'),"2":__('Order_type 2'),"3":__('堂食'),"4":__('预约')}, formatter:
                                function (value, row, index){
                                    if (value==3){
                                        if (row.p_type==1){
                                            //加个颜色
                                            return "<span style='color:red;'>堂食(先付后餐)</span>"
                                        }else{
                                            return "<span style='color:blue;'>堂食(先餐后付)</span>"
                                        }
                                    }else{
                                        if (value==1){
                                            return "<span style='color:blueviolet;'>自取</span>"
                                        }else if (value==2){
                                            return "<span style='color:green;'>外卖</span>"
                                        }else if (value==4){
                                            return "<span style='color:orange;'>预约</span>"
                                        }else{
                                            return "暂无";
                                        }
                                    }

                                }},
                        {field: 'pay_type', title: __('支付方式'), searchList: {"1":__('微信支付'),"2":__('钱包支付'),"3":__('现金支付'),"0":__('暂无')}, formatter: Table.api.formatter.normal},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"6":__('备餐'),"7":__('出餐'),"8":__('待取单'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":__('Status 5'),"9":__('已退款'),"10":__('预约中')},custom: {"6": "green"}, formatter: Table.api.formatter.status},
                        {field: 'p_type', title: __('p_type'), searchList: {"1":__('先付后餐'),"2":__('先餐后付')},visible:false,operate: false, formatter: Table.api.formatter.status},
                        {field: 'order_amount_total', title: __('Order_amount_total'), operate:'BETWEEN'},
                        {field: 'pay_time', title: __('支付时间'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('打印'),
                                    title: __('打印'),
                                    classname: 'btn btn-xs btn-success btn-ajax',
                                    icon: 'fa fa-print',
                                    url: 'fzdc/order/order/print',

                                    success:function (data) {
                                        console.log(123123123);
                                        $(".btn-refresh").trigger("click");
                                    }
                                },
                                {
                                    name: 'refund',
                                    text: __('退款审核'),
                                    title: __('退款审核'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa',
                                    url: 'fzdc/order/order/refund',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    visible: function (row) {
                                        if(row.status === "4")
                                        {return true;}
                                        else
                                        {return false;}
                                    }
                                },

                                {
                                    name: 'detail',
                                    text: __('详情'),
                                    title: __('详情'),
                                    extend: 'data-area=\'["80%","80%"]\'',

                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-edit',
                                    url: 'fzdc/order/order/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    }
                                },

                            ],
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        detail: function () {
            $(document).on('click', '.btn-status-click', function () {
                let order = $(this).data('order');
                let order_id = $('#order_id').val();
                // alert(order);
                $.post('fzdc/order/order/update_status', {
                    order_id,
                    ...order
                }, function (res) {
                    if (res.code == 1) {
                        window.location.reload();
                        parent.Toastr.success(res.msg);
                    } else {
                        parent.Toastr.error(res.msg);
                    }
                })
            });


            //调整桌号
               $(document).on('click', '#js_change', function () {
            // $('#js_change').click(function(){

                Fast.api.open('fzdc/order/order/change?order_id='+$('#order_id').val(), '调整桌号', {
                    callback: function (data) {
                        window.location.reload();
                        // Fast.api.close(data)
                        console.log("===>",data);
                        // if (data.code == 1) {
                        //     window.location.reload();
                        //     parent.Toastr.success(data.msg);
                        // } else {
                        //     parent.location.reload();
                        //     parent.Toastr.error(data.msg);
                        // }
                    }
                });

                // Form.api.bindevent($("form[role=form]") ,function(data,ret){
                //              Fast.api.close(data);//这里是重点
                // });
                // $('#dialogModal').modal('show');
                // $('#dialogModal .modal-title').text('调整桌号');
                // $('#js_submit_dialog').attr('data-type', 'progress');

            });
            $("#c-seat_id").data("params", function (obj) {
                //obj为SelectPage对象
                return {custom: {store_id: $('#store_id').val()}};
            });
            Controller.api.bindevent();
        },
        refund: function () {
            Controller.api.bindevent();
        },
        change: function () {
            $("#c-seat_id").data("params", function (obj) {
                //obj为SelectPage对象
                return {custom: {store_id: $('#store_id').val()}};
            });
            Form.api.bindevent($("form[role=form]"), function(data, ret){
             //给表单绑定新的回调函数 接收 控制器 success(msg,url,data)或者error(msg,url,data)
              Fast.api.close(data);//在这里
            }, function(data, ret){
             console.error("错误");
            });

            // Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
