define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/user/user/index'+ location.search,
                    add_url: 'fzdc/user/user/add',
                    edit_url: 'fzdc/user/user/edit',
                    del_url: 'fzdc/user/user/del',
                    multi_url: 'fzdc/user/user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'p_name', title: __('上级用户'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'score', title: __('Score'), operate: 'BETWEEN', sortable: true},
                        {field: 'money', title: __('佣金'), operate: 'BETWEEN', sortable: true},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, searchList: {normal: __('Normal'), hidden: __('Hidden')}},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            buttons: [
                                {
                                    name: 'xiaji',
                                    text: __('下级列表'),
                                    title: function (row) {
                                        return __('下级列表')+ '-' + row.username;
                                    },
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    extend: 'data-area=\'["80%","80%"]\'',
                                    icon: 'fa fa-tags',
                                    // url: 'fzdc/user/test?p_id={id}',
                                    url: function (row) {
                                        return 'fzdc/user/user?p_id=' + row.id;
                                    },
                                    // callback: function (data) {
                                    //     Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    // },
                                    // success:function (data) {
                                    //     $(".btn-refresh").trigger("click");
                                    // }
                                },
                            ],
                            table: table,
                            events: Table.api.events.operate,
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
        xiaji: function () {
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