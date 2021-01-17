define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            var table = $("#table");

            var isEdit = table.data('isEdit');

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'site/index/index' + location.search,
                    add_url: 'site/index/add',
                    edit_url: 'site/index/edit',
                    del_url: 'site/index/del',
                    multi_url: 'site/index/multi',
                    import_url: 'site/index/import',
                    table: 'site',
                }
            });

            var columns = [
                {checkbox: true},
                {field: 'id', title: __('Id')},
                {field: 'site_id', title: __('Site_id'), visible: false, operate: false},
                {field: 'title', title: __('Title'), operate: 'LIKE'},
                {field: 'keyword', title: __('Keyword'), operate: 'LIKE'},
                {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                {field: 'id', title: '访问地址', formatter: Controller.api.formatter.manageUrl, operate: false},
                {field: 'remark', title: __('Remark'), operate: false},
                // {field: 'show_page', title: __("Show_page"), searchList: {"1":__('Show_page_yes'), "2":__('Show_page_no')}, formatter: Table.api.formatter.status, custom: {1: 'success', 2: 'danger'}},
                {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime}
                
            ];
            if (isEdit == 1) {
                columns.push({field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                    {
                        name: 'detail',
                        text: '查看浏览记录',
                        icon: 'fa fa-list',
                        classname: 'btn btn-info btn-xs btn-detail addtabsit',
                        url: 'site/browse_log/index?site_id={id}'
                    },
                    {
                        name: 'detail',
                        text: '查看消息',
                        icon: 'fa fa-list',
                        classname: 'btn btn-info btn-xs btn-detail addtabsit',
                        url: 'site/message/index?site_id={id}'
                    }
                ]});
            }

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                commonSearch: false,
                columns: [
                    columns
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