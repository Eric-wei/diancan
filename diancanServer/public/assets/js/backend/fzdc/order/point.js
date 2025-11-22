define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/order/point/index' + location.search,
                    add_url: 'fzdc/order/point/add',
                    // edit_url: 'fzdc/order/point/edit',
                    // del_url: 'fzdc/order/point/del',
                    multi_url: 'fzdc/order/point/multi',
                    import_url: 'fzdc/order/point/import',
                    table: 'fzdc_point_order',
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
                        // {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'order_no', title: __('Order_no'), operate: 'LIKE'},
                        // {field: 'user_id', title: __('User_id')},
                        // {field: 'goods_id', title: __('Goods_id')},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'pointgoods.title', title: __('Pointgoods.title'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":__('Status 5')}, formatter: Table.api.formatter.status},
                        {field: 'goods_price', title: __('Goods_price'), operate:'BETWEEN'},
                        {field: 'order_amount_total', title: __('Order_amount_total'), operate:'BETWEEN'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                        // {field: 'address.title', title: __('Address.title'), operate: 'LIKE'},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [

                                {
                                    name: 'detail',
                                    title: __('订单详情'),
                                    text: __('订单详情'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'fzdc/order/point/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },

                                {
                                    name: 'send',
                                    title: __('发货'),
                                    text: __('发货'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-leaf',
                                    url: 'fzdc/order/point/send',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                        // return true;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        // $(".btn-refresh").trigger("click");
                                        return false;
                                    },
                                    visible: function (row) {
                                        if(row.status == '1')
                                        {return true;}
                                        else
                                        {return false;}
                                    }

                                },


                                {
                                    name: 'ajax',
                                    title: __('完成订单'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    icon: 'fa fa-leaf',
                                    confirm: '确认完成订单？',
                                    url: 'fzdc/order/point/complete',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                        // return true;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        Layer.alert(ret.msg);
                                        // $(".btn-refresh").trigger("click");
                                        return false;
                                    },
                                    visible: function (row) {
                                        if(row.coupon_id === 0 && row.status != 3)
                                        {return true;}
                                        else
                                        {return false;}
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
        send: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
