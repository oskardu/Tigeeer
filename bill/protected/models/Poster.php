<?php

/**
 * This is the model class for table "{{poster}}".
 *
 * The followings are the available columns in table '{{poster}}':
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $create_time
 * @property integer $state
 */
class Poster extends CActiveRecord
{
	const IMAGE_DIR = "uploads/poster";
    const MOBILE_IMAGE_DIR = "uploads/mobile/poster";
    const MAXSIZE=907200;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{poster}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'numerical', 'integerOnly'=>true),
			array('title, image', 'length', 'max'=>256),
			array('create_time, type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, image, create_time, state, type', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'image' => 'Image',
			'create_time' => 'Create Time',
			'state' => 'State',
			'type' => 'Type',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Poster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
     * 重写beforeSave
     */
    protected function beforeSave(){
        if($_FILES["image"]){
            $image = $_FILES["image"];
            if($image['size']> self::MAXSIZE){
                return false;
            }
            
            if ($image['error'] == UPLOAD_ERR_OK) {
                $dir = self::IMAGE_DIR;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $name = time().".".$extension;
                if (move_uploaded_file($image['tmp_name'], "{$dir}/{$name}")) {
                    $this->image = $name;
                    $imageFile = "{$dir}/{$name}";
                    //CommonHelper::generateThumb($imageFile, true);
                }else{
                    return false;
                }
            }
        }

        if($_FILES["mobile_image"]){
            $image = $_FILES["mobile_image"];
            if($image['size']> self::MAXSIZE){
                return false;
            }
            if ($image['error'] == UPLOAD_ERR_OK) {
                $dir = self::MOBILE_IMAGE_DIR;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $name = time().".".$extension;
                if (move_uploaded_file($image['tmp_name'], "{$dir}/{$name}")) {
                    $this->mobile_image = $name;
                    $imageFile = "{$dir}/{$name}";
                    CommonHelper::generateThumb($imageFile, true);
                }else{
                    return false;
                }
            }
        }

        return parent::beforeSave();
    }

    /**
     * 按条件获取海报列表
     * @param number $offset
     * @param number $pageSize
     * @param string $useCache
     * @param unknown $state
     * @param string $type
     */
    public static function getListByCondition($offset = 0, $pageSize = 5, $useCache = true, $state = null,$type=null){
        $cacheKey = "poster-list-by-condition-{$offset}-{$pageSize}-{$state}";
        //$result = Yii::app()->cache->get($cacheKey);
        if(false != $result)
            return $result;
        $sql = "SELECT * FROM tt_poster WHERE 1=1";
        $condition = "";

        if($state){
            $condition .= " AND state = :state";
        }
        if($type){
            $condition .= " AND type = :type";
        }

        $orderByConditon = "";

        if(!empty($orderBy)){
            $orderByConditon = " ORDER BY ".$orderBy.", create_time desc";
        }
        $limit = " LIMIT :offset, :pageSize ";
        $command = Yii::app()->db->createCommand($sql.$condition.$orderByConditon.$limit);
        if($state){
            $command->bindParam(':state', $state);
        }
        if($type){
            $command->bindParam(':type', $type);
        }
        
        $command->bindParam(':offset', $offset);
        $command->bindParam(':pageSize', $pageSize);
        $result = $command->queryAll();
        if($useCache)
            Yii::app()->cache->set($cacheKey, $result, 900);
        return $result;
    }

    /**
     * 获取数量
     * @param string $useCache
     * @param string $state
     * @param string $type
     * @return unknown
     */
    public static function getAmountByCondition($useCache = true, $state = null){
        $cacheKey = "poster-amount-by-condition-{$state}";
        //$result = Yii::app()->cache->get($cacheKey);
        if(false != $result)
            return $result;
        $sql = "SELECT count(id) FROM tt_poster WHERE 1=1";
        $condition = "";

        if($state){
            $condition .= " AND state = :state";
        }
        if($type){
            $condition .= " AND type = :type";
        }
        $command = Yii::app()->db->createCommand($sql.$condition);
        if($state){
            $command->bindParam(':state', $state);
        }
        if($type){
            $command->bindParam(':type', $type);
        }
        $result = $command->queryScalar();
        if($useCache)
            Yii::app()->cache->set($cacheKey, $result, 900);
        return $result;
    }
}
