
$(function(){
    subMenu();
    subMenuLiClick();
    addItemVersion();
    
})

//下拉选择框 显示
subMenu = function() {
    $('.pms-select-show,.icon').click(function(event) {
        var $icons, $subMenu, $subMenus, $this;

        $this = $(this);
        $subMenu = $this.siblings('.pms-sub-menu');
        $subMenus = $('.pms-sub-menu');
        $icons = $('.icon.arrow');
        $subMenus.slideUp('fast');
        $icons.text('Q');
        if (!$this.siblings('.pms-sub-menu').is(':animated')) {
            if ($this.hasClass('pms-select-show')) {
                $this.siblings('.arrow').text('P');
            } else if ($this.hasClass('arrow')) {
                $this.text('P');
            }
            $subMenu.slideToggle('fast');
            return event.stopPropagation();
        }
    });

    //隐藏下拉隐藏框
    return $(document).bind({
        click: function() {
            var $icons, $subMenu;

            $subMenu = $(this).find('.pms-sub-menu');
            $icons = $(this).find('.arrow');
            if ($subMenu.is(':visible')) {
                $subMenu.slideUp('fast');
                return $icons.text('Q');
            }
        }
    });
};

//下拉选择框 选项点击事件
subMenuLiClick = function() {
    return $('.pms-sub-menu').on('click', 'li', function() {
        var $changeVal, $defaultId, $defaultval, $icon, $itemId, $subMenuBox, $subMenuShow, $that;

        $that = $(this);
        $subMenuBox = $that.parent('.pms-sub-menu');
        $icon = $subMenuBox.siblings('.icon');
        $subMenuShow = $subMenuBox.siblings('.pms-select-show');
        $defaultval = $subMenuShow.text();
        $defaultId = $subMenuShow.data('id');
        var mark = $subMenuShow.data('name');
        ////假如有删除字样，先把删除去掉
        //$changeVal = $that.find('.task-removeVersion') ? $('.task-removeVersion').remove() : void 0
        $changeVal = $that.text();
        $itemId = $that.data('id');
        switch(mark) {
            case 'task_person': //指派给
            case 'task_priority': //优先级
            case 'task_version': //版本
            case 'task_rate': //进度
                $('#'+mark).val($itemId);
                break;
            default :   //选择项目
            if ( $itemId) {
                if ($itemId !== $defaultId) {
                    change_member($itemId ,true);
                }
                $subMenuShow.val($changeVal).data('id', $itemId);
            }
        }
        $subMenuShow.val($changeVal);
        $subMenuBox.slideUp('fast');
        return $icon.text('Q');
    });
};


//改变指派人 下拉框
//isreplace 是否覆盖已有的显示值  一般情况 编辑下 才会选择不覆盖
function change_member(proj_id ,isreplace ) {

    $.ajax({
        type: "GET",
        url: "/proj/ajax_proj_members/"+proj_id,
        success: function(data){
            if(data) {
                var $text = ''
                if(data.members) {
                    $.each(data.members,function(i,n){
                        if(isreplace && i <1) {
                            $('#task-item-person').val(n.realname);
                            $('#task_person').val(n.username);
                        }
                        $text += '<li data-id="'+ n.username+'">'+n.realname+'</li>';
                    })
                    $('.task-item-person-cont').html($text);
                } else {//成员 版本选择框
                    $('#task-item-person').val('');
                    $('#task_person').val('');
                    $('.task-item-person-cont').html('');
                }
                if(data.versions) {
                    load_version_select(data.versions , isreplace );
                } else { //清空 版本选择框
                    $('#task-item-tar-version').val('');
                    $('#task_version').val('0');
                    $('.task-item-person-cont').html('');
                }
                if(data.version_html) {
                    $('#pms-prop-version-cont').html(data.version_html);
                }
                $('#proj_id').val(proj_id);
            }
        }
    });
}

function load_version_select(data ,isreplace ,repv ) {
    var idx = 0;
    var $text = '';
    $.each(data,function(x,n){
        if(isreplace ) {
            if(typeof(repv) != 'undefined') {
                if(repv == n.version) {
                    $('#task-item-tar-version').val(n.version);
                    $('#task_version').val(n.verid);
                }
            } else if(idx <1) {
                $('#task-item-tar-version').val(n.version);
                $('#task_version').val(n.verid);
            }

        }

        $text += '<li data-id="'+n.verid+'">' + n.version + '</li>';
        idx ++;
    })
    $('.task-item-tar-version-cont').html($text);
}


/*版本管理
 */

