define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/store/store/index' + location.search,
                    add_url: 'fzdc/store/store/add',
                    edit_url: 'fzdc/store/store/edit',
                    del_url: 'fzdc/store/store/del',
                    multi_url: 'fzdc/store/store/multi',
                    import_url: 'fzdc/store/store/import',
                    table: 'fzdc_store',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'alias_title', title: __('别名'), operate: 'LIKE'},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'start_times', title: __('Start_times')},
                        {field: 'end_times', title: __('End_times')},
                        {field: 'balance', title: __('余额')},
                        {field: 'authentication', title: __('认证状态'), searchList: {"0":__('未认证'),"1":__('已认证')}, formatter: Table.api.formatter.normal},
                        {field: 'cleck_goods_counts', title: __('商品点击数')},
                        {field: 'ps_price', title: __('配送费')},
                        {field: 'db_price', title: __('打包费')},
                        {field: 'qs_price', title: __('起送价')},
                        {field: 'subscribe_switch', title: __('Subscribe_switch'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'trade_switch', title: __('营业开关'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'delivery_switch', title: __('Delivery_switch'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'takeaway_switch', title: __('Takeaway_switch'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},

                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'state', title: __('State'), searchList: {"0":__('State 0'),"1":__('State 1')}, formatter: Table.api.formatter.normal},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            buttons: [

                                {
                                    name: 'desk',
                                    text: __('桌号'),
                                    title: function (row) {
                                        return __('桌号管理')+ '-' + row.title;
                                    },
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    extend: 'data-area=\'["100%","100%"]\'',
                                    icon: 'fa fa-tags',
                                    url: function (row) {
                                        return 'fzdc/store/sear?store_id=' + row.id;
                                    },
                                },
                                {
                                    name: 'order',
                                    text: __('订单'),
                                    title: function (row) {
                                        return __('相关订单')+ '-' + row.title;
                                    },
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    extend: 'data-area=\'["100%","100%"]\'',
                                    icon: 'fa fa-list',
                                    url: function (row) {
                                        return 'fzdc/order/order?store_id=' + row.id;
                                    },
                                },
                                {
                                    name: 'bank',
                                    text: __('提现账户'),
                                    title: function (row) {
                                        return __('提现账户');
                                    },
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    extend: 'data-area=\'["100%","100%"]\'',
                                    icon: 'fa fa-tags',
                                    url: function (row) {
                                        return 'fzdc/store/bank?store_id=' + row.id;
                                    },
                                },
                            ],
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'fzdc/store/store/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '140px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'fzdc/store/store/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'fzdc/store/store/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

        admin: function () {

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/store/store/admin',
                    dragsort_url: '',
                    table: 'fzdc_store_admin',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                pagination: false,
                escape: false,
                columns: [
                    [
                        {
                            field: 'superadmin', title: __('Type'), formatter: function (value, row, index) {
                                return row.superadmin ? "<span class='label label-danger'>超级管理员</span>" : "<span class='label label-success'>普通管理员</span>";
                            }
                        },
                        {field: 'username', title: __('Username')},
                        {field: 'nickname', title: __('Nickname')},
                        {
                            field: 'store_ids', title: __('门店'), formatter: function (value, row) {
                                if (row.store_names) {
                                    return `<span title="<p class='store_tips'>
                                    ${row.store_names.replace(/,/g, '<br>')}
                                </p>" data-toggle="tooltip" data-placement="bottom" data-html="true">${value}</span>`;
                                } else {
                                    return value;
                                }
                            }
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            formatter: Table.api.formatter.buttons,
                            buttons: [
                                {
                                    name: 'authorization',
                                    text: __('授权'),
                                    classname: 'btn btn-xs btn-success btn-authorization btn-dialog',
                                    icon: 'fa fa-list',
                                    // extend:`data-area='["80%","50%"]'`,
                                    url: 'fzdc/store/store/update_auth/admin_id/{id}',
                                    visible: function (row) {
                                        return !row.superadmin;
                                    },
                                },
                                {
                                    name: 'remove',
                                    text: __('移除'),
                                    classname: 'btn btn-xs btn-danger btn-remove btn-ajax',
                                    icon: 'fa fa-times',
                                    url: 'fzdc/store/store/update_auth/act/remove/admin_id/{id}',
                                    hidden: function (row) {
                                        return !row.store_ids;
                                    },
                                    confirm: __('确定移除吗?'),
                                    success: function (ret) {
                                        $(".btn-refresh").trigger("click");
                                    }
                                }
                            ]
                        }
                    ]
                ],
                search: false,
                commonSearch: false
            });
            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        update_auth: function () {
            Controller.api.bindevent();
        },


        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                $(document).on("click", ".btn-select-page", function (e, obj) {
                    var that = this;
                    Fast.api.open("fzdc/ajax/get_page_list", "选择路径", {
                        callback: function (data) {
                            $(that).parent().prev().val(data).trigger("change");
                        }
                    })
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
