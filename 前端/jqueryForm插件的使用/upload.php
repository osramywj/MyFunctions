
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery.form.js"></script>
</head>
<body>
<form action="act.php" method="post" enctype="multipart/form-data" id="form">
    <input type="file" name="filename">
    <input type="text" name="username">
    <div style="display: none;" id="output"></div>
    <input type="submit" value="提交">
</form>
<script>

    /****************************jquery.form插件使用**************************************/


    /*************************方法一***************************************************/
    /*$('#form').submit(function(){
        $(this).ajaxSubmit(function(res){
            var a = JSON.parse(res);
            $('#output').html(a).show();
        });
        return false; //阻止表单默认提交
    })*/
    /*************************方法二***************************************************/

    /*$('#form').ajaxForm(function(res){
        var a = JSON.parse(res);
        $('#output').html(a).show();
    })*/
    /*************************方法三***************************************************/

    var options = {
        target:'#output',   // 把服务器返回的内容放入id为output的元素中
        beforeSubmit: fun1,   // 提交前的回调函数
        success:  fun2,   // 提交后的回调函数
        //url: url,                 //默认是form的action， 如果申明，则会覆盖
        //type: type,               //默认是form的method（get or post），如果申明，则会覆盖
        dataType: 'json',           //html(默认), xml, script, json...接受服务端返回的类型!!!!!一定要加引号
        //clearForm: true,          //成功提交后，清除所有表单元素的值
        //resetForm: true,          //成功提交后，重置所有表单元素的值
        timeout: 3000               //限制请求的时间，当请求大于3秒后，跳出请求
    };

    /**
     * 提交前的回调函数  该函数返回false就阻止提交
     * @param formData  提交值的数组对象   提交表单时，Form插件会以Ajax方式自动提交这些数据，格式如：[{name:user,value:val },{name:pwd,value:pwd}]
     * @param jqForm   表单元素的jQuery对象，jqForm[0]是其dom对象
     * @param options
     */
    function fun1(formData,jqForm,options) {
        //进行表单验证

        // formData可以判断全部不为空的情况
        for(var i=0;i<formData.length;i++){
            if(!formData[i].value){
                alert("都不能为空");
                return false;
            }
        }
        // jqForm可以判断某个不为空的情况
        var form = jqForm[0];  //把jqForm转为DOM对象
        if(!form.username.value){
            alert("username不能为空");
            return false;
        }

        // fieldValue()可以获取多值的数组形式，如checkbox
        var value = $("input[name='username']").fieldValue();
        if(!value[0]){
            return false;
        }
    }

/*    function fun2(responseText, statusText){
        // 根据dataType不同responseText解析方式不同
        alert('状态:'+statusText+'\n 返回的内容是:\n'+responseText);
    }*/

    function fun2(res){
        alert('状态:'+res.status+'\n 返回的内容是:\n'+res.info);
    }

        $('#form').ajaxForm(options); //把前面定义的options作为参数传进去

</script>
</body>
</html>