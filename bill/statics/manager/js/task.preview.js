// 任务预览页 js

$(function(){
    mconfirm();
    delay();
    evaluated();
    applyDelay();
    loadassess();
})
var gmark = '';
//领取任务
mconfirm = function() {
    $('.mainCont-operate-btns').on('click','#action_receive',function(){

        if($(this).data('status') == 1 ){
            $('#spanTips').text('领取后开始记录工作时间，确定要领取？')
            $('.pms-draw .pms-btn').show()
            $('.pms-draw .pms-cancel').text('取消')
            $('.pms-draw').removeClass('Ldn')
            gmark ='lq' ;//领取事件
        }
    })
    $('.mainCont-operate-btns').on('click','#btn_remove',function(){

        $('#spanTips').text('你确定要删除此任务吗?')
        $('.pms-draw .pms-btn').text('确定');
        $('.pms-draw .pms-btn').show();
        $('.pms-draw .pms-cancel').text('取消');
        $('.pms-draw').removeClass('Ldn');
        gmark ='rm' ;//删除事件

    })

    $('.pms-draw .pms-prop-btns .pms-btn').click(function(){
        if(gmark =='') {
            return false;
        }
        ask($(this),gmark,'');
        gmark = '';
    });
    $('.pms-prop-btns .pms-cancel').click(function(){
        $(this).parents('.pms-draw').addClass('Ldn')
        gmark = '';//还原
    })
}

//申请延期
delay = function() {
    $('.mainCont-operate-btns').on('click','.btn-delay',function(){
        if($(this).data('status') == 1){
            $('#task-delay-reason').val('')
            $('#task-item-delay').val('')
            $('.pms-delay').removeClass('Ldn')
        }
    });

    var $p = {};
    //点击确定
    $('.mainCont-delay-apply .pms-btn').click(function() {
        var $hours =8;// $('#task-item-delay').val();

//        if($hours.trim() =='' || isNaN($hours)) {
//            alert('小时数为纯数字');
//            $('#task-item-delay').select();
//            return false;
//        }else
        if (Date.parse($('#task-delay-time').val()) <= Date.parse($('#max_date').val())) {
            alert('延迟日期不能早于计划完成日期['+$('#max_date').val()+']');
            $('#task-delay-time').focus();
            return false;
        } else {
            $p.date   = $('#task-delay-time').val();
            $p.hours  = $hours;
            $p.reason = ($('.mainCont-delay-apply').find('textarea').val()).trim();
            $p.max_date = $('#max_date').val();
            if($p.reason =='') {
                alert('请输入延期理由');
                $('#mainCont-delay-apply').focus();
                return false;
            }
            ask($(this),'sqyq' ,$p);
        }
    });
    //点击取消
    $('.mainCont-delay-apply .pms-cancel').click(function(){
        $(this).parents('.pms-delay').addClass('Ldn')
    })
}
//评估
evaluated = function() {
    var $desc, $descShow, $p, $remark, $star;
    $('.mainCont-operate-btns').on('click','.btn-not-evaluated',function() {
        if($(this).data('status') == 1){
            $('.pms-prop-evaluated').removeClass('Ldn')
        }
    });
    $star = $('.star span.icomoon');
    $remark = $('.mainCont-evaluated-input textarea');
    $desc = ['太差了', '一般', '合格', '较好', '很好'];
    $descShow = $('.star .mainCont-evaluated-comment');
    $p = {};
    $p.level = 1; //评星
    $p.remark = '';//评价内容
    $star.bind({
        mouseenter: function() {
            var _i;

            _i = $(this).index();
            $star.addClass('cdedede').removeClass('cf7ad28');
            $(this).parent('.star').find('span.icomoon:lt(' + _i + ')').addClass('cf7ad28');
            return $descShow.text($desc[_i - 1]);
        },
        mouseleave: function() {

            $star.addClass('cdedede').removeClass('cf7ad28');
            $(this).parent('.star').find('span.icomoon:lt(' + $(this).index() + ')').addClass('cf7ad28');
            return $($star[0]).addClass('cf7ad28');
        },
        click: function() {
            var _i;

            _i = $(this).index();
            $star.addClass('cdedede').removeClass('cf7ad28');
            $(this).parent('.star').find('span.icomoon:lt(' + _i + ')').addClass('cf7ad28');
            return $p.level = _i;
        }
    });
    $remark.change(function() {
        return $p.remark = $(this).val();
    });
    //点击确定
    $('.mainCont-evaluated .pms-btn').click(function() {
        if($p.remark =='') {
            alert('请输入评价内容');
            $('.mainCont-evaluated-input textarea').focus();
            return false;
        }
        ask($(this),'pg' ,$p);
        $(this).parents('.pms-prop').addClass('Ldn');
        $star.removeClass('cf7ad28').eq(0).addClass('cf7ad28');
        return $remark.val('');
    });
    //点击取消
    $('.mainCont-evaluated .pms-cancel').click(function(){
        $(this).parents('.pms-prop-evaluated').addClass('Ldn')
        $('.star span.icomoon').removeClass('cf7ad28').eq(0).addClass('cf7ad28')
        $descShow.text($desc[0])
        $('.mainCont-evaluated-input textarea').val('')
    })
};

