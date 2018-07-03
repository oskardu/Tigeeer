var PRICE = {
    "bedroom":0,
    "start":0,
    "end":0,
    "day":0,
    "adjustive_day":0,
    "room_detail_price":{},//每天的价格信息以及折扣
    "all_room_price":0,//房费价格，不包含加床，不含天数减免优惠，价格原始币种
    "all_room_price_rmb":0,//房费价格，不包含加床，不含天数减免优惠，RMB价格
    "adjustive_price":0,//天数减免的优惠价格，原始币种
    "extra_adult_bed_rate":ORDER_DATA.extra_adult_bed_rate,//成人加床费,原始币种
    "extra_kid_bed_rate":ORDER_DATA.extra_kid_bed_rate,//儿童加床费,原始币种
    "adult_bed_num":0,
    "kid_bed_num":0,
    "airport_price":0,//接机费，原始币种，税后价格
    "coupon_price":0,//优惠劵，人民币
    "flights_price":0,//接票价格，人民币
    "insurance_price":0,//保险售价，人民币
    "tax_formula":0,//税费计算方式
    "service_price":0,//服务税价格,原始币种
    "local_price":0,//政府税价格,原始币种
    "breakfast_price":0,//早餐价格，原始币种，税后价格
    "invoice":0,//发票价格，人民币
    "total_price":0,//总价格，人民币
    "flights_out_price":0,//机票支出
    "insurance_out_price":0,//保险支出
    "pay_poundage":0,//支付手续费
    "remit_poundage":0,//汇款手续费
    "swap_diff":0,//换汇差额
    "bedroom_flag":0,//
    'profit':0,//别墅利润 发票使用
    "service_price_rmb":0,
    "local_price_rmb":0,
    "adjustive_price_rmb":0,
};

$(function(){
    $(".btn-submit").bind("click", function(){
        var rank_list = $("#rank_list").val();
        if(rank_list){
            if(inquire_state.indexOf($("#i_state").val())>-1){
                if(confirm("该订单已使用优惠券，修改该状态会退回用户所用优惠券。")){
                    _data = {rank_list:rank_list,i_id:i_id};
                    $.post('/manager/inquire/SetRankByInquireId',_data,function(d){
                        if(d.code==0){
                            alert(d.msg);
                            return false;
                        }
                    },'json');
                }else{
                    return false;
                }
            }
        }
        if (($("#i_state").val() == 4 || $("#i_state").val() == 7 ) && ($("input[name='inquire[pay_date]']").val() == "" || $("input[name='inquire[pay_date]']").val() == "0000-00-00")) {
            alert("如果客服已付款，请填写付款时间");
            $("input[name='inquire[pay_date]']").focus();
            return false;
        }
        if($("select[name='inquire[pay_type]']").val()=="" && $("input[name='inquire[pay_date]']").val() != "" && $("input[name='inquire[pay_date]']").val() != "0000-00-00"){
            alert("请选择付款方式");
            $("select[name='inquire[pay_type]']").focus();
            return false;
        }
    	$("#inquire-form").submit();
    });
    //绑定时间
    $('.dateSelect').datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
    auto_select();
    cost();
    //卧室类型变化
    $('#chosen-select,#adjustive_day,#add_adult_bed_num,#add_kid_bed_num,#airport_transfer_rate,#breakfast_rate,#flights,#insurance_rate,#coupon_rate_input,#invoice_pro,#tax_formula,#start_date,#end_date,.adult_bed_rate,.kid_bed_rate').change(cost);
    $("#sl_f_o,#sl_i_o,#sl_pay_o,#sl_remit_o,#sl_p_i,#sl_p_o,#sl_swap_diff,#sl_swap_ratio").change(function(){
        PRICE.invoice = retain(PRICE.profit* ORDER_DATA.exchange_rate*parseFloat($("#invoice_pro").find("option:selected").attr('data-rate')));
        profits_cost(false);
    });
    associated_villa();

    $("#i_state").change(state);
    state();
    $("#chosen-select").change(function(){
    	$("#bedroom_flag").val($(this).find("option:selected").attr('data-flag'));
    });
});

