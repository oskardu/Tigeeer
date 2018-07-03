<div id="main">
	<?php $this->renderPartial('../_system_left');?>
    <div class="main-middle">
        <div class="main-middleBox">
            <div class="mainCont-manage-btns">
                <a id="add-admin" class="pms-btn pms-btn-addRole pms-btn-add" href="javascript:;">新增</a>
               
            </div>
            <div class="search">
                <form action="" title="">
                    <div class="mainCont-operate-btns">
                        <a href="<?php echo $this->createUrl('/managers/admin/index', array('state' => 1));?>"><button  class="btn-submit btn-operate" type="button">正常</button></a>
                        <a href="<?php echo $this->createUrl('/managers/admin/index', array('state' => 2));?>"><button  class="btn-cancel btn-operate" type="button">冻结</button></a>
                    </div>
                </form>
            </div>
            <div class="mainCont">
                <div class="mainCont-content">
                    <div class="manage-cont">
                        <div class="task-info-wrap">
                            <div class="manage-user-wrap">
                                <table name="manage-user-table" class="manage-user-table manage-tables">
                                    <thead>
                                        <tr>
                                            <th class="manage-user-table-th1" width="8%">账户</th>
                                            <th class="manage-user-table-th1" width="8%">姓名</th>
                                            <!-- <th class="manage-user-table-th2 Lovv" width="8%">
                                                <div class="Lposr"><span class="task-arrow">邮箱</span>
                                                </div>
                                            </th>
                                            <th class="manage-user-table-th3 Lovv" width="5%">
                                                <div class="Lposr"><span class="task-arrow">电话</span>
                                                </div>
                                            </th> -->
                                            <th class="manage-user-table-th1" width="8%">状态</th>
                                            <th class="manage-user-table-th5" width="5%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($admin_list as $admin):?>
                                        <tr>
                                            <td class="manage-user-table-user"><?php echo $admin['user_name']; ?></td>
                                            <td><?php echo $admin['user_cn_name']; ?></td>
                                            <td><?php if($admin['state'] == 1) echo '正常'; else echo "冻结";?></td>
                                            <td class="manage-user-operate">
                                                <a class="edit-admin" href="javascript:;" onclick="edit_admin(<?php echo $admin['id']; ?>)">编辑</a>
                                                
                                                <a href="javascript:;" onclick="reset_password(<?php echo $admin['id']; ?>)">重置密码</a>
                                                
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="Lposf pms-prop Ldn" id="resetpwd">
                        <table>
                            <tr>
                                <td>
                                    <div class="pms-prop-box pms-prop-little pms-prop-small">
                                        <form class="manage-cont-form">
                                            <h4>重置密码</h4>
                                            <div class="pms-prop-wrap">
                                                <div class="field-input">
                                                    <label for="manage-cont-user">新的密码：
                                                        <input id="new-password" type="password" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div class="field-input">
                                                    <label for="manage-cont-user">确认密码：
                                                        <input id="re-password" type="password" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div class="pms-prop-btns">
                                                    <a href="javascript:;" id="pms-resetpwd-cancel" class="pms-cancel">取消</a>
                                                    <a href="javascript:;" id="pms-resetpwd-btn" class="pms-btn" >提交</a></div>
                                                </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="Lposf pms-prop Ldn" id="dia">
                        <table>
                            <tr>
                                <td>
                                    <div class="pms-prop-box pms-prop-little pms-prop-small">
                                            <h4>编辑账户</h4>
                                            <div class="pms-prop-wrap">
                                                <div class="field-input">
                                                    <label for="manage-cont-user">姓名：
                                                        <input id="user_cn_name" type="text" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div id="pwd-div" class="field-input">
                                                    <label for="manage-cont-user">密码：
                                                        <input id="password" type="password" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div class="field-input">
                                                    <label for="manage-cont-user">邮箱：
                                                        <input id="email" type="text" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div class="field-input">
                                                    <label for="manage-cont-user">手机：
                                                        <input id="phone" type="text" value="" class="pms-prop-small-input">
                                                    </label>
                                                </div>
                                                <div class="field-input">
                                                    <label for="manage-cont-user">权限：
                                                        <select id="role" name="state" class="pms-prop-small-input">
                                                            <option value="1">正常</option>
                                                            <option value="2">设计师</option>
                                                            <option value="3">超级管理员</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="field-input">
                                                    <label for="manage-cont-user">状态：
                                                        <select id="state" name="state" class="pms-prop-small-input">
                                                            <option value="1">正常</option>
                                                            <option value="2">冻结</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div>
                                                    <div class="pms-prop-btns">
                                                        <a href="javascript:;" id="pms-cancel" class="pms-cancel">取消</a>
                                                        <a href="javascript:;" id="pms-btn" class="pms-btn">提交</a>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    var uid;
    var isadd = true;
    var pwdChange = false;
    
    function reset_permit() {
        var obj = $("[name='permission']");
        for(var i=0;i<obj.length ;i++) {
            obj[i].checked = false;
        }
    }
    //重置密码
    function reset_password(id){
        uid = id;
        $("#resetpwd").css("display", "block");
    }
    
    //新增账户
    $("#add-admin").bind('click', function(){
        isadd = true;
        reset_permit();
        $("#username").removeAttr("readonly")
        $("#pwd-div").css("display", "block");
        $("[class='pms-prop-small-input']").val('');
        $("#dia").css("display", "block");
    });

    //编辑账户
    function edit_admin(id){
        uid = id;
        $("#dia").css("display", "block");
        isadd = false;
        $("#username").attr("readonly","readonly");
        $("#pwd-div").css("display", "none");
        reset_permit();
        $.get('/managers/admin/edit', {id:id}, function(data, status){
            var info = '';
            info = eval("("+data+")");
            $("#user_cn_name").val(info.user_cn_name);
            $("#email").val(info.user_name);
            $("#phone").val(info.user_phone);
            $("#state").val(info.state);
        });
        
    }

    $('#pms-resetpwd-btn').bind('click', function(){
        if($('#new-password').val().trim() == '' || $('#re-password').val().trim() == ''){
            alert("请填写新的密码和确认密码");
            return false;
        }
        if($('#new-password').val().trim() != $('#re-password').val().trim()){
            alert("新的密码和确认密码不一致");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/managers/admin/resetPassword/id/"+uid,
            data: 'password='+$("#new-password").val(),
            success: function(data)
            {
                if(data == 1){
                    $('#resetpwd').hide();
                    alert("重置密码成功");
                    return true;
                } else {
                    alter('服务器发生未知错误');
                }
                $('#resetpwd').hide();
                return false;
            }
        });
    });
    //提交管理员表单
    $('#pms-btn').bind('click', function(){
        $('#pms-btn').attr("disabled", "disabled");
        $('#pms-btn').css("background-image", "-moz-linear-gradient(center top , white, #F4F4F4)");
        $('#pms-btn').css("border-color", "gainsboro");
        $('#pms-btn').css("color", "#555555");
        var permission ='';
        var obj = $("[name='permission']");
        var url = "/managers/admin/add";
        var mailReg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        for(var i=0;i<obj.length ;i++) {
            if(obj[i].checked) {
                permission += obj[i].value+',' ;
            }
        }

        permission = trim(permission, ',');

        if(isadd && $("#password").val().trim() == ''){
            alert('请填写密码');
            return false;
        }

        // if($("#email").val().trim() == ''){
        //     alert("请填写邮箱");
        //     return false;
        // }

        if(!mailReg.test($("#email").val())){
            alert("请正确填写邮箱格式");
            return false;
        }
        
        if($("#phone").val().trim()== ''){
            alert('请填写手机');
            return false;
        }
        
        
        var username = $('#email').val();
        var user_cn_name = $('#user_cn_name').val();
        var state = $('#state').val();
        var phone = $('#phone').val();
        var password = $("#password").val();
        var passwordFieldString = "";
        var managers = $("#role").val();
        var group = $("#group").val();
        if(isadd){
            passwordFieldString = "&password="+password;
        }else{
            url = "/managers/admin/edit/id/"+uid;
        }
        
        $.ajax({
            type: "POST",
            url: url,
            data: 'permission='+permission+'&managers='+managers+'&group='+group+'&username='+username+'&user_cn_name='+user_cn_name+'&email='+email+'&phone='+phone + '&state='+state+ passwordFieldString +(isadd ? '' : '&id='+uid),
            success: function(data){
                $('#pms-btn').removeAttr("disabled");
                $('#pms-btn').css("background-image", "-moz-linear-gradient(center top , #4D90FC, #4787ED)");
                $('#pms-btn').css("border-color", "#3C7ADC");
                if(data == 1){
                    $('#dia').hide();
                    alert(!isadd ? '编辑成功' :'成功添加');
                    window.location.reload();
                    return true;
                }else if (data == 2){
                    alert('参数错误，请仔细检查');
                    return false;
                }else if (data == 3) {
                    alert('用户名重复');
                    return false;
                }else{
                    alter('服务器发生未知错误，请稍候重试');
                    
                }
                $('#dia').hide();
                return false;
            }
        });
    })    
</script>