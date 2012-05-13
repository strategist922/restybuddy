<?php


class FirstpageController extends Yaf_Controller_Abstract {

   public function indexAction()
   {
        $code = 1;
	$data = array();	
	if(isset($_POST['memberID']) && isset($_POST['appID']) && isset($_POST['cityID']) && isset($_POST['latitude']) && isset($_POST['longitude']))
	{
		extract($_POST);
		$code = 0;
		$s = new Service_Firstpage($memberID,$appID,$cityID,$latitude,$longitude);
		$data = $s->data();
			
	}
	
       	Common_Function::jsonReturn($code,$data);
	
   }

}
