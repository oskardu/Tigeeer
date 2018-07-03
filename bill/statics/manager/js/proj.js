
//$value :角色ID 多个逗号分开 ,obj :html object ,username：成员名
edit = function($value ,obj,username){
    gusername = username;
    $value = String($value);
    var $roleListHide = $(obj).parents('.mainCont').find('.role-list-ldn');
    var $roleListShow = $(obj).parents('.projectBtn').siblings('.memrole');
    var $roleListPlus = $roleListShow.find('.item-member-role');
    var $newRoleList = $roleListHide.clone(true);
    if($('.role-list')){
        $('.item-member-role').removeClass('Ldn')
        $('.role-list').remove()
    }

    var $check = $newRoleList.find(':checkbox');
    var val = $value.split(',');//角色ID 多个用 , 隔开
    $.each($check,function(i,n){
        for(var i=0;i<val.length ; i++) {
            if($(n).val() == val[i]){
                $(n).attr('checked',true)
            }
        }
    })
    $('<div class="role-list">' + $newRoleList.html() + '</div>').appendTo($roleListShow);
    $roleListPlus.addClass('Ldn');

}

//全选 & 反选
checkBoxChecked = function() {
    return $('.item-member-list-info').on('click', 'span.Lflr :checkbox', function() {
        var $subCheckBox, $subCheckList;

        $subCheckBox = $(this).closest('p').siblings('ul');
        $subCheckList = $subCheckBox.find('input:checkbox');
        if ($subCheckBox.length) {
            return $.each($subCheckList, function(i, n) {
                return $(this).prop('checked', !$(this).prop('checked'));
            });
        }
    });
};

//删除
uremove = function(username , obj) {
    if (confirm('确认删除该项')) {
        $(obj).closest('tr').remove();
        delete gmem_list[username];
    }
}

//编辑确认
pmseditor = function(){
    $('.item-member-table').on('click', '.pms-btns-finish-memeberList', function() {
        var $this = $(this)
        var $roleListShow = $this.parents('.role-list')
        var $roleListPlus = $this.parents('.memrole')
        var $text = ''
        var $value = ''
        $roleCheckedList = $roleListShow.find(':checkbox:checked');
        if($roleCheckedList.length < 1){
            alert('至少选择一种角色')
            return false
        }
        $.each($roleCheckedList, function(j, m) {
            $value += $(m).val()+','
            $text += '<span class="item-member-role">' + $(m).siblings('label').text() + '</span>';
        })

        $value  = trim($value.trim(),',');

        $roleListPlus.html($text);
        $roleListPlus.siblings('.projectBtn').find('.item-member-editor').remove()
        $('<a href="javascript:;" class="item-member-editor" onclick="edit(\''+$value+'\',this,\''+gusername+'\')">编辑</a>').prependTo($roleListPlus.siblings('.projectBtn'))
        $roleListPlus.removeClass('Ldn');

        if(gusername) {
            delete gmem_list[gusername];
            gmem_list[gusername] = $value;
            gusername ='';
        }

    });
}

//页面提交
$('#btn_submit').click(function() {

    var item_name =$('#item-name').val().trim();
    var item_desc =$('#item-synopsis').val().trim();
    if(item_name =='') {
        alert('请输入项目名');
        $('#item-name').foucs();
        return false;
    }
    if(item_name =='') {
        alert('请输入项目简介');
        $('#item-synopsis').foucs();
        return false;
    }

    if(isEmptyObject(gmem_list)) {
        alert('请至少选择一个项目成员');
        return false;
    }
    var member = '';
    $.each(gmem_list,function(k,v) {
        member += k+'|'+v+';'
    });

    $.ajax({
        type: "POST",
        url: "/proj/aup",
        data:'proj_name='+encodeURI(item_name)+'&proj_desc='+encodeURI(item_desc)+'&member='+trim(member,';')+(proj_id!='' ? '&proj_id='+proj_id: ''),
        success: function(data)
        {
            if (data.result == "ok") {
                $('#dia').hide();
                alert(proj_id==''? '添加成功' : '编辑成功');
                location.href ='/proj/show?proj_id='+data.proj_id ;
                return true;
            }  else if (data.result == "error") {
                alert('参数错误，请仔细检查');
            }
            else {
                alert('操作失败，请稍后再试');
            }
            $('#dia').hide();
            return false;
        }
    });

});

//页面 reset
$('#btn_reset').click(function() {
    window.location.reload();
});

