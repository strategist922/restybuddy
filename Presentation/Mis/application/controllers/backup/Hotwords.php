<?php


class HotwordsController extends Yaf_Controller_Abstract {

   public function indexAction()
   {
	$code = 1;
	$data = array();
	if(isset($_POST['memberID']) && isset($_POST['appID']) && isset($_POST['cityID']) && isset($_POST['latitude']) && isset($_POST['longitude']))
	{
		$code = 0;
		extract	($_POST);
		$userHotwordNum = !isset($userHotwordNum) ? 10 : $userHotwordNum;
		$areaHotwordNum = !isset($areaHotwordNum) ? 50 : $areaHotwordNum;
		$cityHotwordNum = !isset($cityHotwordNum) ? 80 : $cityHotwordNum;
		$s = new Service_Hotwords($memberID,$appID,$cityID,$latitude,$longitude,$userHotwordNum,$areaHotwordNum,$cityHotwordNum);
		$data = $s->data();
		
	}
	Common_Function::jsonReturn($code,$data);
   }

}
