define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            var addUrl = 'shop_site/goods/add';
            var shop_site_id = Fast.api.query('shop_site_id');
            if (shop_site_id) {
                addUrl += '?shop_site_id=' + shop_site_id;
            }
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop_site/goods/index' + location.search,
                    add_url: addUrl,
                    edit_url: 'shop_site/goods/edit',
                    del_url: 'shop_site/goods/del',
                    multi_url: 'shop_site/goods/multi',
                    import_url: 'shop_site/goods/import',
                    table: 'shop_site_goods',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh DESC, id DESC',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'price', title: __('Price')},
                        {field: 'is_recommend', title: __('Is_recommend'), formatter: Table.api.formatter.status, searchList: {1: __('Is_recommend_no'), 2: __('Is_recommend_yes')}, custom: {1: 'gray', 2: 'success'}},
                        {field: 'weigh', title: __("Weigh")},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                        ]}
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