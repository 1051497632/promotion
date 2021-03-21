define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop_site/message/index' + location.search,
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'shop_site/message/deal',
                    import_url: '',
                    table: 'shop_site_message',
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
                        {field: 'shop_site_id', title: __('Shop_site_id'), visible: false},
                        {field: 'shopsite.title', title: '网站标题', operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status wait'), "2":__('Status success')}, formatter: Table.api.formatter.status, custom: {1: 'danger', 2: 'success'}},
                        {field: 'createtime', title: __('Create time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                            {
                                name: 'deal',
                                text: '处理',
                                icon: 'fa fa-check-square-o',
                                classname: 'btn btn-info btn-xs btn-detail ajaxit',
                                url: 'shop_site/message/deal?id={id}',
                                confirm: '确认要处理吗？',
                                refresh: true,
                                hidden: function (row) {
                                    if (row.status == 2) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            }
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