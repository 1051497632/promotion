define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'service/index/index' + location.search,
                    add_url: 'service/index/add',
                    edit_url: 'service/index/edit',
                    del_url: 'service/index/del',
                    multi_url: 'service/index/multi',
                    import_url: 'service/index/import',
                    table: 'service',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                sortOrder: 'desc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'price', title: __('Price'), operate: false},
                        {field: 'discount', title: __('Discount'), operate: false},
                        {field: 'service_time', title: __('Service_time'), operate: false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                            {
                                name: 'detail',
                                text: '查看订单',
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail addtabsit',
                                url: 'service/order/index?service_id={id}'
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