function state(){
    var val = $('#i_state').val();
    $("#cancel_reson select,#deprecated_reson select").attr("disabled","disabled");
    if(val == 5){
        $("#cancel_reson select").removeAttr('disabled');
        $("#deprecated_reson select").val(0);
    }else if(val == -1){
        $("#deprecated_reson select").removeAttr('disabled');
        $("#cancel_reson select").val(0);
    }else{
        $("#deprecated_reson select,#cancel_reson select").val(0);
    }
}

//价格清单信息刷新
function flush_interface(){
    var str = "<div class='room_day_price'><span class='item_price'>时间</span><span>"+ORDER_DATA.currency+" 兑RMB"+ORDER_DATA.exchange_rate+"</span><span>人民币</span><span>折扣率</span></div>";
    var i = 0;
    for(var val in PRICE.room_detail_price){
        str += "<div class='room_day_price'><span class='item_price'>"+val+"</span><span>"+PRICE.room_detail_price[val]['original_price']*PRICE.room_detail_price[val]['discount_ratio']+"</span><span>"+Math.ceil(PRICE.room_detail_price[val]['original_price'] * ORDER_DATA.exchange_rate*PRICE.room_detail_price[val]['discount_ratio'])+"</span><span>"+PRICE.room_detail_price[val]['discount_ratio']+"</span></div>";
        i++;
    }
    $("#room_detail_list").html(str);
    var total_room = "<div class='room_day_price total_room'><span class='item_price'>"+PRICE.day+"天房费合计</span><span>"+PRICE.all_room_price+"</span><span>"+retain(PRICE.all_room_price_rmb) +"</span><span></span></div>";

    if(PRICE.adjustive_day != 0){
        total_room += "<div class='room_day_price'><span class='item_price'>"+$("#adjustive_day").find("option:selected").text()+"</span><span>"+PRICE.adjustive_price+"</span><span>"+retain(PRICE.adjustive_price_rmb)+"</span><span></span></div>";
    }

    if(PRICE.adult_bed_num){
        total_room += "<div class='room_day_price'><span class='item_price'>"+(PRICE.day+PRICE.adjustive_day)+ "天*"+PRICE.adult_bed_num+"个成人加床</span><span>"+retain(PRICE.extra_adult_bed_rate * PRICE.adult_bed_num*(PRICE.day+PRICE.adjustive_day))+"</span><span>"+retain(PRICE.extra_adult_bed_rate * PRICE.adult_bed_num * ORDER_DATA.exchange_rate*(PRICE.day+PRICE.adjustive_day))+"</span><span></span></div>";
    }

    if(PRICE.kid_bed_num){
        total_room += "<div class='room_day_price'><span class='item_price'>"+(PRICE.day+PRICE.adjustive_day)+ "天*"+PRICE.kid_bed_num+"个儿童加床</span><span>"+retain(PRICE.extra_kid_bed_rate * PRICE.kid_bed_num*(PRICE.day+PRICE.adjustive_day))+"</span><span>"+retain(PRICE.extra_kid_bed_rate * PRICE.kid_bed_num * ORDER_DATA.exchange_rate*(PRICE.day+PRICE.adjustive_day))+"</span><span></span></div>";
    }

    total_room += "<div class='room_day_price'><span class='item_price'>服务税费</span><span>"+PRICE.service_price+"</span><span>"+retain(PRICE.service_price_rmb)+"</span><span></span></div>";
    total_room += "<div class='room_day_price'><span class='item_price'>政府税费</span><span>"+PRICE.local_price+"</span><span>"+retain(PRICE.local_price_rmb)+"</span><span></span></div>";

    if(PRICE.breakfast_price){
        total_room += "<div class='room_day_price'><span class='item_price'>早餐</span><span>"+PRICE.breakfast_price+"</span><span>"+retain(PRICE.breakfast_price * ORDER_DATA.exchange_rate)+"</span><span></span></div>";
    }
    if(PRICE.airport_price){
        total_room += "<div class='room_day_price'><span class='item_price'>接机</span><span>"+PRICE.airport_price+"</span><span>"+retain(PRICE.airport_price * ORDER_DATA.exchange_rate)+"</span><span></span></div>";
    }
    if(PRICE.insurance_price) {
        total_room += "<div class='room_day_price'><span class='item_price'>保险</span><span></span><span>" + PRICE.insurance_price + "</span><span></span></div>";
    }
    if(PRICE.flights_price) {
        total_room += "<div class='room_day_price'><span class='item_price'>机票</span><span></span><span>" + PRICE.flights_price + "</span><span></span></div>";
    }
    //if(PRICE.invoice){
    //    total_room += "<div class='room_day_price'><span class='item_price'>发票</span><span></span><span>"+PRICE.invoice+"</span><span></span></div>";
    //}
    if(PRICE.coupon_price) {
        total_room += "<div class='room_day_price'><span class='item_price'>优惠劵</span><span></span><span>- " + PRICE.coupon_price + "</span><span></span></div>";
    }
    total_room += "<div class='room_day_price total_room'><span class='item_price'>费用总计</span><span></span><span>"+PRICE.total_price+"</span><span></span></div>";

    $(total_room).appendTo("#room_detail_list");
    profits_cost(true);
}


