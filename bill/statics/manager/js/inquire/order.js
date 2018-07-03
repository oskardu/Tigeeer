var extra_bed_fee = 0;
var insurance_rate = 0;
//order
$(function(){

    initDataSelect();

    minDate = 0;
    $(".dateSelect").change(cost);

    //计入机场接送机费用
    $("#airport_transfer_rate").change(function(){
        s_airport_transfer_rate_val = $("#airport_transfer_rate").val();
        s_air_rate = parseFloat(s_airport_transfer_rate_val);
        if(isNaN(s_air_rate)){
            alert('请输入正确的价格数字');
            $("#airport_transfer_rate").val($("#s_air_price").html());
            return false; 
        }
        $("#airport_transfer_rate").html(s_air_rate);
        getAddBed();
    });
    //计入机票费用，币种为RMB
    $("#flights").change(function(){
        s_flights_rate_val = $("#flights").val();
        s_flights_rate = parseFloat(s_flights_rate_val);
        if(isNaN(s_flights_rate)){
            alert('请输入正确的价格数字');
            $("#flights").val($("#s_flights_rate").html());
            return false; 
        }
        $("#s_flights_rate").html(s_flights_rate);
        getAddBed();
    });
    //计入抵用券费用，币种为RMB
    $("#coupon_rate_input").change(function(){
        if($(this).val() == ''){
            $(this).val(0)
        }
        getAddBed();
    });
    //计入保险费用，币种为RMB
    $("#insurance_rate").change(function(){
        insurance_rate = $("#insurance_rate").val();
        insurance_rate = parseFloat(insurance_rate);
        if(isNaN(insurance_rate)){
            alert('请输入正确的价格数字');
            $("#s_insurance_rate").text(0);
            return false; 
        }
        $("#s_insurance_rate").text(insurance_rate);
        getAddBed();
    });
    $("#add_adult_bed_num,#add_kid_bed_num").change(function(){
        add_bed();
    });
    add_bed();
    function add_bed(){
        var start_time = $("#start_date").val();
        var end_time = $("#end_date").val();
        var days = getDateDiff(start_time,end_time);
                
        var add_adult_bed_fee = parseInt($("#add_adult_bed_num").val()) * parseInt($("#add_adult_bed_num").attr('data_price'));
        var add_kid_bed_fee = parseInt($("#add_kid_bed_num").val()) * parseInt($("#add_kid_bed_num").attr('data_price'));
       
        extra_bed_fee = (add_adult_bed_fee + add_kid_bed_fee)*days;
        getAddBed();
    }
    //计入其他费用
    $("#other_rate").change(function(){
        s_other_rate_val = $("#other_rate").val();
        s_other_rate = parseFloat(s_other_rate_val);
        if(isNaN(s_other_rate)){
            alert('请输入正确的价格数字');
            $("#other_rate").val($("#s_other_rate").html());
            return false;
        }
        $("#s_other_rate").html(s_other_rate);
        getAddBed();
    });      
    $("#tax_formula").bind('change', function(){
        getAddBed();
    });
    $('#chosen-select').change(cost);
    $('#adjustive_day').change(cost);
    $(".service_item").bind('click', function(){
        service_item();
    });

//    getAddBed();
    
    initdatepicker_cn();
    $('.dateSelect').datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
    
  
    //订单关联别墅
    $("#bind_villa").click(function(){
    	var id = $("#iid").val();
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
        		$.post(updateurl, {username:username,useremail:useremail,userphone:userphone,passport:passport,idcard:idcard,wechat:wechat,start:start,end:end,state:state,location:location,departure:departure,memo:memo}, function(r){});
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
    $("#chosen-select").change(function(){
    	alert($(this).find("option:selected").attr('data-flag'));
    	$("#bedroom_flag").val($(this).find("option:selected").attr('data-flag'));
    });
});

function getAllRate_1(){
    var stay_price = s_room_rate + extra_bed_fee + parseFloat(service_item_tax) + s_other_rate;
    $("#s_stay_rate").text(stay_price+s_currency+", "+parseFloat(stay_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var service_price = parseFloat(stay_price * service_fee);
    $("#s_service_rate").text(service_price.toFixed(2)+s_currency+", "+parseFloat(service_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var government_price = parseFloat(stay_price * tax_ratio);
    //机票价格
    var air_ticket_price = parseFloat($("#flights").val());
    //保险价格
    var insurance_price = parseFloat($("#insurance_rate").val());
    var prices = parseFloat(stay_price*s_exchange_rate + government_price*s_exchange_rate + service_price*s_exchange_rate + air_ticket_price + insurance_price + s_air_rate*s_exchange_rate);
    var invoice_rate = getinvoice();
    var invoice_price = parseFloat(prices*invoice_rate);
    $("#invoice_rate_input").val(invoice_price);
    $("#invoice_rate").text(invoice_price);

    $("#coupon_rate").text(parseFloat($("#coupon_rate_input").val()));
    var all_price = Math.ceil(invoice_price + prices - parseFloat($("#coupon_rate_input").val()));
    $("#all_price").text(all_price);
    $("#main_price").text(parseFloat(stay_price + government_price + service_price + s_air_rate).toFixed(2) + s_currency+", "+Math.ceil((stay_price + government_price + service_price + s_air_rate)*s_exchange_rate)+" 人民币");
    $("#extra_price").text(air_ticket_price + insurance_price);
    var commission_rate = parseFloat(s_room_rate * commission_ratio).toFixed(2);
    $("#s_commission_price").html(commission_rate+s_currency+", "+parseFloat(commission_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_room_rate").html(s_room_rate+s_currency+", "+parseFloat(s_room_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_location_rate").html(government_price.toFixed(2)+s_currency+", "+parseFloat(government_price*s_exchange_rate).toFixed(2)+"人民币");
    
    paraString = "add_bed_rate:"+extra_bed_fee+"|service_rate:"+service_price+"|location_rate:"+government_price+"|all_rate:"+all_price+"|room_rate:"+s_room_rate+"|other_rate:"+s_other_rate+"|service_item_tax:"+service_item_tax+"|service_item_taxNo:"+service_item_taxNo;
    $("#paraString").val(paraString);
}
function getAllRate_2(){

    var stay_price = s_room_rate + extra_bed_fee  + parseFloat(service_item_tax) + s_other_rate;
    $("#s_stay_rate").text(stay_price+s_currency+", "+parseFloat(stay_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var service_price = parseFloat(stay_price * service_fee);
    $("#s_service_rate").text(service_price.toFixed(2)+s_currency+", "+parseFloat(service_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var government_price = parseFloat((stay_price + service_price) * tax_ratio);
    var air_ticket_price = parseFloat($("#flights").val());
    var insurance_price = parseFloat($("#insurance_rate").val());
    var prices = parseFloat(stay_price*s_exchange_rate + government_price*s_exchange_rate + service_price*s_exchange_rate + air_ticket_price + insurance_price + s_air_rate*s_exchange_rate);
    var invoice_rate = getinvoice();
    var invoice_price = parseFloat(prices*invoice_rate);
    $("#invoice_rate_input").val(invoice_price);
    $("#invoice_rate").text(invoice_price);

    $("#coupon_rate").text(parseFloat($("#coupon_rate_input").val()));
    var all_price = Math.ceil(invoice_price + prices - parseInt($("#coupon_rate_input").val()));
    $("#all_price").text(all_price);
    $("#main_price").text(parseFloat(stay_price + government_price + service_price + s_air_rate).toFixed(2)+ s_currency+", "+ Math.ceil((stay_price + government_price + service_price + s_air_rate)*s_exchange_rate)+" 人民币");
    $("#extra_price").text(air_ticket_price + insurance_price);
    
    var commission_rate = parseFloat(s_room_rate * commission_ratio).toFixed(2);
    $("#s_commission_price").html(commission_rate+s_currency+", "+parseFloat(commission_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_room_rate").text(s_room_rate+s_currency+", "+parseFloat(s_room_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_location_rate").text(government_price.toFixed(2)+s_currency+", "+parseFloat(government_price*s_exchange_rate).toFixed(2)+"人民币");
    paraString = "add_bed_rate:"+extra_bed_fee+"|service_rate:"+service_price+"|location_rate:"+government_price+"|all_rate:"+all_price+"|room_rate:"+s_room_rate+"|other_rate:"+s_other_rate+"|service_item_tax:"+service_item_tax+"|service_item_taxNo:"+service_item_taxNo;
    $("#paraString").val(paraString);
}
function getAllRate_3(){
    
    var stay_price = s_room_rate + extra_bed_fee + parseFloat(service_item_tax) + s_other_rate;
    $("#s_stay_rate").text(stay_price+s_currency+", "+parseFloat(stay_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var service_price = parseFloat((stay_price - s_room_rate * commission_ratio) * service_fee);
    $("#s_service_rate").text(service_price.toFixed(2)+s_currency+", "+parseFloat(service_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var government_price = parseFloat((stay_price - s_room_rate * commission_ratio) * tax_ratio);
    var air_ticket_price = parseFloat($("#flights").val());
    var insurance_price = parseFloat($("#insurance_rate").val());
    var prices = parseFloat(stay_price*s_exchange_rate + government_price*s_exchange_rate + service_price*s_exchange_rate + air_ticket_price + insurance_price + s_air_rate*s_exchange_rate);
    var invoice_rate = getinvoice();
    var invoice_price = parseFloat(prices*invoice_rate);
    $("#invoice_rate_input").val(invoice_price);
    $("#invoice_rate").text(invoice_price);

    $("#coupon_rate").text(parseFloat($("#coupon_rate_input").val()));
    var all_price = Math.ceil(invoice_price + prices - parseFloat($("#coupon_rate_input").val()));
    $("#all_price").text(all_price);
    $("#main_price").text(parseFloat(stay_price + government_price + service_price + s_air_rate).toFixed(2)+ s_currency+", "+Math.ceil((stay_price + government_price + service_price + s_air_rate)*s_exchange_rate)+" 人民币");
    $("#extra_price").text(air_ticket_price + insurance_price);
    
    var commission_rate = parseFloat(s_room_rate * commission_ratio).toFixed(2);
    $("#s_commission_price").html(commission_rate+s_currency+", "+parseFloat(commission_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_room_rate").html(s_room_rate+s_currency+", "+parseFloat(s_room_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_location_rate").html(government_price.toFixed(2)+s_currency+", "+parseFloat(government_price*s_exchange_rate).toFixed(2)+"人民币");
    
    paraString = "add_bed_rate:"+extra_bed_fee+"|service_rate:"+service_price+"|location_rate:"+government_price+"|all_rate:"+all_price+"|room_rate:"+s_room_rate+"|other_rate:"+s_other_rate+"|service_item_tax:"+service_item_tax+"|service_item_taxNo:"+service_item_taxNo;
    $("#paraString").val(paraString);
}
function getAllRate_4(){
    var stay_price = s_room_rate + extra_bed_fee  + parseFloat(service_item_tax) + s_other_rate;
    $("#s_stay_rate").text(stay_price+s_currency+", "+parseFloat(stay_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var service_price = parseFloat((stay_price - s_room_rate * commission_ratio) * service_fee);
    $("#s_service_rate").text(service_price.toFixed(2)+s_currency+", "+parseFloat(service_price*s_exchange_rate).toFixed(2)+"人民币");
    
    var government_price = parseFloat((stay_price - s_room_rate * commission_ratio + service_price) * tax_ratio);
    var air_ticket_price = parseFloat($("#flights").val());
    var insurance_price = parseFloat($("#insurance_rate").val());
    var prices = parseFloat(stay_price*s_exchange_rate + government_price*s_exchange_rate + service_price*s_exchange_rate + air_ticket_price + insurance_price + s_air_rate*s_exchange_rate);
    var invoice_rate = getinvoice();
    var invoice_price = parseFloat(prices*invoice_rate);
    $("#invoice_rate_input").val(invoice_price);
    $("#invoice_rate").text(invoice_price);
    
    $("#coupon_rate").text(parseFloat($("#coupon_rate_input").val()));
    var all_price = Math.ceil(invoice_price + prices- parseFloat($("#coupon_rate_input").val()));
    $("#all_price").text(all_price);
    $("#main_price").text(parseFloat(stay_price + government_price + service_price + s_air_rate).toFixed(2)+ s_currency+", "+Math.ceil((stay_price + government_price + service_price + s_air_rate)*s_exchange_rate)+" 人民币");
    $("#extra_price").text(air_ticket_price + insurance_price);
    
    var commission_rate = parseFloat(s_room_rate * commission_ratio).toFixed(2);
    $("#s_commission_price").html(commission_rate+s_currency+", "+parseFloat(commission_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_room_rate").html(s_room_rate+s_currency+", "+parseFloat(s_room_rate*s_exchange_rate).toFixed(2)+"人民币");
    $("#s_location_rate").html(government_price.toFixed(2)+s_currency+", "+parseFloat(government_price*s_exchange_rate).toFixed(2)+"人民币");
    
    paraString = "add_bed_rate:"+extra_bed_fee+"|service_rate:"+service_price+"|location_rate:"+government_price+"|all_rate:"+all_price+"|room_rate:"+s_room_rate+"|other_rate:"+s_other_rate+"|service_item_tax:"+service_item_tax+"|service_item_taxNo:"+service_item_taxNo;
    $("#paraString").val(paraString);
}

function getinvoice(){
    var type = $("#invoice_pro").val();
    var rate = $("#invoice_pro option[value='"+type+"']").attr('data-rate');
    return rate;
}
var cost = function(){
    if($('#chosen-select').val() == 0){
        //alert('请先选择卧室类型');
        //$('#chosen-select').focus();
        $("#s_room_rate").html(0);
        s_room_rate = 0;
        getAddBed();
        $("#s_room_rate_err").html("错误参数");
        return false;
    }
    var start = $("#start_date").val();
    var end = $("#end_date").val();
    var bedroom = $("#chosen-select").val();
    var bedroom_flag = $("#bedroom_flag").val();
    if(start >= end)
    {
        $("#s_room_rate").html(0);
        s_room_rate = 0;
        getAddBed();
        $("#s_room_rate_err").html("错误参数");
        return false;
    }
    $("#inquireus_start").val(start);
    $("#inquireus_end").val(end);
    $.get('/manager/orders/countcost', {pid:p_id, start:start, end:end, bedroom:bedroom, adjustive_daly:adjustive_day, bedroom_flag:bedroom_flag}, function(data){
        if( data < 0){
            $("#s_room_rate").html(0);
            s_room_rate = 0;
            getAddBed();                  
            $("#s_room_rate_err").html("错误参数");
        }else{
            $("#s_room_rate").html(data+extra_bed_fee);
            $("#s_start").html(start);
            $("#s_end").html(end);
            $("#s_room").html(bedroom);
            s_room_rate = parseFloat(data);
            getAddBed();
            $("#s_room_rate_err").html("");
        }
    });
}
function getDateDiff(date1,date2){
    var arr1=date1.split('-');
    var arr2=date2.split('-');
    var d1=new Date(arr1[0],arr1[1],arr1[2]);
    var d2=new Date(arr2[0],arr2[1],arr2[2]);
    return (d2.getTime()-d1.getTime())/(1000*3600*24);
}

function service_item(){
    var r=document.getElementsByName("service_item[]");
    service_item_tax = 0;
    service_item_taxNo = 0;
    for(var i=0;i<r.length;i++){
         if(r[i].checked){
         fee_type = $("#charge_fee_"+r[i].value).val();
         f_arr = fee_type.split("_")
         if(f_arr[1] == 1){
             //含税
             service_item_tax +=parseFloat(f_arr[0]);
         }else{
             service_item_taxNo +=parseFloat(f_arr[0]);
         }
       }
    }
    
    var start_time = $("#start_date").val();
    var end_time = $("#end_date").val();
    var days = getDateDiff(start_time,end_time);
    var person_num = parseInt($("select[name='Product[total_number]']").val()) + parseInt($("select[name='Product[kid_number]']").val());
        
    service_item_tax = service_item_tax * days * person_num;
    $("#s_breakfast_price").text(service_item_tax);
    $("#s_service_item_taxNo").text(service_item_taxNo);
    getAddBed();
}
function getAddBed(){

    $("#s_add_bed_price").text(extra_bed_fee+s_currency+", "+Math.ceil(extra_bed_fee*s_exchange_rate)+"人民币");
    $("#s_air_price").text(s_air_rate+s_currency+", "+Math.ceil(s_air_rate*s_exchange_rate)+"人民币");
    
    var tar_formula = $('#tax_formula').val();
    if(tar_formula == 1){
        getAllRate_1();
    }else if(tar_formula == 2){
        getAllRate_2();
    }else if(tar_formula == 3){
        getAllRate_3();
    }else if(tar_formula == 4){
        getAllRate_4();
    }
    profits();
}

function formatFloat(src){
    src =  parseFloat(src);
    return Math.ceil(src*Math.pow(10, 2))/Math.pow(10, 2);
}
function checkSubmit(){
    
    var mailReg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
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
    if($("#villa_id").val() == ''){
        alert('请先关联别墅');
        return false;
    }
    
    return true;
}

//初始化选择计费方式
function initDataSelect(){
    $("#tax_formula").val($("#tax_formula").attr("data-initselet"));
    $("#chosen-select").val($("#chosen-select").attr("data-initselect"));
    $("#adult_num").val($("#adult_num").attr("data_initselect"));
    $("#kids_num").val($("#kids_num").attr("data_initselect"));
    $("#invoice_pro").val($("#invoice_pro").attr("data_initselect"));
    $("#invoice_pro").change(function(){
        var rate = getinvoice();
        $("#invoice_message").text(rate);
        getAddBed();
    });
    cost();
}

/**
 * 利润总价计算
 */
function profits(){
    $("#sl_p_i").text(Math.ceil(parseFloat($("#main_price").text())*s_exchange_rate));
    $("#sl_p_o").text(Math.ceil(parseFloat($("#main_price").text())*s_exchange_rate) - Math.ceil(parseFloat($("#s_commission_price").text())*s_exchange_rate));
    $("#sl_p_g").text(Math.ceil(parseFloat($("#s_commission_price").text())*s_exchange_rate));
    $("#sl_f_i").text(parseFloat($("#s_flights_rate").text()));
    $("#sl_f_g").text(parseFloat($("#s_flights_rate").text()) - parseInt($("#sl_f_o").val()));
    $("#sl_i_i").text(parseFloat($("#insurance_rate").val()));
    $("#sl_i_g").text(parseFloat($("#insurance_rate").val()) - parseInt($("#sl_i_o").val()));
    $("#sl_t_i").text(parseFloat($("#invoice_rate_input").val()));
    $("#sl_t_o").text(parseFloat($("#invoice_rate_input").val()));
    $("#sl_t_g").text(0);
    $("#sl_swap_diff").text(parseFloat(($("#sl_swap_ratio").val()-s_exchange_rate)*$("#sl_p_o").val()));
    $("#sl_preferential_o").text($("#coupon_rate_input").val());
    var all_profits = parseFloat($("#sl_p_g").text()) -parseFloat($("#sl_swap_diff").val()) + parseFloat($("#sl_f_g").text()) + parseFloat($("#sl_i_g").text()) + parseFloat($("#sl_t_g").text()) - (parseFloat($("#sl_pay_o").val()) + parseFloat($("#sl_remit_o").val()) + parseFloat($("#sl_preferential_o").text()));
    $("#gross").text(all_profits);
}
$("#sl_f_o,#sl_i_o,#sl_pay_o,#sl_remit_o,#sl_swap_ratio").change(profits);