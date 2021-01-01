define(['jquery', 'bootstrap', 'backend', 'table', 'layer', 'fast'], function ($, undefined, Backend, Table, Layer, Fast) {
    var Controller = {
        index: function () {
            var self = this;
            var extendedDurationStatus = false;
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
            }, 2000);

            if ($('.close-btn').length > 0) {
                $('.close-btn').click(function () {
                    $('.bottom-nav').hide();
                });
            }

            if ($('.send-message').length > 0) {
                $('.send-message').click(function () {
                    var username = $('.c-username').val();
                    var phone = $('.c-phone').val();
                    Fast.api.ajax({
                        url: 'site/send_message',
                        method: 'post',
                        data: {
                            site_id: site_id,
                            username: typeof username == 'undefined' ? "" : username,
                            phone: typeof phone == 'undefined' ? "" : phone,
                        },
                    }, function (data, ret) {
                        $('.c-username').val('');
                        $('.c-phone').val('');
                        Layer.alert('发送成功!');
                        return false;
                    }, function (res, error) {
                        Layer.alert(error.msg);
                        return false;
                    });
                });
            }

            if ($('.wechat-right').length > 0) {
                $('.wechat-right').click(function () {
                    $('.mip-fill-content').css('display', 'flex');
                });
            }

            if ($('.mip-fill-content').length > 0) {
                $('.mip-fill-content .closeWechat').click(function () {
                    $('.mip-fill-content').hide();
                });
                $('.mip-fill-content .openWechat').click(function () {
                    document.location.href = 'weixin://';
                });
            }
        }
    };
    return Controller;
});
