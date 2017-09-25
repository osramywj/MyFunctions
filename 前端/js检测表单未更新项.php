<script  type="text/javascript">
    Array.prototype.inArray=function (value){for (var i=0;i<this.length;i++){if (this[i] == value){return true;}}return false};
    function IsFormChanged(el) {
        var tips="";
        var arr1=new Array();
        var arr2=new Array();
        var isChanged=false;
        var itemVal='';
        var form = document.getElementById(el);
        for (var i = 0; i < form.elements.length; i++) {
            var element = form.elements[i];
            var type = element.type;
            switch(type){
                case "text":
                case "hidden":
                case "textarea":
                case "button":
                    tips += (element.value != element.defaultValue)?tips.length==0?element.name+"='"+escape(element.value)+"'" : "&"+element.name+"='"+escape(element.value)+"'":"";
                    break;
                case "radio":
                    if(!arr1.inArray(element.name)){
                        arr1.push(element.name);
                        var myRadio=document.getElementsByName(element.name);
                        for(var k=0;k<myRadio.length;k++){
                            tips += (myRadio[k].checked && !myRadio[k].defaultChecked)?tips.length==0?myRadio[k].name+"='"+myRadio[k].value+"'":"&"+myRadio[k].name+"='"+myRadio[k].value+"'":"";
                        }
                    }
                    break;
                case "checkbox":
                    if(!arr2.inArray(element.name)){
                        arr2.push(element.name);
                        isChanged=false;
                        var myBox=document.getElementsByName(element.name);
                        itemVal='';
                        for(var k=0;k<myBox.length;k++){
                            if(myBox[k].checked){//当前checkbox被选中
                                if(!myBox[k].defaultChecked){isChanged=true}//是否与初始状态不同,如果是则标记为当前checkbox需更新
                                itemVal += itemVal.length==0?myBox[k].value:","+myBox[k].value;//同一name追加值
                            }else{
                                if(myBox[k].defaultChecked){
                                    isChanged=true;//初始化时为选中状态但当前未选中，标记该复选框组值需更新
                                }
                            }
                        }
                        if(isChanged){itemVal=element.name+"='"+itemVal+"'";tips+=tips.length==0?itemVal:'&'+itemVal}
                    }
                    break;
                case "select-one":
                    for (var j = 0; j < element.options.length; j++) {
                        tips += (element.options[j].selected && !element.options[j].defaultSelected)?tips.length==0?element.name+"='"+element.value+"'":"&"+element.name+"='"+element.value+"'":"";
                    }
                    break;
                case "select-multiple":
                    isChanged=false;
                    itemVal='';
                    for (var j = 0; j < element.options.length; j++) {
                        if(element.options[j].selected){
                            if(!element.options[j].defaultSelected){isChanged=true}//是否与初始状态不同,如果是则标记为当前select需更新
                            itemVal +=itemVal.length==0?element.options[j].value:","+element.options[j].value;//同一个元素只追加值
                        }else{
                            if(element.options[j].defaultSelected){
                                isChanged=true;//初始化时为选中状态但当前未选中，标记该select值需更新
                            }
                        }
                    }
                    if(isChanged){itemVal=element.name+"='"+itemVal+"'";tips+=tips.length==0?itemVal:'&'+itemVal}
                    break;
            }
        }
        tips.length==0?alert('没有更新项'):alert(tips);
    }
</script>
<form id="chageform" action="">
    text：<input type="text" name="txt1" value="1"/><br />
    myBox：<input type="checkbox" name="myBox" checked="checked" value="1"/>
    <input type="checkbox" name="myBox" value="2"/>
    <input type="checkbox" name="myBox" value="3"/><br />

    myBox2：<input type="checkbox" name="myBox2" checked="checked" value="4"/>
    <input type="checkbox" name="myBox2" checked="checked" value="5"/>
    <input type="checkbox" name="myBox2" value="6"/><br />

    myRadio：<input type="radio" name="myRadio" checked="checked" value="1"/>
    <input type="radio" name="myRadio" value="2"/><br />

    myRadio2：<input type="radio" name="myRadio2" value="1"/>
    <input type="radio" name="myRadio2" checked="checked" value="2"/><br />

    mySel：<select name="mySel">
        <option value="1" selected="selected">1</option>
        <option value="2">2</option>
    </select><br />

    mySel2：<select name="mySel2">
        <option value="1">1</option>
        <option value="2" selected="selected">2</option>
    </select><br />

    mySel3：<select name="mySel3" multiple="multiple" style="width:50px;">
        <option value="1">1</option>
        <option value="2" selected="selected">2</option>
        <option value="3">3</option>
        <option value="4" selected="selected">4</option>
        <option value="5">5</option>
    </select><br />

    mySel4：<select name="mySel4" multiple="multiple" style="width:50px;">
        <option value="1">1</option>
        <option value="2" selected="selected">2</option>
        <option value="3">3</option>
        <option value="4" selected="selected">4</option>
    </select><br />
    <input type="button" value="检测" onclick="IsFormChanged('chageform')" />
</form>