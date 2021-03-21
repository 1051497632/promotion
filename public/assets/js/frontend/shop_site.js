define(['jquery', 'bootstrap', 'backend', 'table', 'layer', 'fast'], function ($, undefined, Backend, Table, Layer, Fast) {
    var Controller = {
        index: function () {
            var self = this;
            var isSendMessage = false;
            /* var extendedDurationStatus = false;
            setInterval(function () {
                if (extendedDurationStatus) {
                    return false;
                }
                extendedDurationStatus = true;
                Fast.api.ajax({
                    url: 'site/extended_duration',
                    method: 'post',
                    loading: false,
                    data: {browse_log_id: id},
                }, function (data, ret) {
                    extendedDurationStatus = false;
                    return false;
                }, function (res) {
                    return false;
                });
            }, 2000); */

            if ($('.consultation-call').length > 0) {
                $('.consultation-call').click(function () {
                    if (isSendMessage) {
                        Layer.alert('已经发送过了!');
                        return false;
                    }
                    var phone = $('.c-phone').val();
                    Fast.api.ajax({
                        url: 'shop_site/send_message',
                        method: 'post',
                        data: {
                            shop_site_id: site_id,
                            phone: typeof phone == 'undefined' ? "" : phone,
                        },
                    }, function (data, ret) {
                        $('.c-phone').val('');
                        Layer.alert('发送成功!');
                        isSendMessage = true;
                        return false;
                    }, function (res, error) {
                        Layer.alert(error.msg);
                        return false;
                    });
                });
            }
        }
    };
    return Controller;
});
