<div id="main">
    <?php echo $this->renderPartial('../_left');?>
    <div class="main-middle">
        <div class="main-middleBox">
            <div class="mainCont">
                <form id="poster-form" action="#" method="post" class="task-add" enctype ="multipart/form-data">
                    <div class="mainCont-operate-btns">
                        <p>
                            <button type="submit" value="完成" class="btn-submit btn-operate">完成</button>
                            <button type="reset" value="取消" class="btn-cancel btn-operate" onclick="history.go(-1);">取消</button>
                        </p>
                    </div>
                    <div class="mainCont-content">
                        <div class="task-info-wrap">
                            <div class="task-info-top">
                                <h2 class="task-add-tips"><?php if('add' == $this->action->id) echo "添加"; else echo "编辑";?>图片</h2>
                            </div>
                            <input type="hidden"  name="userid"  value="<?php echo Yii::app()->admin->id ?>">
                            <table>
                                <tr>
                                    <td class="tar">
                                        <label for="task-item-theme"><span style="color: red;">*</span> 图片名：</label>
                                    </td>
                                    <td colspan="5">
                                        <input id="task-item-theme" type="text" name="name" value="<?php echo $model->name; ?>">
                                    </td>
                                </tr>
                                <tbody class="img-box">
                                    <tr>
                                        <td class="tar">
                                            <label for="task-item-theme"><span style="color: red;">*</span> 描述：</label>
                                        </td>
                                        <td colspan="5">
                                            <input id="task-item-theme" type="text" name="description[]" value="<?php echo $model->description; ?>">
                                        </td>
                                    </tr>                                
                                    <tr>
                                        <td class="tar">
                                            <label for="task-item-theme"><span style="color: red;">*</span> 封面图：</label>
                                        </td>
                                        <td>
                                            <input  name="image[]"  id="task-item-theme" type="file">
                                        </td>
                                    </tr>
                                </tbody>
                                <tr>
                                <td></td>
                                <?php if($model->path):?>
                                <td colspan="4">
                                    <img width="50%" src="<?php echo "/".Collect::IMAGE_DIR."/".$model->image; ?>"></img>
                                </td>
                                <?php endif;?>
                                
                                 <tr>
                                    <td class="tar"><label for="task-item-theme"><span style="color: red;">*</span> 类型：</label></td>
                                    <td>
                                        <select name="type">
                                            <option value="0">选择类型</option>
                                            <option value="1" <?php if(1 == $model->type) echo 'selected'; ?>>illustration</option>
                                            <option value="2" <?php if(2 == $model->type) echo 'selected'; ?>>icon</option>
                                            <option value="3" <?php if(3 == $model->type) echo 'selected'; ?>>photography</option>
                                            <option value="4" <?php if(4 == $model->type) echo 'selected'; ?>>template</option>
                                            <option value="5" <?php if(5 == $model->type) echo 'selected'; ?>>projects</option>
                                            <option value="6" <?php if(6 == $model->type) echo 'selected'; ?>>other</option>
                                            
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tar"><label for="task-item-theme"><span style="color: red;">*</span> 状态：</label></td>
                                    <td>
                                        <select name="status">
                                            <option value="0">选择状态</option>
                                            <option value="-1" <?php if(1 == $model->status) echo 'selected'; ?>>拒绝</option>
                                            <option value="1" <?php if(1 == $model->status) echo 'selected'; ?>>发布</option>
                                            <option value="2" <?php if(2 == $model->status) echo 'selected'; ?>>审核</option>
                                        </select>
                                    </td>
                                </tr>                                
                            </table>
                            <button type="button" style="display: block;margin: 0 auto" class="addImgHtml">增加图片</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(function () {
    $('.addImgHtml').click(function () {
        var $clone = $('.img-box').clone();
        $clone.removeClass('img-box')
        $clone.find('input').val('')
        $('table').append($clone)
    })
    $(".btn-submit").bind("click", function(){
        
        if ($("input[name='Poster[title]']").val() == "") {
        	alert("请填写标题");
        	return false;
        }
        
       
        if ($("select[name='Poster[type]']").val() == 0) {
        	alert("请选择海报用途");
        	return false;
        }
        
        if ($("select[name='Poster[state]']").val() == 0) {
        	alert("请选择海报状态");
        	return false;
        }
        
        $("#poster-form").submit();
    });    
});
</script>