//成本利润价格明细数据刷新
function profits_cost(flag){
    var villa_in = parseFloat($("#sl_p_i").val());
    var villa_out = parseFloat($("#sl_p_o").val());
    var villa_profits = 0;

    if(flag && !(Module == 'ORDER' && profits.counter == 0 && !(villa_in == 0 && villa_out == 0 && villa_profits == 0))){

        villa_in = retain(PRICE.all_room_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*(PRICE.day+PRICE.adjustive_day) + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*(PRICE.day+PRICE.adjustive_day) + PRICE.adjustive_price + PRICE.service_price + PRICE.local_price + PRICE.breakfast_price + PRICE.airport_price);
        villa_out = retain(villa_in - (PRICE.all_room_price+PRICE.adjustive_price) * ORDER_DATA.commission_ratio);
        villa_profits = retain((PRICE.all_room_price+PRICE.adjustive_price) * ORDER_DATA.commission_ratio - PRICE.swap_diff);
    }else{
        villa_profits = retain(villa_in - villa_out);
    }


    $("#sl_p_i").val(villa_in);
    $("#sl_p_o").val(villa_out);
    $("#sl_p_g").text(villa_profits);
    $("#sl_p_i_rmb").text(retain(villa_in * ORDER_DATA.exchange_rate));
    $("#sl_p_o_rmb").text(retain(villa_out * ORDER_DATA.exchange_rate));
    $("#sl_p_g_rmb").text(retain(villa_profits * ORDER_DATA.exchange_rate));

    profits.counter ++;

    $("#sl_f_i").text(PRICE.flights_price);
    PRICE.flights_out_price = retain($("#sl_f_o").val());
    $("#sl_f_g").text(PRICE.flights_price - PRICE.flights_out_price);

    $("#sl_i_i").text(PRICE.insurance_price);
    PRICE.insurance_out_price = retain($("#sl_i_o").val());
    $("#sl_i_g").text(PRICE.insurance_price - PRICE.insurance_out_price);
    $("#sl_swap_diff").val( retain( ($("#sl_swap_ratio").val()-ORDER_DATA.exchange_rate) * $("#sl_p_o").val() ) );
    PRICE.pay_poundage = retain($("#sl_pay_o").val());
    PRICE.remit_poundage = retain($("#sl_remit_o").val());
    PRICE.swap_diff = retain($("#sl_swap_diff").val());
    $("#sl_preferential_o").text(PRICE.coupon_price);


    PRICE.profit = villa_profits;  //别墅利润
    PRICE.invoice = retain(PRICE.profit* ORDER_DATA.exchange_rate*parseFloat($("#invoice_pro").find("option:selected").attr('data-rate')));
    $("#sl_invoice_price").text(PRICE.invoice);

    $("#gross").text(retain(villa_profits* ORDER_DATA.exchange_rate + PRICE.flights_price - PRICE.flights_out_price + PRICE.insurance_price - PRICE.insurance_out_price - PRICE.pay_poundage - PRICE.remit_poundage - PRICE.coupon_price - PRICE.swap_diff-PRICE.invoice));

    var paraString = "add_bed_rate:"+(PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day)+"|service_rate:"+PRICE.service_price+"|location_rate:"+PRICE.local_price+"|all_rate:"+PRICE.total_price+"|room_rate:"+PRICE.all_room_price+"|other_rate:"+0+"|service_item_tax:"+0+"|service_item_taxNo:"+0+"|privilege_day:"+PRICE.adjustive_day+"|privilege_price:"+PRICE.adjustive_price+"|rmb_exchange_rate:"+ORDER_DATA.exchange_rate+"|exchange_type:"+ORDER_DATA.currency;
    $("#paraString").val(paraString);

}
profits.counter = 0;

