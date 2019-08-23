layui.config({
    base: '/static/admin/js/',
    version: false
}).extend({ //设定模块别名
    helper: 'helper' //如果 mymod.js 是在根目录，也可以不用设定别名
    ,mod1: 'admin/mod1' //相对于上述 base 目录的子目录
});