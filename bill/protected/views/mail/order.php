<pre>
    寄件用户：<?php echo $model->sender;?><br/>
    手机号：<?php echo $model->send_phone; ?> <br/>
    货物名称：<?php echo $model->goods_name;?> <br/>
    
    请立即跟进：<?php echo 'http://112.74.126.251/managers/order/edit/id/'.$model->id.''; ?> <br/>
    
提交时间：<?php echo date("Y-m-d H:i:s");?><br/>
</pre>