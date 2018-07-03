var uploadMusic = [];
var cid = $('#cid').val();
$(function(){
    $("div.upMusic").each(function(index){
        uploadMusic.push(createSwfUpload(index + 1,$(this).attr('allowFile')));
    });

});

function createSwfUpload(index,allowFile){
    var upload = new SWFUpload({
        // Backend Settings
        upload_url: "/manager/icon/upload_images",
        post_params: {isajax:"1",cookie:document.cookie},
        // File Upload Settings
        file_size_limit : "50MB",
        file_types : allowFile,
        file_types_description : "All Files",
        post_params : {
               "cid" : cid,
               "listorder":index,
               "id":$("#up_file_id_"+index).val()
        },
        file_upload_limit : "0",
        file_queue_limit : "0",
        // Event Handler Settings (all my handlers are in the Handler.js file)
        file_dialog_start_handler : fileDialogStart,
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_start_handler : uploadStart,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        // Button Settingsimg/fee
        button_image_url : "",
        button_placeholder_id : "music" + index +"UploadPlaceholder",
        button_width: 105,
        button_height: 24,
        button_text : '<span class="redText">上传图片</span>',
        button_text_style : ".redText {text-align:center;}",
        button_window_mode:"TRANSPARENT",
        button_cursor: SWFUpload.CURSOR.HAND,
        // Flash Settings
        flash_url : "/statics/manager/swfup/swfupload.swf",
        custom_settings : {
            progressTarget : "music" + index + "UploadProgress",
            callback : function(file,serverData){
                var tmp = eval("(" + serverData + ")");
//                console.log(serverData);
                if(tmp.code == 200){
                	$("#up_file_id_"+index).parent().hide();
                    $('#preview_'+index).html('<img src="'+tmp.path+tmp.image_name+'">');
                    $("#up_file_id_"+index).val(tmp.pid);
                }else{
                    alert('上传失败');
                }
                
            }
        },

        // Debug Settings
        debug: false
    });

    return upload;
}
