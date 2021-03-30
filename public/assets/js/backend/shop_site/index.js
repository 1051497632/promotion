define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop_site/index/index' + location.search,
                    add_url: 'shop_site/index/add',
                    edit_url: 'shop_site/index/edit',
                    del_url: 'shop_site/index/del',
                    multi_url: 'shop_site/index/multi',
                    import_url: 'shop_site/index/import',
                    table: 'shop_site',
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
                        {field: 'user.nickname', title: '客户姓名', operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'keyword', title: __('Keyword'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'id', title: '访问地址', formatter: Controller.api.formatter.manageUrl, operate: false},
                        // {field: 'desc', title: __('Desc'), operate: false},
                        {field: 'show_page', title: __("Show_page"), searchList: {"1":__('Show_page_yes'), "2":__('Show_page_no')}, formatter: Table.api.formatter.status, custom: {1: 'success', 2: 'danger'}},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                            {
                                name: 'message',
                                text: '查看消息',
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-message addtabsit',
                                url: 'shop_site/message/index?shop_site_id={id}'
                            },
                            {
                                name: 'goods',
                                text: '商品列表',
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-goods addtabsit',
                                url: 'shop_site/goods/index?shop_site_id={id}'
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
                    var url = Fast.api.cdnurl('/index/shop_site/index/id/' + row.id + '.html');
                    return '<div class="input-group input-group-sm" style="width:150px;margin:0 auto;"><input type="text" class="form-control input-sm" value="' + url + '"><span class="input-group-btn input-group-sm"><a href="' + url + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
                }
            }
        }
    };
    return Controller;
});