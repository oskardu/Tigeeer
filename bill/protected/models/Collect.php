<?php

/**
 * This is the model class for table "{{collect}}".
 *
 * The followings are the available columns in table '{{collect}}':
 * @property integer $id
 * @property integer $userid
 * @property string $name
 * @property string $path
 * @property string $type
 * @property string $description
 * @property string $status
 * @property string $date
 * @property double $width
 * @property double $height
 */
class Collect extends CActiveRecord
{
	const IMAGE_DIR = "uploads/poster";
    const MOBILE_IMAGE_DIR = "uploads/mobile/poster";
    const MAXSIZE=907200;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{collect}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, name, path, type, description, date', 'required'),
			array('userid', 'numerical', 'integerOnly'=>true),
			array('width, height', 'numerical'),
			array('name, status', 'length', 'max'=>20),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, name, path, type, description, status, date, width, height,image', 'safe', 'on'=>'search'),
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
			'userid' => 'Userid',
			'name' => 'Name',
			'path' => 'Path',
			'type' => 'Type',
			'description' => 'Description',
			'status' => 'Status',
			'date' => 'Date',
			'width' => 'Width',
			'height' => 'Height',
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
		$criteria->compare('userid',$this->userid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Collect the static model class
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
        	
            $images = $_FILES["image"];
            $image_name = array();
            $image_width = array();
            $image_height = array();
            for ($i = 0; $i < count($images['name']); $i++) {
                // var_dump($images['tmp_name'][$i]);
                if ($images['tmp_name'][$i]) {
                    $imageInfo = getimagesize($images['tmp_name'][$i]);
                    if($images['size'][$i]> self::MAXSIZE){
                        return false;
                    }
                
                    if ($images['error'][$i] == UPLOAD_ERR_OK) {
                        $dir = self::IMAGE_DIR;
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $extension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
                        $name = time().'_w'.$imageInfo[0].'_h'.$imageInfo[1].".".$extension;
                        
                        if (move_uploaded_file($images['tmp_name'][$i], "{$dir}/{$name}")) {
                            array_push($image_name, $name);
                            array_push($image_width, $imageInfo[0]);
                            array_push($image_height, $imageInfo[1]);
                            //CommonHelper::generateThumb($imageFile, true);
                        }else{
                            return false;
                        }
                    }
                }
            }
            $this->image = implode(",", $image_name);
            $this->width = implode(",", $image_width);
            $this->height = implode(",", $image_height);
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
    public static function getListByCondition($offset = 0, $pageSize = 5, $useCache = true, $status = null,$type=null){
        $cacheKey = "collect-list-by-condition-{$offset}-{$pageSize}";
        // $cacheKey = "poster-list-by-condition-{$offset}-{$pageSize}-{$state}";
        //$result = Yii::app()->cache->get($cacheKey);
        if(false != $result)
            return $result;
        $sql = "SELECT * FROM tt_collect WHERE 1=1";
        $condition = "";

        if($status){
            $condition .= " AND status = :status";
        }
        if($type){
            $condition .= " AND type = :type";
        }

        $orderByConditon = " order by date desc";

        if(!empty($orderBy)){
            $orderByConditon = " ORDER BY ".$orderBy.", date desc";
        }
        $limit = " LIMIT :offset, :pageSize ";
        $command = Yii::app()->db->createCommand($sql.$condition.$orderByConditon.$limit);
        if($status){
            $command->bindParam(':status', $status);
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
    public static function getAmountByCondition($useCache = true, $status = null){
        $cacheKey = "poster-amount-by-condition-{$status}";
        //$result = Yii::app()->cache->get($cacheKey);
        if(false != $result)
            return $result;
        $sql = "SELECT count(id) FROM tt_collect WHERE 1=1";
        $condition = "";

        if($status){
            $condition .= " AND state = :status";
        }
        if($type){
            $condition .= " AND type = :type";
        }
        $command = Yii::app()->db->createCommand($sql.$condition);
        if($status){
            $command->bindParam(':status', $state);
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
