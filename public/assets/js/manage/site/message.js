define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'site/message/index' + location.search,
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                    import_url: '',
                    table: 'site_message',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'site_message.id',
                sortName: 'site_message.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'site_id', title: '网站id', visible: false},
                        {field: 'site.title', title: '网站标题', operate: 'LIKE'},
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Create time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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