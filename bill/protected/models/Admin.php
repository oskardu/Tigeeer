<?php

/**
 * This is the model class for table "{{admin}}".
 *
 * The followings are the available columns in table '{{admin}}':
 * @property string $id
 * @property string $user_name
 * @property string $user_password
 * @property string $user_email
 * @property string $user_phone
 * @property integer $state
 * @property string $salt
 * @property string $register_date
 * @property string $last_login_date
 * @property string $user_cn_name
 * @property integer $managers
 * @property integer $group
 */
class Admin extends CActiveRecord
{
		const IMAGE_DIR = "uploads/user";
   	const MOBILE_IMAGE_DIR = "uploads/mobile/poster";
    const MAXSIZE=907200;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_cn_name', 'required'),
			array('state, managers, group', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>32),
			array('user_password, user_email', 'length', 'max'=>32),
			array('user_phone', 'length', 'max'=>12),
			array('salt', 'length', 'max'=>64),
			array('user_cn_name', 'length', 'max'=>45),
			array('register_date, last_login_date', 'safe'),
			array('identify', 'length', 'max'=>20),
			array('image', 'length', 'max'=>20),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_name, user_password, user_email, user_phone, state, salt, register_date, last_login_date, user_cn_name, managers, group', 'safe', 'on'=>'search'),
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
			'user_name' => 'User Name',
			'user_password' => 'User Password',
			'user_email' => 'User Email',
			'user_phone' => 'User Phone',
			'state' => 'State',
			'salt' => 'Salt',
			'register_date' => 'Register Date',
			'last_login_date' => 'Last Login Date',
			'user_cn_name' => 'User Cn Name',
			'managers' => 'Managers',
			'group' => 'Group',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('user_password',$this->user_password,true);
		$criteria->compare('user_email',$this->user_email,true);
		$criteria->compare('user_phone',$this->user_phone,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('register_date',$this->register_date,true);
		$criteria->compare('last_login_date',$this->last_login_date,true);
		$criteria->compare('user_cn_name',$this->user_cn_name,true);
		$criteria->compare('managers',$this->managers);
		$criteria->compare('group',$this->group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * beforeSave
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

	    return parent::beforeSave();
	}
	/**
     * 获取管理员所有相关信息
     */
    public function getAdminAttr($state = null)
    {
        $criteria = new CDbCriteria();
        $criteria->order = "state, id";
        if ($state !== null) {
            $criteria->compare('state',$state);
        }
        $adminList = $this->findAll($criteria);
        
        return $adminList;
    }
}