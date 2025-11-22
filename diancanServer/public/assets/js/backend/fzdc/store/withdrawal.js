define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/store/withdrawal/index' + location.search,
                    // add_url: 'fzdc/store/withdrawal/add',
                    edit_url: 'fzdc/store/withdrawal/edit',
                    // del_url: 'fzdc/store/withdrawal/del',
                    multi_url: 'fzdc/store/withdrawal/multi',
                    import_url: 'fzdc/store/withdrawal/import',
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
                        {field: 'store.title', title: __('Store.title'), operate: 'LIKE'},
                        {field: 'user.username', title: __('提现用户'), operate: 'LIKE'},
                        {field: 'amount', title: __('Amount'), operate:'BETWEEN'},
                        {field: 'residue_amount', title: __('剩余可提现金额'), operate:'BETWEEN'},
                        {field: 'type', title: __('Type'),searchList: {"1":__('银行卡'),"2":__('微信'),"3":__('支付宝')}, formatter: Table.api.formatter.normal},
                        {field: 'nu', title: __('卡号/账号')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'refuse', title: __('Refuse'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
