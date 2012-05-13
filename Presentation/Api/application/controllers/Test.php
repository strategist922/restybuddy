<?php


class TestController extends Yaf_Controller_abstract {

	public function indexaaaaAction()
	{

		echo "test";
		$b = new Business_Wordcount();
		$b->usersearchWordscount();
		//echo "run OK";
		Yaf_Dispatcher::getInstance()->disableView();
		exit; 
	}

	public function indexssAction()
	{
		$latitude = '39.939593';
		$longitude = '116.414536';
		$radius = 1000;
		$p =new Business_Nearplace($latitude,$longitude,$radius);
		$r = $p->getPlaceByBlockID();
		var_dump($r);
		die;
	}

	public function tempAction() 
	{
		echo "test";
		//read table then insert into searchwords

		$type = 1;  //商户 1  +  商街2 + 菜 3  +  优惠 4 +  城市10 + 地区 11 + 商圈 12  
		$words = array("7天","七天","如家","贝塔咖啡","老马拉面");
		$db = Sharding_Mysqli::instance();
		//$words = $db->fetchColumn("select name from dx16.bizdistrict"); 
		echo count($words);
		echo "<br />";	
		foreach ($words as $one)
		{
			$one = trim($one);
			if(!empty($one)) {
				$id = md5($one);
				$words = $db->escape($one);
				$insert = "INSERT INTO search_searchwords (id,words,type) VALUES ('$id','$words',$type)";
				//echo $insert."<br />";
				try {
					$db->execute($insert);
				}catch(Exception $e) {
					//	var_dump($e);
				}	
			}

		}


		Yaf_Dispatcher::getInstance()->disableView();
	}

}