/*显示版本管理,如果有权限，才能会显弹出层,把当前的li的个数赋值给ul，且给最后一个li索引值，便于取消时判定是否有变动
 */

showVersion = function() {

    if($('#proj_id').val()=='' || $('#proj_id').val()=='0') {
        return false;
    }
    $radios = $('#pms-prop-version-cont').find(':radio');
    $.each($radios, function(i, n) {
        if ($(n).val() == $('#task-item-tar-version').val()) {
            $(this).prop('checked', true);
            return false;
        }
    });
    $('#task-add-item-version').val('').trigger('keyup');
    $('.pms-prop').removeClass('Ldn');
};
/*添加版本，当且仅当输入框不为空时，添加按钮才会响应事件
 */

addItemVersion = function() {

    var $proj_id = $('#proj_id').val();
    /*禁止输入中文,禁止复制粘贴及右侧
     */
    $('#task-add-item-version').bind({
        cut: function() {
            return false;
        },
        copy: function() {
            return false;
        },
        contextmenu: function() {
            return false;
        },
        paste: function() {
            return false;
        },
        keyup: function() {
            var $len, $val;

            $(this).val($('#task-add-item-version').val().replace(/[\u4e00-\u9fa5]/g, '')); //过滤中文
            $val = $('#task-add-item-version').val().trim();
            $len = $val.length;
            if ($len > 0) {
                return $('.pms-task-add-btn').removeClass('pms-task-no-btn').data('readonly', 1);
            } else {
                return $('.pms-task-add-btn').addClass('pms-task-no-btn').data('readonly', 0);
            }
        },
        blur:function(){
            $val = $('#task-add-item-version').val().trim();
            $len = $val.length;
            if ($len > 0) {
                return $('.pms-task-add-btn').removeClass('pms-task-no-btn').data('readonly', 1);
            } else {
                return $('.pms-task-add-btn').addClass('pms-task-no-btn').data('readonly', 0);
            }
        }
    });
    /*
      右边的  + 按钮， 不提交服务器 存在本地
    */

    return $('.pms-task-add-btn').click(function() {
        var $that, $val;

        $that = $(this);
        $val = $('#task-add-item-version').val().trim();
        $proj_id = $proj_id ? $proj_id : $('#proj_id').val();

        if ($that.data('readonly') == 1) {

            if($val.length > 30) {
                $('#task-add-item-version').select();
                return alert('版本号长度请限制在30个字符以内');
            }
            if($val=='' || trim($val,'0') =='') {
                $('#task-add-item-version').select();
                return alert('版本号不能为空格或全由数字0开头');
            }
            if(typeof(g_ver_list[$val]) != 'undefined') {
                alert('此版本已经存在，请重现输入');
                $('#task-add-item-version').select();
                return false;
            }
            g_ver_list[$val] = '-1'; //新增

            $('<li class="clearfix"><span class="Lflr"><a href="javascript:;" id="remove_version" onclick="removeVersion('+$('#proj_id').val()+',\''+$val+'\',this)" >删除</a></span><p><span class="task-version-radio"><input id="'+$val+'" type="radio" name="version-radio" value="'+$val+'"></span><label for="'+$val+'"><span class="task-version-cont">' + $val + '</span></lable></p></li>').appendTo('#pms-prop-version-cont');
            $('#task-add-item-version').val('');
            return $('#task-add-item-version').trigger('keyup');
        }
    });
};

/*添加版本，当data[readonly]为1时，会响应添加版本事件
 */

$('.pms-prop-btns .pms-btn').click(function() {

    if(isEmptyObject(g_ver_list)) {
        alert('服务器异常，请刷新页面再操作');
        return false;
    }
    var ver_str = '';
    $.each(g_ver_list,function(k,v) {
        ver_str += k+'#S#'+v+';'
    });

    //设置当前任务的版本号
    var set_ver = '';
    $radios = $('#pms-prop-version-cont').find(':radio:checked');
    $.each($radios, function(i, n) {
        set_ver = $(n).val();

    });
    var proj_id =$('#proj_id').val();
    if (ver_str!='' && proj_id !='' ) {
        return $.ajax({
            url: '/proj/ajax_modify_version',
            type: 'POST',
            data: 'proj_id=' + proj_id + '&version=' + encodeURI(trim(ver_str,';'))+'&sver='+encodeURI(set_ver),
            success: function(data) {
                if (data.result == 'ok') {
                    if(data.versions) {
                        load_version_select(data.versions,true , set_ver);
                    }
                    if(data.version_html) {
                        $('#pms-prop-version-cont').html(data.version_html);
                    }
                    $('#task-add-item-version').trigger('keyup');
                } else {
                    alert('服务器繁忙，请稍后再试…');
                    change_member(proj_id, false);
                    return false;
                }
                $('.pms-prop').addClass('Ldn');
            }
        });
    }
});

