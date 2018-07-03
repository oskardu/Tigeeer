<div id="main">
    <?php $this->renderPartial('../_left'); ?>
    <div class="main-middle">
        <div class="main-middleBox">
	        <div class="mainCont-tips">
	            <a href="<?php echo $this->createUrl('/managers/collect/index', array('type' => 1)); ?>" <?php if($this->getQuery('type') == 1 || !$this->getQuery('type')) echo 'class="cur"'; ?>><span>首页轮播</span></a>
	            <a href="<?php echo $this->createUrl('/managers/collect/index', array('type' => 2)); ?>" <?php if($this->getQuery('type') == 2) echo 'class="cur"'; ?>><span>实力展示</span></a>
	            
	        </div>
            <div class="mainCont">
                <div class="mainCont-operate-btns">
                
                <p><a href="<?php echo $this->createUrl('/managers/collect/add'); ?>" class="btn-add btn-operate">添加</a></p>
                
                </div>
                <div class="mainCont-content">
                    <div class="task-tables-wrap">
                        <table class="task-today task-tables">
                            <thead>
                                <tr>
                                	<th class="task-tables-th1"></th>
                                    <th class="task-tables-th2">id</th>
                                    <th class="task-tables-th5">图片名</th>
                                    <th class="task-tables-th5">地址</th>
                                    <th class="task-tables-th5">类型</th>
                                    <th class="task-tables-th3">状态</th>
                                    <th class="task-tables-th5">添加时间</th>
                                    <th class="task-tables-th6">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($list as $p):?>
                                <tr>
                                	<td class="check-box tac">&nbsp;</td>
                                    <td class="tac"><?php echo $p['id'];?></td>
                                    <td class="tac"><?php echo $p['name']; ?></td>
                                    <td class="tac"><?php echo $p['image']; ?></td>
                                    <td>
                                        <?php 
                                            if(1 == $p['type']) echo 'illustration';
                                            elseif(2 == $p['type']) echo 'icon';
                                            elseif(3 == $p['type']) echo 'photography';
                                            elseif(4 == $p['type']) echo 'templates';
                                            elseif(5 == $p['type']) echo 'projects';
                                        ?>
                                     </td>
                                    <td><?php echo $p['status']; ?></td>
                                    <td><?php echo $p['date']; ?></td>
                                    <td class="c3c7adc">
                                        <a href="<?php echo $this->createUrl("/managers/collect/edit", array('id' => $p['id'])); ?>">编辑</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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