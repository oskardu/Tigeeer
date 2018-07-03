<div id="main">
    <?php echo $this->renderPartial('../_left'); ?>
  <style>
    .rate_span{position: absolute;left: 800px;}
    .rate_table{height: 24px;}
  </style>
    <div class="main-middle">
      <div class="main-middleBox">
        <div class="mainCont">
          <div class="mainCont-operate-btns">
            <p>
                <a href="/managers/order/add" class="btn-add s-btn btn-operate" id="pms-btn">添加</a>
            </p>
          </div>
          <div class="mainCont-content">
              <div class="search">
                <form id="filter" action="" title="搜索">
                    <span class="Ldib">
                        <input type="text" placeholder="订单号" name="order_id"  value="<?php echo $this->getQuery('order_id');?>" autocomplete="off" class="search-input">
                    </span>
                    <span class="Ldib">
                        <input type="text" placeholder="发货人手机号" name="userphone" value="<?php echo $this->getQuery('userphone');?>" autocomplete="off" class="search-input">
                    </span>
                    <span class="Ldib">
                        <input type="text" placeholder="发货人姓名" name="username" value="<?php echo $this->getQuery('username');?>" autocomplete="off" class="search-input">
                    </span>
                    <em class="Ldib"><input type="submit" value="O" class="search-btn icomoon"></em>
                </form>
            </div>
            <div class="task-tables-wrap" style="overflow-X:scroll;">
              <table class="task-today task-tables">
                <thead>
                  <tr>
                    <th width="50px">编码</th>
                    <th width="100px">订单号</th>
                   
                    <th width="100px"> 货物名称</th>
                    <th width="70px">收件人姓名</th>
                    <th width="130px">收件人手机号</th>
                    <th width="150px">下单时间</th>
                    
                    <th width="90px">寄件用户姓名</th>
                    <th width="130px">寄件用户手机号</th>
                    <th width="70px">订单状态</th>

                    
                    <th width="70px">物流追踪</th>
                    <th width="70px">操作</th>
                    <th width="70px">慎重操作</th>
                    <th width="120px">最后操作员工</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($list) foreach ($list as $order) :?>
                  <tr class="table-cont">
                    <td class="tac">
                        <?php echo $order->id;?>
                    </td>
                    <td class="tac">
                        <?php echo $order->order_id;?>
                    </td>
                    <td>
                        <?php echo $order->goods_name;?>
                    </td>
                    <td>
                        <?php echo $order->accept_name;?>
                    </td>
                    <td>
                        <?php echo $order->accept_phone;?>
                    </td>
                    <td><?php echo $order->create_time; ?></td>
                    
                    <td><?php echo $order->sender; ?></td>
                    <td class="tac" >
                       <?php echo $order->send_phone;?>
                    </td>
                    <td>
                        <?php if(1 == $order->state) echo '上门取件'; ?>
                        <?php if(2 == $order->state) echo '协议签署'; ?>
                        <?php if(3  == $order->state) echo '已揽件'; ?>
                        <?php if(4  == $order->state) echo '发车'; ?>
                        <?php if(5 == $order->state) echo '派车'; ?>
                        <?php if(6 == $order->state) echo '已收件'; ?>
                    </td>
                    
                    <td class="c3c7adc"><a href="<?php echo $this->createUrl("/managers/order/ordertrack", array('id' => $order->order_id)); ?>">查看</a></td>
                    <td class="c3c7adc"><a href="<?php echo $this->createUrl("/managers/order/edit", array('id' => $order->id)); ?>">编辑</a></td>
                    <td class="tac"><a href="<?php echo $this->createUrl("/managers/order/delete", array('id' => $order->id)); ?>">删除</a></td>
                    <td class="tac"><?php echo $order->create_name;?></td>
                    
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
            <?php
                if(!empty($pagination)) echo $pagination;
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>


