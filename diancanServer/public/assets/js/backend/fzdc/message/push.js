define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/message/push/index' + location.search,
                    add_url: 'fzdc/message/push/add',
                    edit_url: 'fzdc/message/push/edit',
                    // del_url: 'fzdc/message/push/del',
                    multi_url: 'fzdc/message/push/multi',
                    import_url: 'fzdc/message/push/import',
                    table: 'fzdc_message_push',
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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'masterplate', title: __('Masterplate'), operate: 'LIKE'},
                        {field: 'type', title: __('类型'), searchList: {"1":__('支付成功通知'),"2":__('积分变更通知'),"3":__('订单状态通知'),"4":__('用户加菜通知')}, formatter: Table.api.formatter.normal},

                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
            });
            Controller.api.bindevent();
        },
        edit: function () {
            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
            });
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
