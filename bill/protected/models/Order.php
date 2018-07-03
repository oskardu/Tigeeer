<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property integer $order_id
 * @property string $logistics_id
 * @property string $logistics_name
 * @property integer $pick_up
 * @property string $sender
 * @property integer $settlement_way
 * @property string $send_destination
 * @property string $send_address
 * @property integer $send_tel
 * @property integer $send_phone
 * @property string $accept_name
 * @property string $accept_destination
 * @property string $accept_address
 * @property integer $accept_tel
 * @property integer $accept_phone
 * @property string $goods_name
 * @property integer $goods_weight
 * @property integer $goods_pay_weight
 * @property integer $goods_num
 * @property integer $goods_volume
 * @property integer $insured
 * @property integer $receipt_order
 * @property integer $receipt_type
 * @property integer $transport_type
 * @property integer $delivery_type
 * @property integer $pay_service
 * @property string $note
 * @property string $create_time
 * @property string $receipt_time
 * @property integer $state
 * @property string $receipt_name
 * @property string $create_name
 */
class Order extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, pick_up, send_destination, send_address, send_phone, accept_name, accept_destination, accept_address, accept_phone, goods_weight, goods_num, goods_volume, insured, create_time, state', 'required'),
			array('order_id, pick_up, settlement_way, send_tel, send_phone, accept_tel, accept_phone, goods_weight, goods_pay_weight, goods_num, goods_volume, insured, receipt_order, receipt_type, transport_type, delivery_type, pay_service, state', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>10),
			array('logistics_id, logistics_name, send_destination, accept_destination, goods_name', 'length', 'max'=>60),
			array('sender, receipt_name', 'length', 'max'=>20),
			array('send_address, accept_address, note', 'length', 'max'=>100),
			array('accept_name, create_name', 'length', 'max'=>15),
			array('receipt_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, logistics_id, logistics_name, pick_up, sender, settlement_way, send_destination, send_address, send_tel, send_phone, accept_name, accept_destination, accept_address, accept_tel, accept_phone, goods_name, goods_weight, goods_pay_weight, goods_num, goods_volume, insured, receipt_order, receipt_type, transport_type, delivery_type, pay_service, note, create_time, receipt_time, state, receipt_name, create_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'logistics_id' => 'Logistics',
			'logistics_name' => 'Logistics Name',
			'pick_up' => 'Pick Up',
			'sender' => 'Sender',
			'settlement_way' => 'Settlement Way',
			'send_destination' => 'Send Destination',
			'send_address' => 'Send Address',
			'send_tel' => 'Send Tel',
			'send_phone' => 'Send Phone',
			'accept_name' => 'Accept Name',
			'accept_destination' => 'Accept Destination',
			'accept_address' => 'Accept Address',
			'accept_tel' => 'Accept Tel',
			'accept_phone' => 'Accept Phone',
			'goods_name' => 'Goods Name',
			'goods_weight' => 'Goods Weight',
			'goods_pay_weight' => 'Goods Pay Weight',
			'goods_num' => 'Goods Num',
			'goods_volume' => 'Goods Volume',
			'insured' => 'Insured',
			'receipt_order' => 'Receipt Order',
			'receipt_type' => 'Receipt Type',
			'transport_type' => 'Transport Type',
			'delivery_type' => 'Delivery Type',
			'pay_service' => 'Pay Service',
			'note' => 'Note',
			'create_time' => 'Create Time',
			'receipt_time' => 'Receipt Time',
			'state' => 'State',
			'receipt_name' => 'Receipt Name',
			'create_name' => 'Create Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('logistics_id',$this->logistics_id,true);
		$criteria->compare('logistics_name',$this->logistics_name,true);
		$criteria->compare('pick_up',$this->pick_up);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('settlement_way',$this->settlement_way);
		$criteria->compare('send_destination',$this->send_destination,true);
		$criteria->compare('send_address',$this->send_address,true);
		$criteria->compare('send_tel',$this->send_tel);
		$criteria->compare('send_phone',$this->send_phone);
		$criteria->compare('accept_name',$this->accept_name,true);
		$criteria->compare('accept_destination',$this->accept_destination,true);
		$criteria->compare('accept_address',$this->accept_address,true);
		$criteria->compare('accept_tel',$this->accept_tel);
		$criteria->compare('accept_phone',$this->accept_phone);
		$criteria->compare('goods_name',$this->goods_name,true);
		$criteria->compare('goods_weight',$this->goods_weight);
		$criteria->compare('goods_pay_weight',$this->goods_pay_weight);
		$criteria->compare('goods_num',$this->goods_num);
		$criteria->compare('goods_volume',$this->goods_volume);
		$criteria->compare('insured',$this->insured);
		$criteria->compare('receipt_order',$this->receipt_order);
		$criteria->compare('receipt_type',$this->receipt_type);
		$criteria->compare('transport_type',$this->transport_type);
		$criteria->compare('delivery_type',$this->delivery_type);
		$criteria->compare('pay_service',$this->pay_service);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('receipt_time',$this->receipt_time,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('receipt_name',$this->receipt_name,true);
		$criteria->compare('create_name',$this->create_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function getListByCondition($param = array()){
        $cache = $param['cache'];
        $order_id = $param['order_id'];
        $sender =$param['sender'];
        $send_phone = $param['send_phone'];
        $offset = $param['offset'];
        $limit = $param['pagesize'];
        $cacheKey = "order-get-amount-by-{$order_id}";
        if($cache != null){
            $data = Yii::app()->cache->get($cacheKey);
            if(false != $data){
                return $data;
            }
        }
        $criteria=new CDbCriteria;

        if($order_id != null){
            $criteria->compare('order_id', $order_id);
        }
        if($sender != null){
            $criteria->compare('sender', $sender, true);
        }
        if($send_phone != null){
            $criteria->compare('send_phone', $send_phone, true);
        }
        
        $criteria->offset = $offset;
        $criteria->limit = $limit;
        $data = Order::model()->findAll($criteria);
        if($cache != null){
            Yii::app()->cache->set($cacheKey, $data, 900);
        }
        return $data;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $useCache
     * @param unknown_type $title
     * @param unknown_type $destination
     * @param unknown_type $keywords
     * @param unknown_type $recommend
     */
    public static function getAmountByCondition($param = array()){
        $cache = $param['cache'];
        $order_id = $param['order_id'];
        $sender =$param['sender'];
        $send_phone = $param['send_phone'];
        $offset = $param['offset'];
        $limit = $param['pagesize'];
        $cacheKey = "order-get-amount-by-{$order_id}";
        if($cache!=null){
            $data = Yii::app()->cache->get($cacheKey);
            if(false != $data){
                return $data;
            }
        }

        $criteria=new CDbCriteria;

        if($order_id != null){
            $criteria->compare('order_id', $order_id);
        }
        if($sender != null){
            $criteria->compare('sender', $sender, true);
        }
        if($send_phone != null){
            $criteria->compare('send_phone', $send_phone, true);
        }
       
        $criteria->offset = $offset;
        $criteria->limit = $limit;

        $command = Yii::app()->db->createCommand();
        $data = $command->select("count(id)")
            ->from('tt_order')
            ->where($criteria->condition, $criteria->params)
            ->queryScalar();
        if($cache != null){
            Yii::app()->cache->set($cacheKey, $data, 900);
        }
        return $data;
    }
}
