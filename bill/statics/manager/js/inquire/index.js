$(function(){
    var isAdd = false;
    initdatepicker_cn();
    $('.dateSelect').datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });

    //提交菜单表单
    $('#pms-btn').bind('click', function(){
        var url = "/manager/inquire/add";
        /*var mailReg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        if($('#user_name').val().trim() == '' ){
            alert('请填写用户名称');
            return false;
        }

        if($('#user_email').val().trim() != '' && !mailReg.test($("#user_email").val())){
            alert("请正确填写邮箱格式");
            return false;
        }
        if($('#user_phone').val().trim() == '' ){
            alert('请填写用户电话');
            return false;
        }
        var user_name = $('#user_name').val();
        var user_email = $('#user_email').val();
        var user_phone = $('#user_phone').val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var pid = $('#p_id').val();*/
        url = "/manager/inquire/add";
        $.ajax({
            type: "POST",
            url: url,
            //data: 'user_name='+user_name+'&user_email='+user_email +'&user_phone='+user_phone + '&product_id='+pid+'&start_date='+start_date+'&end_date='+end_date,
            success: function(data){
                $('#pms-btn').removeAttr("disabled");
                $('#pms-btn').css("background-image", "-moz-linear-gradient(center top , #4D90FC, #4787ED)");
                $('#pms-btn').css("border-color", "#3C7ADC");
                if(data == 0){
                    alert('系统问题');
                }else{
                    window.location.href="/manager/inquire/addTwo/id/"+data+"/pid/0";
                }
                $('#pms-btn').attr("disabled", "disabled");
                $('#pms-btn').css("background-image", "-moz-linear-gradient(center top , white, #F4F4F4)");
                $('#pms-btn').css("border-color", "gainsboro");
                $('#pms-btn').css("color", "#555555");                
                $('#dia').hide();
                return false;
            }
        });
    })

    /*$("#add_inquire").bind('click', function(){
        if(!isAdd){
            alert("请先填写正确的产品ID");
            return false;
        }
        $("#dia").css("display", "block");
    });*/

    $("#status_operate").change(function(){
    	$("#filter_state").val($("#status_operate").val());
        $("#filter").submit();
    });
    $("#responser").change(function(){
    	$("#filter_responser").val($("#responser").val());
        $("#filter").submit();
    });


    $(".orders").bind('click', function(){
        iid = $(this).attr('iid');
        if(parseInt(iid) != iid){
            alert('信息有误');
            return false;
        }
        $(this).parent().html('<div><img width="20px" height="20px" src="/statics/web2/images/load.gif"></div>');
        setdisabled(this);
        $.get('/manager/inquire/order', {id:iid}, function(data){
            if( data == 0){
                alert("接单失败，请稍后再试！");
            }else{
                window.location.href = window.location.href;
            }
        });
        
    });
    
    function setdisabled(obj) {
        setTimeout(function() { obj.disabled = true; }, 100);
    }
});