<?php
class Controller extends CController
{
    public $menu = array();
	public $breadcrumbs = array();

    protected static $key;
    /**
     * 页面关键字
     * @var string
     */
    private $_keywords;

    /**
     * 页面描述
     * @var string
     */
    private $_description;

	/**
	 * 自定义的view路径
	 * @var string
	 */
	private $_viewPath;

	/**
	 * @var CHttpRequest
	 */
	private $_request;

	/**
	 * Initializes the controller.
	 * This method is called by the application before the controller starts to execute.
	 * You may override this method to perform the needed initialization for the controller.
	 * @see CController
	 */
	public function init()
	{
	    parent::init();
        $session_id =  $_GET['token'];
        if($session_id != false){

            session_id($session_id);
            Yii::app()->getSession()->open();

        }
		$this->_request = Yii::app()->request;
        $cookie = Yii::app()->request->getCookies();
        self::$key = Yii::app()->params['key'];
        
        
	}

   

    /**
     * 设置页面标题
     * @param mixed $title 标题
     * @param boolean $flag 是否不重新赋值
     */
    protected function _setPageTitle($title, $flag = true)
    {
        if (is_array($title)) $title = implode(' - ', $title);
        if ($flag) {
            $title = "{$title} - {$this->pageTitle}";
        }
        $this->pageTitle = $title;
    }

    /**
     * 设置页面keywords
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->_keywords = $keywords;
    }

    /**
     * 获取页面keywords
     * @return string
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * 设置页面description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * 获取页面description
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * 指定view路径，路径请使用别名
     * @param string $pathAlias path alias to be imported
     */
    protected function setViewPath($pathAlias)
    {
        if ($pathAlias) {
            $path = Yii::getPathOfAlias($pathAlias);
            if (is_dir($path)) {
                $this->_viewPath = $path;
            }
        }
    }

    /**
     * 重写getViewPath方法
     * 如果自定义了view路径，那么就使用指定的view
     * 否者使用原始的view
     * @see CController::getViewPath()
     * @return string
     */
    public function getViewPath()
    {
        if ($this->_viewPath) {
            return $this->_viewPath;
        } else {
            return parent::getViewPath();
        }
    }

    /**
     * 获取CHttpRequest
     * @return CHttpRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Returns the named GET parameter value.
     * If the GET parameter does not exist, the second parameter to this method will be returned.
     * @param string $name the GET parameter name
     * @param mixed $defaultValue the default parameter value if the GET parameter does not exist.
     * @return mixed the GET parameter value
     * @see CHttpRequest
     */
    public function getQuery($name, $defaultValue = null)
    {
        return $this->_request->getQuery($name, $defaultValue);
    }

    /**
     * Returns the named POST parameter value.
     * If the POST parameter does not exist, the second parameter to this method will be returned.
     * @param string $name the POST parameter name
     * @param mixed $defaultValue the default parameter value if the POST parameter does not exist.
     * @return mixed the POST parameter value
     * @see CHttpRequest
     */
    public function getPost($name, $defaultValue = null)
    {
        return $this->_request->getPost($name, $defaultValue);
    }

    /**
     * Returns the named FILE parameter value.
     * @param string $name the FILE parameter name
     * @return mixed the FILE parameter value
     */
    public function getPostFile($name)
    {
        return isset($_FILES[$name]) ? $_FILES[$name] : null;
    }

    /**
     * Returns part of the request URL that is after the question mark.
     * @return string part of the request URL that is after the question mark
     * @see CHttpRequest
     */
    public function getQueryString()
    {
        return $this->_request->getQueryString();
    }

    /**
     * Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     * @see CHttpRequest
     */
    public function getIsPostRequest()
    {
        return $this->_request->getIsPostRequest();
    }

    /**
     * Returns whether this is an AJAX (XMLHttpRequest) request.
     * @return boolean whether this is an AJAX (XMLHttpRequest) request.
     * @see CHttpRequest
     */
    public function getIsAjaxRequest()
    {
        return $this->_request->getIsAjaxRequest();
    }
    
    /**
     * 返回PrivatePara
     * @return string
     */
    public function getPrivatePara($privateParaClass) {

        if($privateParaClass) {
            return HjBase::share()->encode(json_encode($privateParaClass),  self::$key);
        }
        else {
            return null;
        }
    }
    /**
     * 解密PrivatePara
     * @return string
     */
    public function getDecode($privateParaClass) {

        if($privateParaClass) {
            return json_decode(HjBase::share()->decode($privateParaClass,  self::$key));
        }
        else {
            return null;
        }
    }
   

}