function cost(){

    PRICE.start = $("#start_date").val();
    PRICE.end = $("#end_date").val();
    PRICE.bedroom = parseFloat($('#chosen-select').val());
    PRICE.adjustive_day =  parseFloat($("#adjustive_day").val());
    PRICE.adult_bed_num = parseInt($("#add_adult_bed_num").val());
    PRICE.kid_bed_num = parseInt($("#add_kid_bed_num").val());
    PRICE.airport_price = parseFloat($("#airport_transfer_rate").val());
    PRICE.coupon_price = parseFloat($("#coupon_rate_input").val());
    PRICE.flights_price = parseFloat($("#flights").val());
    PRICE.insurance_price = parseFloat($("#insurance_rate").val());
    PRICE.tax_formula = parseInt($('#tax_formula').val());
    PRICE.breakfast_price = parseFloat($("#breakfast_rate").val());
    PRICE.day = getDateDiff(PRICE.start,PRICE.end);
    PRICE.bedroom_flag = parseInt($("#bedroom_flag").val());
    PRICE.extra_adult_bed_rate = parseFloat($(".adult_bed_rate").val());
    PRICE.extra_kid_bed_rate = parseFloat($(".kid_bed_rate").val());
    

    var data = {
    		pid:ORDER_DATA.p_id, 
    		start:PRICE.start, 
    		end:PRICE.end, 
    		bedroom:PRICE.bedroom, 
    		adjustive_daly:PRICE.adjustive_day, 
    		bedroom_flag:PRICE.bedroom_flag, 
    		currency:ORDER_DATA.exchange_rate,
    		charge_method:PRICE.tax_formula,
    };
    $.ajax({
        'type':"GET",
        'data':data,
        'url':'/manager/orders/costdetail',
        'dataType':'json',
        success:function(mes){
            if(mes.code){
                PRICE.room_detail_price = mes.data.detail_price;
                PRICE.all_room_price = retain(mes.data.all_room_price);
                PRICE.adjustive_price = retain(mes.data.adjustive_price);
                PRICE.adjustive_price_rmb = retain(mes.data.adjustive_price_rmb);
                PRICE.all_room_price_rmb = mes.data.all_room_price_rmb;
                PRICE.service_price = retain(mes.data.service_fee);
                PRICE.local_price = retain(mes.data.tax);
                PRICE.service_price_rmb = retain(mes.data.service_fee_rmb);
                PRICE.local_price_rmb = retain(mes.data.tax_rmb);
                /*if(PRICE.tax_formula == 1){
                    getAllRate_1();
                }else if(PRICE.tax_formula == 2){
                    getAllRate_2();
                }else if(PRICE.tax_formula == 3){
                    getAllRate_3();
                }else if(PRICE.tax_formula == 4){
                    getAllRate_4();
                }*/

                //PRICE.invoice = retain((((PRICE.all_room_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day) + PRICE.service_price + PRICE.local_price) * ORDER_DATA.exchange_rate) * parseFloat($("#invoice_pro").find("option:selected").attr('data-rate')));
                PRICE.total_price = Math.floor( PRICE.all_room_price_rmb + ( PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*(PRICE.day+PRICE.adjustive_day) + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*(PRICE.day+PRICE.adjustive_day) + PRICE.adjustive_price  + PRICE.breakfast_price + PRICE.airport_price) * ORDER_DATA.exchange_rate + PRICE.service_price_rmb + PRICE.local_price_rmb  + PRICE.flights_price + PRICE.insurance_price - PRICE.coupon_price);
                PRICE.invoice = retain(PRICE.profit* ORDER_DATA.exchange_rate*parseFloat($("#invoice_pro").find("option:selected").attr('data-rate')))
                flush_interface();

            }else{
                PRICE.room_detail_price = {};
                PRICE.all_room_price = 0;
                PRICE.adjustive_price = 0;
                //alert(mes.msg);
                return;
            }
        }
    });
}

