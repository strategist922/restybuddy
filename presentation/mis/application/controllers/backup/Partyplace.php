<?php


class PartyplaceController extends Yaf_Controller_Abstract {

   public function indexAction()
   {
	$code = 1;
	$data = array();
	if(isset($_POST['memberID']) && isset($_POST['appID']) && isset($_POST['listMemberIDs']) && isset($_POST['cityID']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['placeIDs'])) {
		$code = 0;
		extract($_POST);
		$s = new Service_Partyplace($memberID,$appID,$listMemberIDs,$cityID,$latitude,$longitude,$placeIDs);
		$data = $s->data();	
	}
	Common_Function::jsonReturn($code,$data);
   }

}
