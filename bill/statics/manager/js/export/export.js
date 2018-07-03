/**
 * Created by yu on 9/30/14.
 */
$(function(){
    $('.dateSelect').datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
    $("#villape").click(function(){

        location.href = "/manager/export/dispricemes";
    });
});