function retain(val){
    var num = new Number(val);
    return parseFloat(num.toFixed(1));
}

//价格计算方式1
function getAllRate_1(){
    PRICE.service_price = retain((PRICE.all_room_price + PRICE.adjustive_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day) * ORDER_DATA.service_fee);
    PRICE.local_price = retain((PRICE.all_room_price + PRICE.adjustive_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day) * ORDER_DATA.tax_ratio);
}
//价格计算方式2
function getAllRate_2(){
    PRICE.service_price = retain((PRICE.all_room_price + PRICE.adjustive_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day) * ORDER_DATA.service_fee);
    PRICE.local_price = retain((PRICE.all_room_price + PRICE.adjustive_price + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day + PRICE.service_price) * ORDER_DATA.tax_ratio);
}
//价格计算方式3
function getAllRate_3(){
    PRICE.service_price = retain((PRICE.all_room_price * (1 - ORDER_DATA.commission_ratio) + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day + PRICE.adjustive_price ) * ORDER_DATA.service_fee);
    PRICE.local_price = retain((PRICE.all_room_price * (1 - ORDER_DATA.commission_ratio) + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day + PRICE.adjustive_price) * ORDER_DATA.tax_ratio);
}
//价格计算方式4
function getAllRate_4(){
    PRICE.service_price = retain((PRICE.all_room_price * (1 - ORDER_DATA.commission_ratio) + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day + PRICE.adjustive_price) * ORDER_DATA.service_fee);
    PRICE.local_price = retain((PRICE.all_room_price * (1 - ORDER_DATA.commission_ratio) + PRICE.adult_bed_num * PRICE.extra_adult_bed_rate*PRICE.day + PRICE.kid_bed_num * PRICE.extra_kid_bed_rate*PRICE.day + PRICE.service_price + PRICE.adjustive_price) * ORDER_DATA.tax_ratio);
}


/**
 * 计算两日期时间差
 * @param   interval 计算类型：D是按照天、H是按照小时、M是按照分钟、S是按照秒、T是按照毫秒
 * @param  date1 起始日期  格式为年月格式 为2012-06-20
 * @param  date2 结束日期
 * @return 
 */
/*function getDateDiff(date1, date2) {
    var objInterval = {'D' : 1000 * 60 * 60 * 24, 'H' : 1000 * 60 * 60, 'M' : 1000 * 60, 'S' : 1000, 'T' : 1};
    //interval = interval.toUpperCase();
    var dt1 = Date.parse(date1.replace(/-/g, "/"));
    var dt2 = Date.parse(date2.replace(/-/g, "/"));
    try{
        return ((dt2 - dt1) / objInterval['D']).toFixed(0);//保留两位小数点
    }catch (e){
        return e.message;
    }
}*/
//计算日期相差的天数
function getDateDiff(date1,date2){
    var arr1=date1.split('-');
    var arr2=date2.split('-');
    var d1=new Date(arr1[0],arr1[1]-1,arr1[2]);
    var d2=new Date(arr2[0],arr2[1]-1,arr2[2]);
    return (d2.getTime()-d1.getTime())/(1000*3600*24);
}

