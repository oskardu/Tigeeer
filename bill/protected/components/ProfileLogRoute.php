<?php

/**
 * 自定义Yii CProfileLogRoute 类，实现记录profile 到文件
 *
 */
class ProfileLogRoute extends CProfileLogRoute {


    /**
     * 是否直接显示出来
     * @var boolean
     */
    public $isDisplay = true;
    public $logFile = 'nprofile.log';


    protected function render($view,$data)
    {
        $view == null;// just fix warning

		$app=Yii::app();
		$isAjax=$app->getRequest()->getIsAjaxRequest();

        if(!($app instanceof CWebApplication) || $isAjax)
			return;

        if ($this->isDisplay) {
            parent::render($view, $data);
            return;
        }

        $r = $app->request->requestUri;
        $d = date('Y/m/d H:i:s');

        $t = sprintf('%0.5f',Yii::getLogger()->getExecutionTime()) . 's';
        $m = number_format(Yii::getLogger()->getMemoryUsage()/1024) . 'kb';
        $msg = "\n{$d}\t{$r}\tNProfile Summary:\nTime:{$t}\tMemory:{$m}\n";

        foreach($data as $index=>$entry)
        {
            $proc=CHtml::encode($entry[0]);
            $min=sprintf('%0.5f',$entry[2]);
            $max=sprintf('%0.5f',$entry[3]);
            $total=sprintf('%0.5f',$entry[4]);
            $average=sprintf('%0.5f',$entry[4]/$entry[1]);

            $r = $app->request->requestUri;
            //$d = date('Y/m/d H:i:s');
            $proc = str_replace("\n", '', $proc);
            $msg .= "{$proc}\ttotal:{$total}\taverage:{$average}\tmin:{$min}\tmax:{$max}\n";
        }
        //$msg .= "{$d}\t{$r}\tNProfile Summary End\n";

        //Yii::log($msg);
        //Yii::log($msg);
        $this->logToFile($msg);
    }

    /**
     * 记录日志到文件
     * @param string $msg
     */
    protected function logToFile($msg)
    {
        $logPath = realpath(Yii::app()->getRuntimePath());

		$logFile= $logPath . DIRECTORY_SEPARATOR . $this->logFile . '.' . date('Ymd') ;

		$fp = @fopen($logFile,'a');
		@flock($fp,LOCK_EX);
		//foreach($logs as $log)
		//	@fwrite($fp,$this->formatLogMessage($log[0],$log[1],$log[2],$log[3]));
        @fwrite($fp, $msg);
		@flock($fp,LOCK_UN);
		@fclose($fp);
    }
}

?>
