<?php
/**
* 
*/
class CollectController extends ManagerController
{
    
	public function actionIndex(){
    	
    	$page = (int) $this->getQuery('page', 1);
    	// $type = (int) $this->getQuery('type', 1);
    	if($page < 1){
    	    $page = 1;
    	}
    	$pageSize = 15;
    	$offset = ($page - 1) * $pageSize;
    	$total = Collect::getAmountByCondition(false, $status,$type);
        // $total = Collect::getAmountByCondition(false, $status);
        // $list = Poster::getListByCondition($offset, $pageSize, false, $state, $type);
    	$list = Collect::getListByCondition($offset, $pageSize, false, $status);
    	$pagination = CommonHelper::pagination(null, $page, ceil($total/$pageSize), $total);
        $this->render('index', array('list' => $list, 'pagination' => $pagination));
    }
    /**
     * 添加海报
     */
    public function actionAdd(){
        $model = new Collect;
        
        if($this->isPostRequest){
            $model->name= $_POST['name'];
            $model->description = implode('@@', $_POST['description']);
            $model->type = $_POST['type'];
            $model->status = $_POST['status'];
            $model->userid = $_POST['userid'];
        	$model->date = date("Y-m-d H:i:s");
        	if($model->save(false)){
        		$this->redirect(array('index'));
        	}else{
    	        $errImage='<font color=red>某张海报图片超过了大小限制，请做压缩处理</font>';
    	        
    	    }
        }
        $this->render('form', array('errmsg'=>$errImage));        
    }
    /**
     * 编辑海报
     * @param int $id
     */
    public function actionEdit($id){
    	$model = $this->loadModel($id);
    	
    	if($this->isPostRequest){
    	    $model->attributes = $this->getPost('Poster');
    	    if($model->save(false)){
    	        $this->redirect(array('index'));
    	    }else{
    	    	//echo 123;die;
    	        $errImage='<font color=red>海报图片超过了大小限制，请做压缩处理</font>';
    	    }
    	}
    	$this->render('form', array('model' => $model, 'errmsg'=>$errImage));
    }
    
    /**
     * 加载对象
     * @param int $id
     * @throws CHttpException
     * @return unknown
     */
    private function loadModel($id){
    	$model = Collect::model()->findByPk($id);
    	if($model===null)
    		throw new CHttpException(404, 'The requested page does not exist.');
    	return $model;
    }
	
}