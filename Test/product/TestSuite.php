<?php
require_once "PHPUnit/Framework/TestSuite.php";


class serviceSuite extends PHPUnit_Framework_TestSuite {
 
   public function __construct()
   {
	
	require_once dirname(dirname(dirname(__FILE__)))."/Env/env.inc.php";
        define("APP_PATH", ROOT_PATH."/Presentation/Api");	
	define("RUN_MODE",'product');

	define("ROOT_URL",'http://s.dianxing.cn');
	
        //初始化AP程序
	$application = new Yaf_Application(CONF_PATH . DIRECTORY_SEPARATOR. RUN_MODE. DIRECTORY_SEPARATOR . "application.ini");
	
	//应用程序初始化
	$application->bootstrap();
   	
       //require_once 'Service/FirstpageTest.php';
	require_once './Service/HotwordsTest.php';
	require_once './Service/UsersearchTest.php';
	require_once './Service/WordexpandTest.php';
	//require_once 'Service/PartyplaceTest';
	
	$this->setName('Test Suite');
	
	//$this->addTestSuite('FirstpageTest'); 
	$this->addTestSuite('HotwordsTest');
	$this->addTestSuite('UsersearchTest');
	$this->addTestSuite('WordexpandTest');

   }

   public static function suite() {
	return new self();
   }

	   

}
