$(document).ready(function(){
    $("#nation").val($("#nation").attr("data-select"));
    $("#nation").change(nationCityChange);
    nationCityChange();
    $("#check").click(function(){
        var data = {
            order_number:$("#ONmber").val(),
            nation:$("#nation").val(),
            city:$("#city").val(),
            stime:$("#stime").val(),
            etime:$("#etime").val()
        };
        var url = "/manager/pricedetail?";
        for(var v in data){
            url = url+v+'='+data[v]+'&';
        }
        location.href = url;
    })
});


function nationCityChange(){
    var val = $("#nation").val();
    if(val != 0){
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/manager/pricedetail/getcitybynation?nation='+val,
            success:function(data){
                var str = '<option value="0">城市</option>';
                for(var v in data){
                    str += "<option value='"+v+"'>"+data[v]+"</option>"
                }
                $("#city").html(str);
                $("#city").val($("#city").attr("data-select"));
            }
        })
    }else{
        $("#city").html('<option value="0">城市</option>');
        $("#city").val(0);
    }
}