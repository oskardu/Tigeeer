<link rel="stylesheet" type="text/css" href="/statics/manager/css/jquery-ui-timepicker-addon.css">
<div id="main">
    <?php echo $this->renderPartial('../_left');?>
    <div class="main-middle">
        <div class="main-middleBox">
            <div class="mainCont">
                <form id="destination-form" action="#" method="post" class="task-add" enctype ="multipart/form-data">
                    <div class="mainCont-operate-btns">
                        <p>
                            <button type="reset" value="返回" class="btn-cancel btn-operate" onclick="history.go(-1);">返回</button>
                        </p>
                    </div>
                    <div class="mainCont-content">
                        <div class="task-info-wrap" style="font-size:16px;">
                            <div class="task-info-top">
                                <h2 class="task-add-tips">物流追踪/订单号：<?php echo $id;?></h2>
                            </div>
                            <table>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme" class="c3c7adc">到达时间</label>
                                    </td>
                                    
                                    <td colspan="2">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span class="c3c7adc">到达目的地</span>
                                    </td>
                                    <td colspan="2">慎重操作</td>
                                </tr>
                                <?php foreach ($model as $v) {
                                    
                                ?>
                                    <tr>
                                        <td class="tar">
                                            <label for="task-item-theme"><?php echo $v->track_time;?></label>
                                        </td>
                                        
                                        <td colspan="2">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <span><?php echo $v->track_address;?></span>
                                        </td>
                                        <td class="tac"><a href="<?php echo $this->createUrl("/managers/order/trackdelete", array('id' => $v->id,'order_id'=>$v->order_id)); ?>">删除</a></td>
                                    </tr>
                                <?php }?>
                                <tr><td> </td></tr>
                                <tr><td> </td></tr>
                                <tr><td> </td></tr>
                                <form id="destination-form" action="#" method="post" class="task-add" enctype ="multipart/form-data">
                                    <tr>
                                        <td class="tar">
                                           <input placeholder="到达时间" readonly  id="task-item-star-time" type="text" name="Order[track_time]" value="" class="task-item-time dateSelect"><span class="icomoon calendarIcon">U</span> 
                                        </td>
                                        
                                        <td colspan="2">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input placeholder="到达目的地" type="text" name="Order[track_address]" value="" style="width:500px">
                                        </td>
                                        <td colspan="1"><button type="submit" value="完成" class="btn-submit btn-operate">提交</button></td>
                                        <input type="hidden" name="Order[order_id]" value="<?php echo $id;?>"/>
                                    </tr>
                                    
                                </form>
                                <tr><td> </td></tr>
                                <tr><td> </td></tr>
                                <tr><td> </td></tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/statics/manager/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/statics/manager/js/jquery-ui-timepicker-zh-CN.js"></script>

<script>
    initdatepicker_cn();
    $('.dateSelect').datetimepicker({
        
        timeFormat: "HH:mm:ss",
        dateFormat: 'yy-mm-dd',
    });

    $(".btn-submit").bind("click", function(){
        
        if ($("input[name='Order[track_time]']").val() == "") {
            alert("请填写到达时间");
            return false;
        }
        if ($("input[name='Order[track_address]']").val() == "") {
            alert("请填写到达目的地");
            return false;
        }
        $("#destination-form").submit();
    });
</script>

<script>

</script>