applyDelay = function() {
    return $('.task-apply-list-cont .apply-reply-btn').bind({
        click: function() {
            var p ={};
            p.id = $(this).parents('tr').data('id')

            p.reply = 2;//拒绝
            if ($(this).hasClass('apply-reply-btn-yes')) {
                p.reply = 1; //同意
            }
            if(p.reply == 2 && !confirm('确定拒绝此条延期申请吗?')){
                return false;
            }

            ask($(this),'apply',p);

            return void 0;
        }
    });
};

function ask(obj,mark ,p ) {

    var query = '';
    if(mark == 'sqyq') { //申请延期的参数
        query = '&pdate='+p.date+'&phours='+p.hours+'&preason='+encodeURI(p.reason)+'&max_date='+p.max_date;
    }else if(mark =='pg') {//评估的参数
        query = '&pstar='+p.level+'&premark='+encodeURI(p.remark);
    }else if(mark =='apply') { //审核 延期申请
        query = '&aid='+p.id+'&reply='+ p.reply;
    }
    $.ajax({
        type: "POST",
        url: "/task/ajax_task_status",
        data:'mark='+mark+'&task_id='+task_id+query,
        success: function(data){
            if(data.result == 'ok') {
                if(mark == 'rm') {
                    location.href='/task/show';
                } else if(mark == 'lq') {
                    $('#action_receive').data('status',0).text('已领取').addClass('btn-received')
                    if($('.btn-delay')){
                        $('.btn-delay').removeClass('Ldn')
                    }
                    obj.parents('.pms-draw').addClass('Ldn');
                }else if(mark =='pg') {
                    $('.btn-not-evaluated').addClass('btn-evaluated').data('status',0).text('已评估');
                    obj.parents('.pms-prop').addClass('Ldn');
                    loadassess();
                }else if (mark =='sqyq') {
                    $('.btn-delay').addClass('btn-delaied').data('status', 0).text('已申请延期');
                    obj.parents('.pms-delay').addClass('Ldn');
                    reloadtop();
                }else if(mark == 'apply') {
                    obj.parents('tr').remove();
                    if ($('.mainCont-apply-list').find('tr').length < 2) {
                        $('.mainCont-apply-list').remove();
                        reloadtop();
                    }
                }
            } else if (data.result == 'error') {
                if(mark == 'sqyq' || mark == 'pg') {
                    alert('你的申请还未审核,请耐心等待...');
                    if(mark == 'sqyq') {
                        obj.parents('.pms-delay').addClass('Ldn');
                    }else {
                        obj.parents('.pms-prop').addClass('Ldn');
                    }
                }
            }else if (data.result == 'error2') {
                if(mark == 'sqyq' ) {
                    alert('申请日期选择错误,请勿选择为周末的日期');
                    obj.parents('.pms-delay').addClass('Ldn');
                }
            } else{

                if(mark =='lq' || mark == 'rm') {
                    $('#spanTips').text('服务器繁忙，请稍后再试...')
                    obj.hide().siblings('a').text('确认')
                }else if(mark =='pg') {
                    obj.parents('.pms-prop').addClass('Ldn');
                }else if(mark =='sqyq') {
                    obj.parents('.pms-delay').addClass('Ldn');
                }
                alert('服务器繁忙，请稍后再试...');
            }
            gmark = '';
        }
    });
}

//重新加载 任务预览页的头部功能按钮
function reloadtop() {
    $.ajax({
        type: "GET",
        url: "/task/detail/"+task_id,
        data:'loadbtn=mq',
        success: function(data){
            if(data){
                $('.mainCont-operate-btns').html($(data))
            }
        }
    })
}

//加载 任务评估列表
function loadassess() {
    $.ajax({
        type: "GET",
        url: "/task/ajax_load_assess",
        data:'task_id='+task_id,
        success: function(data){
            if(data){
                $('#assess_div').html($(data))
            }
        }
    })
}
