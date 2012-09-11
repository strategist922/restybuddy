<?php
require_once "PHPUnit/Framework/TestSuite.php";

class TestNow extends PHPUnit_Framework_TestSuite {

	public function __construct()
	{
		define("APP_PATH", dirname(dirname(dirname(__FILE__)))."/presentation/api");
		require_once dirname(dirname(dirname(__FILE__)))."/env/env.inc.php";
		define("ROOT_URL",'http://api.dev.woshimaijia.com');
		$application = new Yaf_Application(CONF_PATH . "/application.ini");
		$application->bootstrap();

		require_once dirname(__FILE__).'/Service/IdTest.php';
		require_once dirname(__FILE__).'/Library/VenderTest.php';
		require_once dirname(__FILE__).'/Library/ScwsTest.php';
		require_once dirname(__FILE__).'/Service/AdTest.php';
		require_once dirname(__FILE__).'/Library/RemindTest.php';
		require_once dirname(__FILE__).'/Library/TagsTest.php';
		require_once dirname(__FILE__).'/Library/HardwareTest.php';
		require_once dirname(__FILE__).'/Library/UserTest.php';
		require_once dirname(__FILE__).'/Library/StreamTest.php';
		

		$this->setName('Test Suite');


		//$this->addTestSuite('BusinessNearplaceTest');
		//$this->addTestSuite('TimeTest');
		//$this->addTestSuite('VenderTest');
		//$this->addTestSuite('ScwsTest');
		//$this->addTestSuite('AdTest');
		//$this->addTestSuite('RemindTest');
		$this->addTestSuite('StreamTest');

	}

	public static function suite() {
		return new self();
	}
}
