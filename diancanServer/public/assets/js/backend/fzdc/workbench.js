define(['jquery', 'bootstrap', 'backend', 'table', 'form', '/assets/addons/fzdc/js/vue.js'], function ($, undefined, Backend, Table, Form, Vue) {
    var Controller = {
        index: function () {
            var app = new Vue({
                el: '#app',
                data: {
                    hasLoad: {
                        recent: false,
                        allDesk: false,
                    },
                    audioUrl: '',
                    recentList: [],
                    deskList: []
                },
                created: function () {
                    console.info('created');
                },
                mounted: function () {
                    console.info('mounted');
                },
                updated: function () {
                    console.info('updated');
                },
                methods: {
                    itemAdd: function (e) {
                        console.log('itemAdd', e.currentTarget.dataset, this.attributes);
                        this.attributes.push($.extend(true, {}, {label: '名称', type: 'wrong'}));
                        this.render = function () {
                            render.formElement(this.attributes.length - 1);
                        };
                    },
                    itemDel: function (e) {
                        console.log('itemDel', e.currentTarget.dataset);
                        this.attributes.splice(e.currentTarget.dataset.index, 1);
                    }
                }
            })

            // 加载最近下单
            var loadRecentData = function (init) {
                if (init) {
                    app.hasLoad.recent = false
                }
                Fast.api.ajax({
                    url: 'fzdc/workbench/recentData',
                    data: {
                        shop_id: $('#c-shop_id').val(),
                    },
                    loading: false
                }, function (res) {
                    console.log('loadRecentData', res)
                    app.hasLoad.recent = true
                    app.recentList = res.orderList

                    return false;
                });
            };

            // 更新座号状态
            $(document).on("click", ".changeDeskStatus", function () {
                //获取某一个属性的值
                var state = $(this).attr('data-id');
                Fast.api.ajax({
                    url: 'fzdc/workbench/changeDeskStatus',
                    data: {
                        state,
                    },
                    loading: false
                }, function (res) {
                    //页面刷新
                    loadShopData();
                    return false;
                });
            });


            // 加载所有桌号
            var loadShopData = function (init) {
                if (init) {
                    app.hasLoad.allDesk = false
                }
                Fast.api.ajax({
                    url: 'fzdc/workbench/shopData',
                    data: {
                        shop_id: $('#c-shop_id').val(),
                        group: $('#c-group').val()
                    },
                    loading: false
                }, function (res) {
                    console.log('loadShopData', res)
                    app.hasLoad.allDesk = true
                    app.deskList = res.deskList

                    return false;
                });
            };

            // 加载下单通知
            var loadOrderNotice = function () {
                Fast.api.ajax({
                    url: 'fzdc/workbench/noticeData',
                    data: {
                        shop_id: $('#c-shop_id').val(),
                    },
                    loading: false
                }, function (res) {
                    console.log('loadOrderNotice', res)
                    if (res.voice) {
                        Toastr.success(res.title);
                        var audioHTML = document.getElementById('bgMusic');
                        audioHTML.src = res.voice;
                        setTimeout(function () {
                            var promise = audioHTML.play();
                            console.log('setTimeout promise', promise);
                            if (promise !== undefined) {
                                promise.then(_ => {
                                    //  这种情况可以自动播放（非谷歌的其他浏览器：edge、360）
                                    console.log('promise success')

                                }).catch(error => {
                                    //  谷歌和火狐就会阻止播放，可以显示一个PLAY按钮提示用户点击后进行播放
                                    console.log('promise error')
                                });
                            }
                        }, 500);
                    }

                    return false;
                });
            };

            // 选择门店
            $(document).on("change", "#c-shop_id", function () {
                var value = $(this).val();
                console.log("shop_id change", value);
                loadRecentData(true);
                loadShopData(true);
            });
            $('#c-shop_id').trigger('change');

            // 选择状态
            $(document).on("click", ".filter-box .b-item", function () {
                var value = $(this).data('value');
                console.log("group change", value);
                $('#c-group').val(value);
                $('.filter-box .b-item').removeClass('f-active');
                $(this).addClass('f-active');
                loadShopData();
            });

            // 订单和桌号 - 自动刷新
            setInterval(function () {
                loadRecentData();
                loadShopData();
            }, 5000);

            // 语音通知 - 自动刷新
            setInterval(function () {
                if (Config.voiceNotice) {
                    loadOrderNotice();
                }
            }, 2000);

            // 切换语音通知
            $(document).on("click", ".voice-notice", function () {
                var state = $(this).data('state');
                Fast.api.ajax({
                    url: 'fzdc/workbench/voiceNotice',
                    data: {
                        state,
                    },
                    loading: false
                }, function (res) {
                    console.log('voiceNotice', res);
                    Config.voiceNotice = state;
                    if (state) {
                        $('.voice-notice.f-open').addClass('hide');
                        $('.voice-notice.f-close').removeClass('hide');
                    } else {
                        $('.voice-notice.f-close').addClass('hide');
                        $('.voice-notice.f-open').removeClass('hide');
                    }

                    return false;
                });
            });

            Form.api.bindevent($('.select-shop'));
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});