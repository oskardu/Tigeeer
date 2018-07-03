<div id="main">
    <?php echo $this->renderPartial('../_left');?>
    <div class="main-middle">
        <div class="main-middleBox">
            <div class="mainCont">
                <form id="destination-form" action="#" method="post" class="task-add" enctype ="multipart/form-data">
                    <div class="mainCont-operate-btns">
                        <p>
                            <button type="submit" value="完成" class="btn-submit btn-operate">完成</button>
                            <button type="reset" value="取消" class="btn-cancel btn-operate" onclick="history.go(-1);">取消</button>
                        </p>
                    </div>
                    <div class="mainCont-content">
                        <div class="task-info-wrap">
                            <div class="task-info-top">
                                <h2 class="task-add-tips"><?php if('add' == $this->action->id) echo "添加"; else echo "编辑";?>物流订单</h2>
                            </div>
                            <table>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme"><span style="color: red;">*</span> 订单号：</label>
                                    </td>
                                    <td colspan="2">
                                        
                                        <input id="task-item-theme" name="Order[order_id]" value="<?php echo $model->order_id;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">订单状态</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[state]">
                                            <option value="1" <?php if(1 == $model->state) echo 'selected'; ?>>上门取件</option>
                                            <option value="2" <?php if(2 == $model->state) echo 'selected'; ?>>协议签署</option>
                                            <option value="3" <?php if(3 == $model->state) echo 'selected'; ?>>已揽件</option> 
                                            <option value="4" <?php if(4 == $model->state) echo 'selected'; ?>>发车</option>
                                            <option value="5" <?php if(4 == $model->state) echo 'selected'; ?>>派车</option>
                                            <option value="6" <?php if(4 == $model->state) echo 'selected'; ?>>已收件</option>
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">托运物流单号</label>
                                    </td>
                                    <td colspan="2">
                                        
                                        <input  id="task-item-theme" name="Order[logistics_id]" value="<?php echo $model->logistics_id;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">托运物流名称</label>
                                    </td>
                                    <td colspan="3">
                                        <input id="task-item-theme" type="text" name="Order[logistics_name]" value="<?php echo $model->logistics_name;?>">
                                    </td>
                                </tr>                                
                                                           
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">取货方式</label>
                                    </td>
                                    <td>
                                        
                                       <select name="Order[pick_up]">
                                            <option value="1" <?php if(1 == $model->pick_up) echo 'selected'; ?>>上门取货</option>
                                            <option value="2" <?php if(2 == $model->pick_up) echo 'selected'; ?>>送至网点</option>                                           
                                       </select>
                                    </td>
                                    
                                </tr>  
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">发货人</label>
                                    </td>
                                    <td colspan="2">
                                        <input id="task-item-theme" type="text" name="Order[sender]" value="<?php echo $model->sender;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">结算方式</label>
                                    </td>
                                    <td>
                                        <select name="Order[pick_up]">
                                            <option value="1" <?php if(1 == $model->settlement_way) echo 'selected'; ?>>月结</option>
                                            <option value="2" <?php if(2 == $model->settlement_way) echo 'selected'; ?>>到付</option> 
                                            <option value="3" <?php if(3 == $model->settlement_way) echo 'selected'; ?>>现金</option>                                           
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">发货地址</label>
                                    </td>
                                    <?php $address = explode('#', $model->send_destination);?>
                                    <td colspan="5">
                                        <input  type="text" name="send_province" value="<?php echo $address[0];?>"> 省 <input  type="text" name="send_city" value="<?php echo $address[1];?>"> 市 <input  type="text" name="send_county" value="<?php echo $address[2];?>"> 县/区
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">发货详细地址</label>
                                    </td>
                                    <td colspan="5">
                                        <input id="task-item-theme" type="text" name="Order[send_address]" value="<?php echo $model->send_address;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">发货人手机号</label>
                                    </td>
                                    <td colspan="2">
                                        <input id="task-item-theme" type="text" name="Order[send_phone]" value="<?php echo $model->send_phone;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">收货人姓名</label>
                                    </td>
                                    <td colspan="2">
                                        <input id="task-item-theme" type="text" name="Order[accept_name]" value="<?php echo $model->accept_name;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">收货人地址</label>
                                    </td>
                                    <?php $accept_address = explode('#', $model->accept_destination);?>
                                    <td colspan="5">
                                        <input  type="text" name="accept_province" value="<?php echo $accept_address[0];?>"> 省 <input  type="text" name="accept_city" value="<?php echo $accept_address[1];?>"> 市 <input  type="text" name="accept_county" value="<?php echo $accept_address[2];?>"> 县/区
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">收货人详细地址</label>
                                    </td>
                                    <td colspan="5">
                                        <input id="task-item-theme" type="text" name="Order[accept_address]" value="<?php echo $model->accept_address;?>">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">收货人手机号</label>
                                    </td>
                                    <td colspan="2">
                                        <input id="task-item-theme" type="text" name="Order[accept_phone]" value="<?php echo $model->accept_phone;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物名称</label>
                                    </td>
                                    <td colspan="5">
                                        <input id="task-item-theme" type="text" name="Order[goods_name]" value="<?php echo $model->goods_name;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物重量</label>
                                    </td>
                                    <td colspan="1">
                                        <input id="task-item-theme" type="text" name="Order[goods_weight]" value="<?php echo $model->goods_weight;?>">千克
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物结算重量</label>
                                    </td>
                                    <td colspan="1">
                                        <input id="task-item-theme" type="text" name="Order[goods_pay_weight]" value="<?php echo $model->goods_pay_weight;?>">千克
                                    </td>
                                </tr>
             	                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物件数</label>
                                    </td>
                                    <td colspan="1">
                                        <input id="task-item-theme" type="text" name="Order[goods_num]" value="<?php echo $model->goods_num;?>">件
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物体积</label>
                                    </td>
                                    <td colspan="1">
                                        <input id="task-item-theme" type="text" name="Order[goods_volume]" value="<?php echo $model->goods_volume;?>">立方米
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">货物保价</label>
                                    </td>
                                    <td colspan="1">
                                        <input id="task-item-theme" type="text" name="Order[insured]" value="<?php echo $model->insured;?>">元
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">签收回单</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[receipt_order]">
                                            <option value="1" <?php if(1 == $model->receipt_order) echo 'selected'; ?>>原件货单</option>
                                            <option value="2" <?php if(2 == $model->receipt_order) echo 'selected'; ?>>运输签单</option> 
                                                                                      
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">签收回单类型</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[receipt_order]">
                                            <option value="1" <?php if(1 == $model->receipt_type) echo 'selected'; ?>>原件</option>
                                            <option value="2" <?php if(2 == $model->receipt_type) echo 'selected'; ?>>传真</option> 
                                                                                      
                                       </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">运输方式</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[receipt_order]">
                                            <option value="1" <?php if(1 == $model->transport_type) echo 'selected'; ?>>普通汽运</option>
                                            <option value="2" <?php if(2 == $model->transport_type) echo 'selected'; ?>>定时达</option> 
                                                                                      
                                       </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">交货方式</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[receipt_order]">
                                            <option value="1" <?php if(1 == $model->delivery_type) echo 'selected'; ?>>网点自提</option>
                                            <option value="2" <?php if(2 == $model->delivery_type) echo 'selected'; ?>>送货上门</option>
                                            <option value="3" <?php if(3 == $model->delivery_type) echo 'selected'; ?>>送货上楼</option> 
                                            <option value="4" <?php if(4 == $model->delivery_type) echo 'selected'; ?>>专车配送</option>
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">收费项目</label>
                                    </td>
                                    
                                    <td>
                                        <select name="Order[receipt_order]">
                                            <option value="1" <?php if(1 == $model->pay_service) echo 'selected'; ?>>专人清点</option>
                                            <option value="2" <?php if(2 == $model->pay_service) echo 'selected'; ?>>预约交货</option> 
                                            <option value="3" <?php if(3 == $model->pay_service) echo 'selected'; ?>>报关进仓</option>                                          
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">签收时间</label>
                                    </td>
                                    <td colspan="1">
                                        <input placeholder="签收时间" readonly  id="task-item-star-time" type="text" name="Order[receipt_time]" value="<?php echo $model->receipt_time; ?>" class="pms-select-show task-item-time dateSelect"><span class="icomoon calendarIcon icon">U</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">签收人</label>
                                    </td>
                                    <td colspan="1">
                                        <input type="text" name="Order[receipt_name]" value="<?php echo $model->receipt_name; ?>" >
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme">备注</label>
                                    </td>
                                    <td colspan="5">
                                        <input id="task-item-theme" type="text" name="Order[note]" value="<?php echo $model->note;?>">
                                    </td>
                                </tr> 
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
<script>
    initdatepicker_cn();
    $('.dateSelect').datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });

    $(".btn-submit").bind("click", function(){
        
        if ($("input[name='Order[sender]']").val() == "") {
            alert("请填写发货人姓名");
            return false;
        }
        if ($("input[name='Order[accept_name]']").val() == "") {
            alert("请填写收货人姓名");
            return false;
        }
        $("#destination-form").submit();
    });
</script>

<script>

</script>