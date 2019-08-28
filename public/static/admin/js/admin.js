layui.config({
    base: 'static/extend/',
    version: false
}).extend({ //设定模块别名
    helper: 'helper/helper'                //如果 mymod.js 是在根目录，也可以不用设定别名
});