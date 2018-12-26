/*------------------------------------------------------------/
功能：设置列表中表项获取鼠标焦点时的背景色
参数：li_col【可选】 鼠标所在表项行的背景色
返回：原调用对象
示例：$("ul").focusColor("red");
/------------------------------------------------------------*/
(function($) {
    $.fn.extend({
        "focusColor": function(li_col) {
            var def_col = "#ccc"; //默认获取焦点的色值
            //如果设置的颜色不为空，使用设置的颜色，否则为默认色
            li_col = (li_col == undefined) ? def_col : li_col;
            $(this).find("li").each(function() { //遍历表项<li>中的全部元素
                $(this).mouseover(function() { //获取鼠标焦点事件
                    $(this).css("background-color", li_col); //使用设置的颜色
                }).mouseout(function() { //鼠标焦点移出事件
                    $(this).css("background-color", '#fff'); //恢复原来的颜色
                })
            })
            return $(this); //返回jQuery对象，保持链式操作
        }
    });
})(jQuery);