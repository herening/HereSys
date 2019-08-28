layui.define(['layer', 'form'], function(exports) {
    "use strict";

    var $ = layui.jquery,
        layer = layui.layer,
        form = layui.form,
        helper = {
            setForm: function (div, object) {
                div.each(function (index, item) {
                    var itemFrom = $(this);
                    layui.each(object, function (key, value) {
                        var itemElem = itemFrom.find('[name="' + key + '"]')
                            , type;

                        //如果对应的表单不存在，则不执行
                        if (!itemElem[0]) return;
                        type = itemElem[0].type;

                        //如果为复选框
                        if (type === 'checkbox') {
                            itemElem[0].checked = value;
                        } else if (type === 'radio') { //如果为单选框
                            itemElem.each(function () {
                                if (this.value == value) {
                                    this.checked = true
                                }
                            });
                        } else { //其它类型的表单
                            itemElem.val(value);
                        }
                    });
                });
            }

            // ............
        }
    exports('helper', helper);
    });

