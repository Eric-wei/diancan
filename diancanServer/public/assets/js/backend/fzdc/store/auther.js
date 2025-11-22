define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/store/auther/index' + location.search,
                    add_url: 'fzdc/store/auther/add',
                    edit_url: 'fzdc/store/auther/edit',
                    del_url: 'fzdc/store/auther/del',
                    multi_url: 'fzdc/store/auther/multi',
                    import_url: 'fzdc/store/auther/import',
                    table: 'fzdc_store_authen',
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
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'id_number', title: __('Id_number'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'store_image', title: __('Store_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'yy_image', title: __('Yy_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'font_image', title: __('Font_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'back_image', title: __('Back_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'hard_image', title: __('Hard_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'self_image', title: __('Self_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'result', title: __('Result'), operate: 'LIKE'},
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
