var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
initdatepicker_cn();
$('#discount_begin').datepicker({
    inline: true,
    dateFormat: 'yy-mm-dd',
    minDate:new  Date(),
    onClose: function( selectedDate ) {
        $('#discount_end').datepicker("option","minDate",selectedDate);
    }
});
$('#discount_end').datepicker({
    inline: true,
    dateFormat: 'yy-mm-dd',
    minDate:new  Date(),
    onClose: function( selectedDate ) {
        $('#discount_begin').datepicker("option","maxDate",selectedDate);
    }
});
//验证日期有效性
var Date_validate =  {
    villa_id :0,
    begin_date:new Date(),
    end_date:null,
    _type:0,
    update:0, //是否为编辑模式  1=>是  0=>不是
    p_id:0, //修改id
    validate_is_insert:function(list){
        var load = layer.load();
        isDiscountDate._True =0;
        isDiscountDate._DateList=list;
        if(!isDiscountDate.getDateList(this.begin_date,this.end_date)){
            var _original_discount_ratio = $("#original_discount_ratio").val();
            if(_original_discount_ratio>1 || _original_discount_ratio<=0 || _original_discount_ratio==''){
                layer.close(load);
                layer.tips('请填写别墅方给定折扣信息','#original_discount_ratio');
                return false;
            }
            if(this._type==1){
                var _discount_ratio = $("#discount_ratio").val();
                if(_discount_ratio>=1 || _discount_ratio<=0 || _discount_ratio==''){
                    layer.close(load);
                    layer.tips('请填写折扣信息','#discount_ratio');
                    return false;
                }
                var _data = {begin_date:this.begin_date,end_date:this.end_date,discount_ratio:_discount_ratio,original_discount_ratio:_original_discount_ratio};
            }else{
                if($("#discount_info").val()==''){
                    layer.close(load);
                    layer.tips('请填写折扣信息','#discount_info');
                    return false;
                }
                var _data = {begin_date:this.begin_date,end_date:this.end_date,discount_info:$("#discount_info").val(),original_discount_ratio:_original_discount_ratio};
            }
            var _action_insert_url ='/manager/product/getDatevalidate?id='+this.villa_id+'&type='+this._type;
            var _action_update_ur = '/manager/product/Discountedit?v_id='+this.villa_id+'&type='+this._type+'&d_id='+this.p_id;
            $.post((this.update)?_action_update_ur:_action_insert_url,_data,function(d){
                layer.close(load);
                if(d.code==1){
                    layer.msg(d.msg);
                    parent.$("#discount_list").trigger('click');
                }else{
                    layer.msg(d.msg);
                }
            },'json')
        }else{
            layer.close(load);
            layer.tips('您选择时间段包含其他优惠，请联系相关人员核实','#discount_begin');
            return false;
        }
    }
};
//获取时间交叉
//    isOrderDate._True =0;
//    isOrderDate._DateList =orderDays;
//    isOrderDate.getDateList(_start,_end);
var isDiscountDate  = {
    _True:0,
    _DateList:[],
    getDate:function(str){
        var tempDate=new Date();
        var list=str.split("-");
        tempDate.setFullYear(list[0]);
        tempDate.setMonth(list[1]-1);
        tempDate.setDate(list[2]);
        return tempDate;
    },
    getTrue:function(Date_str){
        for(i in this._DateList){
            if(this._DateList[i].toString() == Date_str.toString()){
                return 1;
            }
        }
    },
    getDateList:function(start,end){
        var start=this.getDate(start);
        var end=this.getDate(end);
        if(start>end){
            var _temp = start;
            start = end;
            end = _temp;
        }
        start.setDate(start.getDate());
        while(!(start.getFullYear()==end.getFullYear()&&start.getMonth()==end.getMonth()&&start.getDate()==end.getDate())){
            if(this.getTrue([(start.getMonth()+1),start.getDate(),start.getFullYear()])){
                this._True =1;
                break;
            }
            start.setDate(start.getDate()+1);
        }
        return this._True;
    }
};