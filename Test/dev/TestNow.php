<?php
require_once "PHPUnit/Framework/TestSuite.php";


class TestNow extends PHPUnit_Framework_TestSuite {
 
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
   	
	
	//require_once dirname(__FILE__).'/Library/BusinessNearplaceTest.php';	
	require_once dirname(__FILE__).'/Library/TimeTest.php';
	require_once dirname(__FILE__).'/Service/UserviewTest.php';
	$this->setName('Test Suite');
	

	//$this->addTestSuite('BusinessNearplaceTest');
	//$this->addTestSuite('TimeTest');
	$this->addTestSuite('UserviewTest');
   }

   public static function suite() {
	return new self();
   }

	   

}
