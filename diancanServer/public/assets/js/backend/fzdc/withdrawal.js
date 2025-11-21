define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/withdrawal/index' + location.search,
                    add_url: 'fzdc/withdrawal/add',
                    edit_url: 'fzdc/withdrawal/edit',
                    del_url: 'fzdc/withdrawal/del',
                    multi_url: 'fzdc/withdrawal/multi',
                    import_url: 'fzdc/withdrawal/import',
                    table: 'fzdc_withdrawal',
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
                        {field: 'store_id', title: __('Store_id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'amount', title: __('Amount'), operate:'BETWEEN'},
                        {field: 'type', title: __('Type')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'refuse', title: __('Refuse'), operate: 'LIKE'},
                        {field: 'month', title: __('Month'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'residue_amount', title: __('Residue_amount'), operate:'BETWEEN'},
                        {field: 'order_sn', title: __('Order_sn'), operate: 'LIKE'},
                        {field: 'out_batch_no', title: __('Out_batch_no'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
