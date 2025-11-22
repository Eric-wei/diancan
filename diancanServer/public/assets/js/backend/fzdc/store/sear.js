
define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/store/sear/index' + location.search,
                    add_url: 'fzdc/store/sear/add',
                    edit_url: 'fzdc/store/sear/edit',
                    del_url: 'fzdc/store/sear/del',
                    download_url: 'fzdc/store/sear/download',
                    multi_url: 'fzdc/store/sear/multi',
                    import_url: 'fzdc/store/sear/import',
                    table: 'fzdc_seat',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'store.title', title: __('Store.title'), operate: 'LIKE'},
                        {field: 'store_id', title: __('store_id'),visible:false, operate: false},
                        {field: 'seart.title', title: __('桌号分类'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'no', title: __('No'), operate: 'LIKE'},
                        {field: 'img', title: __('桌图'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'use_status', title: __('使用状态'), searchList: {"1":__('空闲中'),"2":__('使用中'),"3":__('打扫中')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            buttons: [
                                {
                                    name: 'reply',
                                    title: __('先餐后付二维码'),
                                    text: __('先餐后付二维码'),
                                    classname: 'btn btn-xs btn-warning btn-dialog btn-read',
                                    icon: 'fa fa-address-book',
                                    url: 'fzdc/store/sear/generate?type=2',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                    }
                                },
                                {
                                    name: 'reply',
                                    title: __('先付后餐二维码'),
                                    text: __('先付后餐二维码'),
                                    classname: 'btn btn-xs btn-warning btn-dialog btn-read',
                                    icon: 'fa fa-address-book',
                                    url: 'fzdc/store/sear/generate?type=1',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                    }
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
                                        return 'fzdc/order/order?seat_id=' + row.id+'&store_id='+row.store_id;
                                    },
                                },
                            ],
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            var submitForm = function (ids, layero) {
                var options = table.bootstrapTable('getOptions');
                console.log('options', options);
                var columns = [];
                $.each(options.columns[0], function (i, j) {
                    if (j.field && !j.checkbox && j.visible && j.field !== 'operate') {
                        columns.push(j.field);
                    }
                });
                var search = options.queryParams({});
                console.log('search', search);
                $("input[name=search]", layero).val(options.searchText);
                $("input[name=ids]", layero).val(ids);
                $("input[name=filter]", layero).val(search.filter);
                $("input[name=op]", layero).val(search.op);
                $("input[name=columns]", layero).val(columns.join(','));
                $("form", layero).submit();
            };

            // 启动和暂停按钮
            $(document).on("click", ".btn-export-qrcode", function () {
                var ids = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all = table.bootstrapTable('getOptions').totalRows;
                console.log('choose', ids, page, all);
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl('fzdc/store/sear/download?type=2' + location.search) + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title: '导出数据',
                    btn: ["选中项(" + ids.length + "条)", "本页(" + page.length + "条)", "全部(" + all + "条)"],
                    success: function (layero, index) {
                        // 弹窗显示后，统一3个按钮的样式
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    },
                    yes: function (index, layero) {
                        submitForm(ids.join(","), layero);
                        Layer.close(index);
                        return true;
                    },
                    btn2: function (index, layero) {
                        var ids = [];
                        $.each(page, function (i, j) {
                            ids.push(j.id);
                        });
                        submitForm(ids.join(","), layero);
                        return true;
                    },
                    btn3: function (index, layero) {
                        submitForm("all", layero);
                        return true;
                    }
                })
            });
            $(document).on("click", ".btn-export-qrcode2", function () {
                var ids = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all = table.bootstrapTable('getOptions').totalRows;
                console.log('choose', ids, page, all);
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl('fzdc/store/sear/download?type=1' + location.search) + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title: '导出数据',
                    btn: ["选中项(" + ids.length + "条)", "本页(" + page.length + "条)", "全部(" + all + "条)"],
                    success: function (layero, index) {
                        // 弹窗显示后，统一3个按钮的样式
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    },
                    yes: function (index, layero) {
                        submitForm(ids.join(","), layero);
                        Layer.close(index);
                        return true;
                    },
                    btn2: function (index, layero) {
                        var ids = [];
                        $.each(page, function (i, j) {
                            ids.push(j.id);
                        });
                        submitForm(ids.join(","), layero);
                        return true;
                    },
                    btn3: function (index, layero) {
                        submitForm("all", layero);
                        return true;
                    }
                })
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
                url: 'fzdc/store/sear/recyclebin' + location.search,
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
                                    url: 'fzdc/store/sear/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'fzdc/store/sear/destroy',
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

        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
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
