<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends Controller
{
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex()
	{
		//echo phpinfo();die;
        $asd= array(
    		'Page' => 3,
    		'PageSize' => '5',
    		'NOWFirstPageImagesID' => null
    	);
        $data=$this->getPrivatePara($asd);

        //$data = HjBase::share()->decode($data,  self::$key);
        //echo $this->getDecode($data);
        echo $data;
		//echo $data->PageSize;
	}
	public function actionAsd()
	{
        $title = $_GET['title'] ? $_GET['title'] : $_POST['title'];
        $body = $_GET['body'] ? $_GET['body'] : $_POST['body'];
        $address = $_GET['address'] ? $_GET['address'] : $_POST['address'];
        $data = array();
        $data['address'] = $address;
        $data['subject'] = $title;
        $data['body'] = $body;
        MailHelper::send($data);
		//echo  json_encode('123');
	}

}