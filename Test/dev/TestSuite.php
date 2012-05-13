<?php
require_once "PHPUnit/Framework/TestSuite.php";


class serviceSuite extends PHPUnit_Framework_TestSuite {
 
   public function __construct()
   {
	
	require_once dirname(dirname(dirname(__FILE__)))."/Env/env.inc.php";
        define("APP_PATH", ROOT_PATH."/Presentation/Api");	
	define("RUN_MODE",'dev');

	define("ROOT_URL",'http://s.dianxing.cn');
	
        //初始化AP程序
	$application = new Yaf_Application(CONF_PATH . DIRECTORY_SEPARATOR. RUN_MODE. DIRECTORY_SEPARATOR . "application.ini");
	
	//应用程序初始化
	$application->bootstrap();
   	
       //require_once 'Service/FirstpageTest.php';
	require_once dirname(__FILE__).'/Service/HotwordsTest.php';
	require_once dirname(__FILE__). '/Service/UsersearchTest.php';
	require_once dirname(__FILE__).'/Service/WordexpandTest.php';
	require_once dirname(__FILE__).'/Service/UserviewTest.php';
	//require_once 'Service/PartyplaceTest';
	require_once dirname(__FILE__).'/Library/ShardingRedisTest.php';
	require_once dirname(__FILE__).'/Library/ShardingMysqliTest.php';
	
	require_once dirname(__FILE__).'/Library/BusinessNearplaceTest.php';	

	$this->setName('Test Suite');
	
	//$this->addTestSuite('FirstpageTest'); 
	$this->addTestSuite('HotwordsTest');
	$this->addTestSuite('UsersearchTest');
	$this->addTestSuite('WordexpandTest');
	$this->addTestSuite('UserviewTest');

	$this->addTestSuite('ShardingRedisTest');
	$this->addTestSuite('ShardingMysqliTest');

	$this->addTestSuite('BusinessNearplaceTest');


   }

   public static function suite() {
	return new self();
   }

	   

}
