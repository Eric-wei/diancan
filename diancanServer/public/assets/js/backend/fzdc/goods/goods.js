define(['jquery', 'bootstrap', 'backend', 'table', 'form', '/assets/addons/fzdc/js/skugoods.js'], function ($, undefined, Backend, Table, Form, Sku) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzdc/goods/goods/index' + location.search,
                    add_url: 'fzdc/goods/goods/add',
                    edit_url: 'fzdc/goods/goods/edit',
                    del_url: 'fzdc/goods/goods/del',
                    multi_url: 'fzdc/goods/goods/multi',
                    import_url: 'fzdc/goods/goods/import',
                    table: 'fzdc_goods',
                }
            });

            var table = $("#table");
            $('.btn-add').data('area', ['80%', '80%']);
            table.on('post-body.bs.table', function(e, setting, json, xhr) {
                $('.btn-editone,.btn-edit').data('area', ['80%', '80%']);
            });

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
                        {field: 'types.name', title: __('Type.name'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'type', title: __('类型'), searchList: {"1":__('单规格'),"2":__('多规格')}, operate: 'LIKE', formatter: Table.api.formatter.flag},
                        {field: 'tags', title: __('Tags'), operate: 'LIKE', formatter: Table.api.formatter.flag},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'state', title: __('State'), searchList: {"0":__('State 0'),"1":__('State 1')}, formatter: Table.api.formatter.normal},
                        {field: 'weigh', title: __('权重')},
						{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            $(".btn-add").data("area", ["80%", "90%"]);
            $(".btn-editone").data("area", ["100%", "100%"]);

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
                url: 'fzdc/goods/goods/recyclebin' + location.search,
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
                                    url: 'fzdc/goods/goods/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'fzdc/goods/goods/destroy',
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
            //下拉框点击事件
            $("#c-store_ids").on("click", function () {
                var type = $("#c-type_id").val();
                if (type=='') {
                    layer.alert("请先选择分类")
                }
            });
            $("#c-store_ids").data("params", function (obj) {
                //obj为SelectPage对象
                return {type_id: $("#c-type_id").val()};
            });


            var d = { groups: [], items: [], products: [] }
            Sku._init(d);
            Controller.api.bindevent();
        },
        edit: function () {
            //下拉框点击事件
            $("#c-store_ids").on("click", function () {
                var type = $("#c-type_id").val();
                if (type=='') {
                    layer.alert("请先选择分类")
                }
            });
            $("#c-store_ids").data("params", function (obj) {
                //obj为SelectPage对象
                return {type_id: $("#c-type_id").val()};
            });


            Sku._init(Config.skus);
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
