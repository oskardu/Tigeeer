<?php
class OrderController extends ManagerController{
   
    public function actionIndex(){
    	$page = (int) $this->getQuery('page', 1);
        if($page < 1){
            $page = 1;
        }
        $pageSize = 15;
        $offset = ($page - 1) * $pageSize;
        $param = array(
            'offset'=>$offset,
            'pagesize'=>$pageSize,
            'order_id'=>$this->getQuery('order_id'),
            'sender'=>$this->getQuery('username'),
            'send_phone'=>$this->getQuery('userphone'),
        );
        $total = Order::getAmountByCondition($param);
        $list = Order::getListByCondition($param);
        //print_r($list);die;
        $pagination = CommonHelper::pagination(null, $page, ceil($total/$pageSize), $total);
        //print_r($pagination);die;
        $this->render('index',array('list'=>$list,'pagination'=>$pagination));
    }
    public function actionAdd(){
        $model=new Order;
        if(isset($_POST['Order'])){
            $model->attributes = $_POST['Order'];
            $send_destination = $_POST['send_province'].'#'.$_POST['send_city'].'#'.$_POST['send_county'];
            $accept_destination = $_POST['accept_province'].'#'.$_POST['accept_city'].'#'.$_POST['accept_county'];
            $model->send_destination = $send_destination;
            $model->accept_destination =$accept_destination;
            $model->create_time = date("Y-m-d H:i:s",time());
            $model->create_name = Yii::app()->admin->name;
            if($model->save(false)){
                $this->redirect(array('index'));
            }
        }
        $this->render('form',array('model'=>$model));
    }
    public function actionEdit($id){
        $model = Order::model()->findByPk($id);
        if (isset($_POST['Order'])) {
            $model->attributes = $_POST['Order'];
            $send_destination = $_POST['send_province'].'#'.$_POST['send_city'].'#'.$_POST['send_county'];
            $accept_destination = $_POST['accept_province'].'#'.$_POST['accept_city'].'#'.$_POST['accept_county'];
            $model->send_destination = $send_destination;
            $model->accept_destination =$accept_destination;
            $model->create_name = Yii::app()->admin->name;
            if($model->save(false)){
                $this->redirect(array('index'));
            }
        }
        $this->render('form',array('model'=>$model));
    }
    /**
     * 删除订单
     * @param int $id
     */
    public function actionDelete($id){
        if (!empty($id)) {
            Order::model()->deleteByPk($id);
            $this->redirect('/managers/order/index');
        }
    }
    /**
     * 删除订单状态
     * @param int $id
     */
    public function actionTrackDelete($id,$order_id){
        if (!empty($id)) {
            OrderTrack::model()->deleteByPk($id);
            $this->redirect('/managers/order/OrderTrack/id/'.$order_id);
        }
    }
    public function actionOrderTrack($id){
        
        $model = OrderTrack::model()->findAll('order_id=:id order by track_time asc',array(':id'=>$id));
        if(isset($_POST['Order'])){
            $track = new OrderTrack;
            $track->attributes = $_POST['Order'];
            $track->create_name = Yii::app()->admin->name;
            if ($track->save(false)) {
                $this->redirect(array('ordertrack','id'=>$track->order_id));
            }
        }
        $this->render('track',array('model'=>$model,'id'=>$id));
    }
}
