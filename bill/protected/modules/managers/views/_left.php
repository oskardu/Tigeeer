<div class="main-left">
    <ul class="main-left-nav">
    	<!-- <li class="<?php if(strpos(Yii::app()->request->pathInfo,'user')){echo 'cur';}?>">
            <a href="" class=" header-tips-manage">用户管理</a>
        </li> -->
        <li class="<?php if(strpos(Yii::app()->request->pathInfo,'order')){echo 'cur';}?>">
            <a href="/managers/order/index" class=" header-tips-manage">订单管理</a>
        </li>
        <li class="<?php if(strpos(Yii::app()->request->pathInfo,'poster')){echo 'cur';}?>">
            <a href="/managers/collect/index" class=" header-tips-manage">图片管理</a>
        </li>
    </ul>  
</div>