//sure
manageMember = function() {
    var $manaMemberBox, $manaRoleBox;

    $manaMemberBox = $('.item-member-list-info');
    $manaRoleBox = $('.item-member-role-list');
    return $('.item-member-btn .pms-btn').click(function() {
        var $manageMeFn, $personArr, $roleArr, $table;

        $table = $('.item-member-table tbody');
        $personArr = $.grep($manaMemberBox.find(':checkbox:checked'), function(val, key) {
            return !$(val).closest('p').siblings().is('ul');
        });
        $roleArr = $manaRoleBox.find(':checkbox:checked');
        var $role_view_text = '';//表格中 角色显示

        //遍历用户选择框
        var choice_mem = false;
        $.each($personArr, function(key3, val3) {
            var text = $(val3).val().split('|'); // ex:username|realname
            if(text.length<2) return true;// 类似 continue
            gmem_list[text[0]] = '';
            choice_mem = true;

        });
        //遍历角色选择框
        var $role_str ='';
        $.each($roleArr, function(key1, val1) {
            var text2 = $(val1).val().split('|'); //ex : roleid | role_name
            $role_view_text += '<span class="item-member-role">' +text2[1] + '</span>';
            $role_str += text2[0]+',';
        });

        $role_str = trim($role_str,',');

        if(!choice_mem || $role_str.trim() =='') {
            alert('请选择成员和角色');
            return false;
        }
        $.each(gmem_list , function (k,v) {
            if(gmem_list[k] =='')
                gmem_list[k] = $role_str;
        });


        // 显示
        $.each($personArr, function(key2, val2) {
            var text = $(val2).val().split('|');

            if(text.length<2) return true; // 类似 continue

            $('<tr><td class="Lpl15">' + text[1] + '</td><td class="memrole">' + $role_view_text + '</td>' +
                '<td class="projectBtn"><a href="javascript:;" class="item-member-editor" onclick="edit(\''+gmem_list[text[0]]+'\',this,\''+text[0]+'\')">编辑</a>' +
                '<a href="javascript:;" class="item-member-remove" onclick="uremove(\''+text[0]+'\',this)">删除</a></td></tr>').appendTo($table);
        });

        $(this).parents('.pms-prop').find(':checkbox').prop('checked', false);
        $(this).parents('.pms-prop').addClass('Ldn');
        $('#name-search-input').val('');
        if ($('.search-list-ul')) {
            $('.search-list-ul').remove();
            $('.item-member-list-info').find('>ul').removeClass('Ldn');
        }
        $('#dia').hide();
        return true;

    });
};

// 成员框 展开 收缩 事件
showSubList = function() {
    return $('.item-member-list-info').on('click', 'span.task-arrow', function() {
        var $this, $ul;

        $this = $(this);
        $ul = $this.closest('li').find('>ul');
        if (!$('.item-member-list-info ul').is(':animated')) {
            $ul.slideToggle('fast');
            return $this.toggleClass('task-arrowr');
        }
    });
}

//搜索
searchName = function() {
    return $('#item-member-search').click(function() {
        var $checkBox, $checkboxArr, $inputVal, $newCheckboxArr;

        $inputVal = $('#name-search-input').val();
        $inputVal = $inputVal.trim();
        $checkBox = $(this).parents('.search').siblings('.item-member-list-info');

        $checkBox.find('.searchList').remove();

        $checkboxArr = $checkBox.find(':checkbox');
        $newCheckboxArr = $.grep($checkboxArr, function(n, i) {
            return !$(n).parent().parent().is('p');
        });

        $htmlString = '';
        re = eval('/' + $inputVal + '/');

        $.each($newCheckboxArr, function(i, x) {
            var splits = $(x).val().split('|'); // ex: miy@ndoo.net|稍大
            if (re.test(splits[0]) || re.test(splits[1])) {
                return $htmlString += '<li><span class="Lflr">' +
                    '<input type="checkbox" id="s_'+splits[0]+'" value="' + $(x).val() + '"/></span>' +
                    '<span><label  for="s_'+splits[0]+'">' +splits[1] + '</label></span>' +
                    '</li>';
            }
        });
        if($htmlString == '') {
            alert('没有匹配到相应的人员');
            $checkBox.find('ul').removeClass('Ldn')
            return false;
        }
        $checkBox.find('ul').addClass('Ldn');

        return $('<ul class="searchList">' + $htmlString + '</ul>').appendTo($checkBox);
    });
};
//给ENTER绑定搜索功能
bindEnter = function(){
    $('#name-search-input').bind('keypress',function(event){
        if(event.keyCode == '13'){
            $('#item-member-search').trigger('click')
        }
    })
}
//搜索框中的重置事件
$('.searchClose').click(function(){
    reset();
})

//重置 人员选择框
reset = function(){
    $('#name-search-input').val('')
    var $checkbox = $('.pms-prop').find(':checkbox');
    var $text = $('.pms-prop').find(':text')
    var $searchResult = $('.search-list-ul')
    if($searchResult){
        $('.search-list-ul').remove()
        $('.item-member-list-info').find('ul').eq(0).removeClass('Ldn')
    }
    if($('.searchList')){
        $('.searchList').remove()
    }
    $checkbox.prop('checked',false)
    $text.val('')
}

// init
$(function(){
    // 添加人员
    $('.item-member-add .pms-btn').click(function(){
        reset();
        $('#dia').show()
    })

    //人员选择框 取消 按钮
    $('.pms-prop .pms-cancel').click(function(){
        reset();
        $('#dia').hide()
    })
    checkBoxChecked();
    manageMember();
    searchName();
    bindEnter();
    showSubList();
    pmseditor()

})
//二级显示
