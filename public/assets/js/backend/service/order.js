define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'service/order/index' + location.search,
                    add_url: '',
                    edit_url: '',
                    del_url: 'service/order/del',
                    multi_url: '',
                    import_url: '',
                    table: 'service_order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'status ASC, id DESC',
                sortOrder: 'desc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id'), visible: false},
                        {field: 'service_id', title: __('Service_id'), visible: false},
                        {field: 'user.nickname', title: '用户', operate: 'LIKE'},
                        {field: 'service.name', title: '服务名称', operate: false},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status wait'), "2":__('Status success')}, formatter: Table.api.formatter.flag, custom: {1: 'danger', 2: 'success'}},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                            {
                                name: 'deal',
                                text: '处理',
                                icon: 'fa fa-check-square-o',
                                classname: 'btn btn-info btn-xs btn-detail ajaxit',
                                url: 'service/order/deal?id={id}',
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
            },
            formatter: {
                manageUrl: function (value, row, index) {
                    var url = Fast.api.cdnurl('/index/site/index/id/' + row.id + '.html');
                    return '<div class="input-group input-group-sm" style="width:150px;margin:0 auto;"><input type="text" class="form-control input-sm" value="' + url + '"><span class="input-group-btn input-group-sm"><a href="' + url + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
                }
            }
        }
    };
    return Controller;
});