/*删除版本,如果有项目ID则可进行删除操作
 */

removeVersion = function(proj_id ,version ,obj) {

        if (proj_id && version &&confirm('确认删除该版本')) {
            $(obj).parents('li').remove();
            if(g_ver_list) {
                delete g_ver_list[version];
            }
        }
};
/*点击取消时，判断是否有变动，若有，则在表单中进行局部刷新
 */

$('.pms-prop-btns .pms-cancel').click(function() {
    $('.pms-prop').addClass('Ldn');
    change_member($('#proj_id').val(), false);
});


//添加版本 div 取消事件
$('.pms-prop .pms-cancel').click(function(){
    $(this).parents('.pms-prop').addClass('Ldn')
})

$('#reset').click(function() {
    if(task_id != '' && is_deco==''){
        if(!confirm('确定要放弃此次编辑吗?')) {
            return false;
        }
    }
    history.go(-1);
})

rsync_mark = true;

function valid() {

    if($('#proj_id').val().trim() == '') {
        alert('请选择一个项目');
        return false;
    }
    if($('#task-item-theme').val().trim() == '') {
        alert('请输入主题');
        return false;
    }
    if(!editor.hasContents()) {
        alert('请输入描述');
        editor.focus();
        //editor.getPlainTxt()
        return false;
    }
    if($('#task_person').val().trim() == '') {
        alert('请选择一个指派对象');
        return false;
    }
    if($('#task_priority').val().trim() == '') {
        alert('请选择一个优先级');
        return false;
    }

    if($('#task_version').val().trim() == '') {
        alert('请选择目标版本');
        return false;
    }

    if($('#task-item-star-time')[0].defaultValue != $('#task-item-star-time').val()) {
//        if (is_upt && Date.parse($('#task-item-star-time').val()+' 23:59') < new Date().getTime()) {
//            alert('开始日期不能小于当前日期');
//            $('#task-item-star-time').focus();
//            return false;
//        }else
        if (Date.parse($('#task-item-end-time').val()) < Date.parse($('#task-item-star-time').val())) {
            alert('完成日期不能小于开始日期');
            $('#task-item-end-time').focus();
            return false;
        }
        checkdate($('#task-item-star-time').val(),1,(is_upt ?'1':(is_deco? '2': '3')));
        if(! rsync_mark) {
            rsync_mark =true;
            $('#task-item-star-time').focus();
            return false;
        }
    }


    if($('#task-item-end-time')[0].defaultValue != $('#task-item-end-time').val()){
//        if (Date.parse($('#task-item-end-time').val()+' 23:59') < new Date().getTime()) {
//            alert('完成日期不能小于当前日期');
//            $('#task-item-end-time').focus();
//            return false;
//        } else
        if (Date.parse($('#task-item-end-time').val()) < Date.parse($('#task-item-star-time').val())) {
            alert('完成日期不能小于开始日期');
            $('#task-item-end-time').focus();
            return false;
        }

        checkdate($('#task-item-end-time').val(),0 ,(is_upt ?'1':(is_deco? '2': '3')));
        if(!rsync_mark) {
            rsync_mark = true;
            $('#task-item-end-time').focus();
            return false;
        }
    }

    return true;
}

//mark : 1:更新 2：分解 ：3新增
function checkdate(date,is_start ,mark) {
    if(date=='' || task_id=='') {
        return false;
    }
    if(mark =='3') {
        rsync_mark = true;
        return true;
    }
    $.ajax({
        url: '/task/ajax_check_done_date/'+task_id,
        type: 'GET',
        async: false,
        data: 'date=' + date + '&is_start=' + is_start+'&mark='+mark,
        success: function(data) {
            if (data.result == 'fail') {
                rsync_mark = false;
                if(data.code =='-1') {
                    alert('子任务的开始时间不能小于父任务的开始时间['+data.date+']');
                    return false;
                }else if(data.code == '-2') {
                    alert('子任务的完成时间不能大于父任务的完成时间['+data.date+']');
                    return false;
                }else if(data.code == '-3') {
                    alert('父任务开始时间不能大于子任务最小的开始时间['+data.date+']');
                    return false;
                }else if(data.code == '-4') {
                    alert('父任务完成时间不能小于子任务最大的完成时间['+data.date+']');
                    return false;
                }

            }else if(data.result == 'ok') {
                rsync_mark = true;
                return true;
            }
            return alert('服务器繁忙，请稍后再试……');
        }
    });

}