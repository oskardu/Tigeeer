<?php


class IndexController extends Controller
{
	
	public function actionSearch()
	{

        $p = $_POST['p'];
        $order_id=$this->getDecode($p);
        $user = Order::model()->find('order_id=:id',array(':id'=>$order_id->order_id));
        $order_info =OrderTrack::model()->findAll('order_id=:id order by track_time asc',array(':id'=>$order_id->order_id));
        $order = array();
        foreach ($order_info as $v) {
        	$order[]=$v->attributes;
        }
        $data = array('user'=>$user->attributes,'order'=>$order);
        
        $data=$this->getPrivatePara($data);
        echo $data;
	}
	public function actionOrder()
	{
        $p = $_POST['p'];
        $order=$this->getDecode($p);
        $model=new Order;
        //echo $this->getPrivatePara($order);die;
        if(!empty($order)){
            $model->attributes = $order;
            $model->pick_up = $order->pick_up;
            $model->sender = $order->sender;
            $model->send_phone = $order->send_phone;
            $model->send_destination = $order->send_destination;
            $model->send_address = $order->send_address;
            $model->accept_name = $order->accept_name;
            $model->accept_phone = $order->accept_phone;
            $model->accept_destination = $order->accept_destination;
            $model->accept_address = $order->accept_address;
            $model->goods_name = $order->goods_name;
            $model->goods_pay_weight = $order->goods_pay_weight;
            $model->goods_weight = $order->goods_weight;
            $model->goods_volume = $order->goods_volume;
            $model->goods_num = $order->goods_num;
            $model->receipt_order = $order->receipt_order;
            $model->receipt_type = $order->receipt_type;
            $model->transport_type = $order->transport_type;
            $model->pay_service = $order->pay_service;
            $model->delivery_type = $order->delivery_type;
            $model->note = $order->note;
            $model->create_time = date("Y-m-d H:i:s",time());
            
            if($model->save(false)){
                $data = array();
                $data['address'] = 'service@hongjiawuliu.com';
                $data['subject'] = "Hong jia wu liu - 订单通知";
                $data['body'] = $this->renderPartial('application.views.mail.order', array('model'=>$model), true);
                MailHelper::send($data);
                echo $this->getPrivatePara(array('code'=>1));
            }else{
            	echo $this->getPrivatePara(array('code'=>2));
            }
             
        }
	}
	public function actionBanner(){
		$data = Poster::model()->findAll('state=:state AND type=:type',array(':state'=>1,'type'=>1));
		$banner = array();
		
        foreach ($data as $v) {
        	$banner[]=$v->attributes;
        }
        echo $this->getPrivatePara($banner);
	}

    public function actionPic(){
        $data = Poster::model()->findAll('state=:state AND type=:type',array(':state'=>1,'type'=>2));
        $banner = array();
        
        foreach ($data as $v) {
            $banner[]=$v->attributes;
        }
        echo $this->getPrivatePara($banner);
    }

}