//订单关联别墅
function associated_villa(){

    $("#bind_villa").click(function(){
        var id = $("input[name='id']").val();
        var username = $("#i_user_name").val();
        var useremail = $("#i_user_email").val();
        var userphone = $("#i_user_phone").val();
        var passport = $("#i_user_passport").val();
        var idcard = $("#i_user_card_id").val();
        var wechat = $("#i_user_wechat").val();
        var start = $("#i_start_date").val();
        var end = $("#i_end_date").val();
        var state = $("#i_state").val();
        var location = $("#i_user_city").val();
        var departure = $("#i_depart_city").val();
        var memo = $("#memo_message").val();
        var source = $("#i_inquire_source").val();
        var usersex = $('input[name="inquire[user_sex]"]:checked').val()
        if(!id){
            alert('未获取到订单ID');
            return false;
        }
        var pid = $("#p_id").val();
        if(!pid){
            alert('未获取到别墅ID');
            return false;
        }
        var url = "/manager/inquire/bindVilla";
        url += "/id/" +id+ "/pid/"+pid;
        /*url += "/username/"+username;
         url += "/useremail/"+useremail;
         url += "/userphone/"+userphone;
         url += "/passport/"+passport;
         url += "/idcard/"+idcard;
         url += "/wechat/"+wechat;
         url += "/start/"+start;
         url += "/end/"+end;
         url += "/state/"+state;
         url += "/location/"+location;
         url += "/departure/"+departure;*/

        $.get(url, function(data){
            if(data == 1){
                var updateurl = "/manager/inquire/update/id/"+id+"/pid/"+pid;
                $.post(updateurl, {username:username,useremail:useremail,userphone:userphone,passport:passport,idcard:idcard,wechat:wechat,start:start,end:end,state:state,location:location,departure:departure,memo:memo, source:source,usersex:usersex}, function(r){});
                alert('关联成功');
                window.location.href= "/manager/inquire/addTwo/id/"+id+ "/pid/"+pid;
            }else{
                alert("关联失败");
                return false;
            }
        });
    });

   
    $("#p_id").change(function(){
        var pid = $("#p_id").val();
        isAdd = false;
        if(parseInt(pid) != pid){
            alert('请输入合法的产品ID');
            $("#p_id").val('');
            $("#p_name").val('');
            return false;
        }
        $.get('/manager/inquire/getProductName', {id:pid}, function(data){
            if( data == 0){
                alert("你输入的产品不存在，请重新输入");
                $("#p_id").val('');
                $("#p_name").val('');
            }else{
                isAdd = true;
                $("#p_name").val(data);
            }
        });
    });
}

//提交时进行信息验证
function checkSubmit(){

    var mailReg = /^([a-zA-Z0-9]+[-_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9\-]+\.[a-zA-Z]{2,3}$/;
    if($('#i_state').val().trim() == -1){
        return true;
    }
    if($('#i_user_name').val().trim() == '' ){
        alert('请填写用户名称');
        return false;
    }

    if($('#i_user_email').val().trim() != '' && !mailReg.test($("#i_user_email").val())){
        alert("请正确填写邮箱格式");
        return false;
    }
    if($('#i_user_phone').val().trim() == '' ){
        alert('请填写用户电话');
        return false;
    }

    if($('#i_state').val().trim() == ''){
        alert('请选择当前订单的状态');
        return false;
    }

    if(typeof $("#villa_id").val() == 'undefined'){
        alert('请先关联别墅');
        return false;
    }

    return true;
}

//自动选中
function auto_select(){
    $("#tax_formula").val($("#tax_formula").attr("data-initselet"));
    $("#chosen-select").val($("#chosen-select").attr("data-initselect"));
    $("#adult_num").val($("#adult_num").attr("data_initselect"));
    $("#kids_num").val($("#kids_num").attr("data_initselect"));
    $("#invoice_pro").val($("#invoice_pro").attr("data_initselect"));
}