<?php
/**
* 
*/
class CollectController extends Controller
{
    const IMAGE_DIR = "uploads/poster";


    public function actionList(){
        header('Content-type:text/json');
        $page = (int) $this->getQuery('page', 1);
        $type = (int) $this->getQuery('type', 1);
        $pageSize = (int) $this->getQuery('pagesize', 5);
        if($page < 1){
            $page = 1;
        }
        $dir = self::IMAGE_DIR;
        $offset = ($page - 1) * $pageSize;
        $total = Collect::getAmountByCondition(false, $status,$type);
        $list = Collect::getListByCondition($offset, $pageSize, false, $status, $type);
        $arr = array();
        foreach ($list as $key => $value) {
            $image = $value['image'];
            $value['path'] = 'http://'.$_SERVER["HTTP_HOST"].'/'.$dir.'/'.$image;
            array_push($arr, $value);

        }
        echo json_encode(array('code' => 200, 'msg' => 'illustration list', 'data' => $arr));
    }

    public function actionGet() {
        header('Content-type:text/json');
        $id = $_GET['id'];
        $res = Yii::app()->db->createCommand()
        ->from('tt_collect g')
        ->select('g.*,gt.user_name,gt.image as avatar')
        ->join('tt_admin gt', 'g.userid=gt.id')
        ->where('g.`id`='.$id)
        ->queryRow();

        $dir = self::IMAGE_DIR;

        if ($res) {
            $image = $res['image'];
            $avatar = $res['avatar'];
            $res['path'] = "http://{$_SERVER["HTTP_HOST"]}/{$dir}/{$image}";
            $res['avatar'] = "http://{$_SERVER["HTTP_HOST"]}/{$dir}/{$avatar}";
            // $res['description'] = json_decode($res['description']);
            echo json_encode(array('code' => 200, 'msg' => 'collect', 'data' => $res));
        } else {
            echo json_encode(array('code' => 100, 'msg' => '此条信息不存在'));
        }
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
            $model->instro = $_POST['instro'];
            $model->status = 1;
            $model->userid = $_POST['userid'];
            $model->date = date("Y-m-d H:i:s");
            if($model->save(false)){
                echo json_encode(array('code' => 200, 'msg' => 'collect'));
            }else{
                echo json_encode(array('code' => 100, 'msg' => 'collect'));
            }
        } else {
            echo json_encode(array('code' => 100, 'msg' => 'collect'));
        }
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